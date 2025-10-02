-- =====================================================
-- ZENDO DATABASE SCHEMA - VERSION OPTIMISÉE
-- =====================================================

-- Table des Utilisateurs (Expéditeurs et Voyageurs/Livreurs)
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255),
    city VARCHAR(50),
    country VARCHAR(50) DEFAULT 'Côte d\'Ivoire',
    bio TEXT,
    reliability_score DECIMAL(3,2) DEFAULT 0.00 CHECK (reliability_score >= 0 AND reliability_score <= 5.00),
    role ENUM('expeditor', 'traveler', 'both') NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    two_factor_auth BOOLEAN DEFAULT FALSE,
    last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    wallet_balance DECIMAL(12,2) DEFAULT 0.00 CHECK (wallet_balance >= 0),
    
    -- Index pour optimiser les recherches
    INDEX idx_phone (phone_number),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_city (city),
    INDEX idx_verified (is_verified),
    INDEX idx_active (is_active),
    INDEX idx_last_active (last_active)
);

-- Table des Voyageurs/Livreurs - Version optimisée
CREATE TABLE Travelers (
    traveler_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    departure_city VARCHAR(50) NOT NULL,
    arrival_city VARCHAR(50) NOT NULL,
    departure_date DATE NOT NULL,
    arrival_date DATE NOT NULL,
    departure_time TIME,
    arrival_time TIME,
    available_weight DECIMAL(5,2) NOT NULL CHECK (available_weight > 0),
    max_package_weight DECIMAL(5,2) DEFAULT 10.00,
    price_per_kg DECIMAL(8,2) NOT NULL CHECK (price_per_kg > 0),
    transport_type ENUM('avion', 'bus', 'voiture', 'train') DEFAULT 'avion',
    status ENUM('active', 'in_progress', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_online BOOLEAN DEFAULT FALSE,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    special_instructions TEXT,
    accepts_fragile BOOLEAN DEFAULT TRUE,
    accepts_documents BOOLEAN DEFAULT TRUE,
    verification_required BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- Index pour optimiser les recherches
    INDEX idx_user_id (user_id),
    INDEX idx_departure_city (departure_city),
    INDEX idx_arrival_city (arrival_city),
    INDEX idx_departure_date (departure_date),
    INDEX idx_status (status),
    INDEX idx_route (departure_city, arrival_city),
    INDEX idx_date_range (departure_date, arrival_date),
    INDEX idx_online (is_online),
    INDEX idx_transport (transport_type)
);

-- Table des Documents d'Identité - Version sécurisée
CREATE TABLE IdentityDocuments (
    document_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    document_type ENUM('passport', 'national_id', 'driver_license', 'residence_permit') NOT NULL,
    document_number VARCHAR(50) NOT NULL,
    document_photo VARCHAR(255) NOT NULL,
    document_photo_back VARCHAR(255), -- Pour recto-verso
    expiry_date DATE,
    issuing_country VARCHAR(50) DEFAULT 'Côte d\'Ivoire',
    verification_status ENUM('pending', 'verified', 'rejected', 'expired') DEFAULT 'pending',
    rejection_reason TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    verified_by INT NULL, -- Admin qui a vérifié
    is_primary BOOLEAN DEFAULT FALSE, -- Document principal
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- Index pour optimiser les recherches
    INDEX idx_user_id (user_id),
    INDEX idx_document_type (document_type),
    INDEX idx_verification_status (verification_status),
    INDEX idx_expiry_date (expiry_date),
    UNIQUE KEY unique_user_document (user_id, document_type, document_number)
);

-- Table des Colis - Version complète avec tracking
CREATE TABLE Packages (
    package_id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    sender_id INT NOT NULL,
    traveler_id INT,
    recipient_name VARCHAR(100) NOT NULL,
    recipient_phone VARCHAR(15) NOT NULL,
    recipient_email VARCHAR(100),
    recipient_address TEXT NOT NULL,
    package_description TEXT NOT NULL,
    category ENUM('documents', 'electronics', 'clothing', 'food', 'medicine', 'fragile', 'other') DEFAULT 'other',
    weight DECIMAL(5,2) NOT NULL CHECK (weight > 0),
    dimensions_length DECIMAL(5,2),
    dimensions_width DECIMAL(5,2),
    dimensions_height DECIMAL(5,2),
    declared_value DECIMAL(12,2) DEFAULT 0.00,
    insurance_value DECIMAL(12,2) DEFAULT 0.00,
    fragile BOOLEAN DEFAULT FALSE,
    requires_signature BOOLEAN DEFAULT FALSE,
    pickup_address TEXT NOT NULL,
    delivery_address TEXT NOT NULL,
    pickup_city VARCHAR(50) NOT NULL,
    delivery_city VARCHAR(50) NOT NULL,
    estimated_delivery_fee DECIMAL(8,2),
    final_delivery_fee DECIMAL(8,2),
    status ENUM('pending', 'accepted', 'picked_up', 'in_transit', 'arrived', 'out_for_delivery', 'delivered', 'cancelled', 'returned') DEFAULT 'pending',
    priority ENUM('standard', 'express', 'urgent') DEFAULT 'standard',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL,
    picked_up_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    delivery_photo VARCHAR(255),
    delivery_signature VARCHAR(255),
    pickup_photo VARCHAR(255),
    special_instructions TEXT,
    
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (traveler_id) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    -- Index pour optimiser les recherches
    INDEX idx_tracking_number (tracking_number),
    INDEX idx_sender_id (sender_id),
    INDEX idx_traveler_id (traveler_id),
    INDEX idx_status (status),
    INDEX idx_pickup_city (pickup_city),
    INDEX idx_delivery_city (delivery_city),
    INDEX idx_route (pickup_city, delivery_city),
    INDEX idx_created_at (created_at),
    INDEX idx_priority (priority),
    INDEX idx_category (category)
);

-- Table des Transactions - Version sécurisée avec audit
CREATE TABLE Transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    transaction_reference VARCHAR(50) UNIQUE NOT NULL,
    package_id INT,
    sender_id INT NOT NULL,
    traveler_id INT,
    transaction_type ENUM('delivery_fee', 'commission', 'refund', 'wallet_topup', 'wallet_withdrawal', 'penalty') NOT NULL,
    amount DECIMAL(12,2) NOT NULL CHECK (amount > 0),
    currency VARCHAR(3) DEFAULT 'XOF',
    commission_rate DECIMAL(5,2) DEFAULT 0.00,
    commission_amount DECIMAL(12,2) DEFAULT 0.00,
    net_amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('mobile_money', 'orange_money', 'mtn_money', 'moov_money', 'bank_transfer', 'cash', 'card', 'wallet') NOT NULL,
    payment_provider VARCHAR(50),
    transaction_status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'disputed') DEFAULT 'pending',
    failure_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    payment_reference VARCHAR(100),
    external_reference VARCHAR(100), -- Référence du fournisseur de paiement
    receipt_url VARCHAR(255),
    notes TEXT,
    processed_by INT, -- Admin qui a traité
    
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE SET NULL,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (traveler_id) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    -- Index pour optimiser les recherches
    INDEX idx_transaction_reference (transaction_reference),
    INDEX idx_package_id (package_id),
    INDEX idx_sender_id (sender_id),
    INDEX idx_traveler_id (traveler_id),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_status (transaction_status),
    INDEX idx_payment_method (payment_method),
    INDEX idx_created_at (created_at),
    INDEX idx_amount (amount),
    INDEX idx_external_reference (external_reference)
);

-- Table des Messages - Version complète avec chat
CREATE TABLE Messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    conversation_id VARCHAR(50) NOT NULL, -- Pour grouper les messages
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    package_id INT,
    message_type ENUM('text', 'image', 'file', 'location', 'system') DEFAULT 'text',
    message_content TEXT NOT NULL,
    attachment_url VARCHAR(255),
    attachment_type VARCHAR(50),
    attachment_size INT,
    location_lat DECIMAL(10,8),
    location_lng DECIMAL(11,8),
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivered_at TIMESTAMP NULL,
    read_at TIMESTAMP NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    reply_to_message_id INT NULL, -- Pour les réponses
    is_system_message BOOLEAN DEFAULT FALSE,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_reported BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE SET NULL,
    FOREIGN KEY (reply_to_message_id) REFERENCES Messages(message_id) ON DELETE SET NULL,
    
    -- Index pour optimiser les recherches
    INDEX idx_conversation_id (conversation_id),
    INDEX idx_sender_id (sender_id),
    INDEX idx_receiver_id (receiver_id),
    INDEX idx_package_id (package_id),
    INDEX idx_sent_at (sent_at),
    INDEX idx_is_read (is_read),
    INDEX idx_message_type (message_type),
    INDEX idx_conversation_time (conversation_id, sent_at)
);

-- Table des Propositions - Version améliorée
CREATE TABLE Proposals (
    proposal_id INT PRIMARY KEY AUTO_INCREMENT,
    package_id INT NOT NULL,
    traveler_id INT NOT NULL,
    proposed_price DECIMAL(10,2) NOT NULL CHECK (proposed_price > 0),
    original_price DECIMAL(10,2),
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    estimated_pickup_date DATE,
    estimated_delivery_date DATE,
    message TEXT,
    terms_conditions TEXT,
    status ENUM('pending', 'accepted', 'rejected', 'expired', 'withdrawn') DEFAULT 'pending',
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    rejection_reason TEXT,
    
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE CASCADE,
    FOREIGN KEY (traveler_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- Index pour optimiser les recherches
    INDEX idx_package_id (package_id),
    INDEX idx_traveler_id (traveler_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_expires_at (expires_at)
);

-- Table des Notifications - Version complète
CREATE TABLE Notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error', 'promotion', 'system', 'security') DEFAULT 'info',
    category ENUM('package', 'payment', 'account', 'system', 'promotion', 'security') DEFAULT 'system',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    is_read BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255), -- URL pour action directe
    action_text VARCHAR(50), -- Texte du bouton d'action
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    related_package_id INT,
    related_transaction_id INT,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (related_package_id) REFERENCES Packages(package_id) ON DELETE SET NULL,
    FOREIGN KEY (related_transaction_id) REFERENCES Transactions(transaction_id) ON DELETE SET NULL,
    
    -- Index pour optimiser les recherches
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_category (category),
    INDEX idx_is_read (is_read),
    INDEX idx_priority (priority),
    INDEX idx_created_at (created_at),
    INDEX idx_expires_at (expires_at)
);

-- Table des Évaluations - Version complète
CREATE TABLE Evaluations (
    evaluation_id INT PRIMARY KEY AUTO_INCREMENT,
    evaluator_id INT NOT NULL,
    evaluated_id INT NOT NULL,
    package_id INT NOT NULL,
    transaction_id INT,
    overall_rating DECIMAL(2,1) CHECK (overall_rating >= 1.0 AND overall_rating <= 5.0),
    communication_rating INT CHECK (communication_rating >= 1 AND communication_rating <= 5),
    punctuality_rating INT CHECK (punctuality_rating >= 1 AND punctuality_rating <= 5),
    care_rating INT CHECK (care_rating >= 1 AND care_rating <= 5),
    professionalism_rating INT CHECK (professionalism_rating >= 1 AND professionalism_rating <= 5),
    comment TEXT,
    pros TEXT, -- Points positifs
    cons TEXT, -- Points à améliorer
    would_recommend BOOLEAN DEFAULT TRUE,
    is_anonymous BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT FALSE, -- Évaluation vérifiée
    is_flagged BOOLEAN DEFAULT FALSE,
    flag_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (evaluator_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (evaluated_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE CASCADE,
    FOREIGN KEY (transaction_id) REFERENCES Transactions(transaction_id) ON DELETE SET NULL,
    
    -- Contrainte pour éviter les doublons
    UNIQUE KEY unique_evaluation (evaluator_id, evaluated_id, package_id),
    
    -- Index pour optimiser les recherches
    INDEX idx_evaluator_id (evaluator_id),
    INDEX idx_evaluated_id (evaluated_id),
    INDEX idx_package_id (package_id),
    INDEX idx_overall_rating (overall_rating),
    INDEX idx_created_at (created_at),
    INDEX idx_is_verified (is_verified)
);

-- Table des Profils - mise à jour avec zone d’activité
CREATE TABLE Profiles (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    badge ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
    total_packages_sent INT DEFAULT 0,
    successful_deliveries INT DEFAULT 0,
    canceled_packages INT DEFAULT 0,
    reliability_percentage DECIMAL(5, 2) DEFAULT 0.00,
    joined_date DATE,
    activity_zone VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Table des Historiques - inchangée
CREATE TABLE Histories (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    package_id INT,
    user_id INT,
    action_type ENUM('published', 'modified', 'deleted', 'accepted', 'rejected', 'delivered', 'evaluated') NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Nouvelle Table des Trajets Proposés
CREATE TABLE ProposedTrips (
    trip_id INT PRIMARY KEY AUTO_INCREMENT,
    traveler_id INT,
    origin_city VARCHAR(50) NOT NULL,
    destination_city VARCHAR(50) NOT NULL,
    trip_date DATE NOT NULL,
    estimated_time TIME,
    vehicle_type ENUM('moto', 'car', 'bus', 'plane') NOT NULL,
    max_packages INT NOT NULL,
    max_weight_kg DECIMAL(10, 2) NOT NULL,
    accepted_package_types VARCHAR(100),
    requires_secure_payment BOOLEAN DEFAULT FALSE,
    express_delivery BOOLEAN DEFAULT FALSE,
    vehicle_photo VARCHAR(255),
    status ENUM('active', 'completed', 'canceled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (traveler_id) REFERENCES Travelers(traveler_id)
);

-- =====================================================
-- NOUVELLES TABLES POUR UN SYSTÈME COMPLET
-- =====================================================

-- Table des Sessions Utilisateur
CREATE TABLE UserSessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device_type ENUM('mobile', 'tablet', 'desktop', 'unknown') DEFAULT 'unknown',
    location_country VARCHAR(50),
    location_city VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    INDEX idx_user_id (user_id),
    INDEX idx_is_active (is_active),
    INDEX idx_expires_at (expires_at),
    INDEX idx_last_activity (last_activity)
);

-- Table des Logs d'Audit
CREATE TABLE AuditLogs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_table_name (table_name),
    INDEX idx_created_at (created_at)
);

-- Table des Paramètres Système
CREATE TABLE SystemSettings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'integer', 'decimal', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE, -- Visible côté client
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    
    FOREIGN KEY (updated_by) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    INDEX idx_setting_key (setting_key),
    INDEX idx_is_public (is_public)
);

-- Table des Codes Promotionnels
CREATE TABLE PromoCodes (
    promo_id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed_amount') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0.00,
    max_discount_amount DECIMAL(10,2),
    usage_limit INT DEFAULT NULL, -- NULL = illimité
    usage_count INT DEFAULT 0,
    user_limit INT DEFAULT 1, -- Limite par utilisateur
    is_active BOOLEAN DEFAULT TRUE,
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_until TIMESTAMP NOT NULL,
    applicable_to ENUM('all', 'new_users', 'specific_users') DEFAULT 'all',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    
    FOREIGN KEY (created_by) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    INDEX idx_code (code),
    INDEX idx_is_active (is_active),
    INDEX idx_valid_dates (valid_from, valid_until)
);

-- Table d'utilisation des codes promo
CREATE TABLE PromoCodeUsage (
    usage_id INT PRIMARY KEY AUTO_INCREMENT,
    promo_id INT NOT NULL,
    user_id INT NOT NULL,
    package_id INT,
    transaction_id INT,
    discount_applied DECIMAL(10,2) NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (promo_id) REFERENCES PromoCodes(promo_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE SET NULL,
    FOREIGN KEY (transaction_id) REFERENCES Transactions(transaction_id) ON DELETE SET NULL,
    
    UNIQUE KEY unique_user_promo (promo_id, user_id, package_id),
    
    INDEX idx_promo_id (promo_id),
    INDEX idx_user_id (user_id),
    INDEX idx_used_at (used_at)
);

-- Table des Réclamations/Support
CREATE TABLE SupportTickets (
    ticket_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    package_id INT,
    transaction_id INT,
    category ENUM('delivery_issue', 'payment_issue', 'account_issue', 'technical_issue', 'other') NOT NULL,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    status ENUM('open', 'in_progress', 'waiting_response', 'resolved', 'closed') DEFAULT 'open',
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    resolution TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    assigned_to INT, -- Admin assigné
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id) ON DELETE SET NULL,
    FOREIGN KEY (transaction_id) REFERENCES Transactions(transaction_id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES Users(user_id) ON DELETE SET NULL,
    
    INDEX idx_ticket_number (ticket_number),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_category (category),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- ANALYSE DES TABLES ET RELATIONS MISES À JOUR
-- =====================================================
Analyse de Base de Données Mise à Jour

Table Users :

Mise à jour avec last_active pour gérer le statut en ligne ("En ligne", "Dernière connexion") dans "Messagerie" et "Profil".
Représente tous les utilisateurs (expéditeurs et voyageurs/livreurs).


Table Travelers :

Mise à jour avec vehicle_type en ENUM, avg_delivery_time, et punctuality_rate pour refléter les performances dans "Profil".
Détails spécifiques aux livreurs, liée à Users.


Table IdentityDocuments :

Inchangée. Stocke les documents requis, liée à Users.


Table Packages :

Inchangée. Gère les colis avec delivery_type, statuts, et champs pour publication.
Liée à Users (expéditeur) et Travelers (voyageur).


Table Transactions :

Mise à jour avec payment_method pour refléter les options de recharge dans "Portefeuille" (Mobile Money, carte, manuel).
Gère les mouvements financiers, liée à Users et Packages.


Table Messages :

Mise à jour avec gps_location pour partager la position et is_reported pour la modération dans "Messagerie".
Gère la messagerie sécurisée, liée à Users et Packages.


Table Proposals :

Mise à jour avec accepted_package_types pour gérer les types de colis acceptés dans "Proposer un Trajet".
Gère les offres et négociations, liée à Packages et Travelers.


Table Notifications :

Mise à jour avec type incluant 'trip' pour les notifications de trajets dans "Proposer un Trajet".
Stocke les notifications, liée à Users, Packages, et Proposals.


Table Evaluations :

Inchangée. Gère les notes et commentaires, liée à Packages et Users.


Table Profiles :

Mise à jour avec activity_zone pour refléter la zone d’activité dans "Profil".
Gère les badges et statistiques, liée à Users.


Table Histories :

Inchangée. Gère l'archivage des actions, liée à Packages et Users.


Table ProposedTrips (Nouvelle) :

Gère les trajets proposés par les voyageurs dans "Proposer un Trajet".
Inclut origin_city, destination_city, trip_date, vehicle_type, max_packages, max_weight_kg, accepted_package_types, et options comme requires_secure_payment et express_delivery.
Liée à Travelers pour associer les trajets aux livreurs.
Statut (active, completed, canceled) pour gérer la gestion des trajets.



Relations Mises à Jour

1:1 : Entre Users et Travelers, Profiles.
1:N : Entre Users et IdentityDocuments, Transactions, Notifications, Messages, Histories.
N:N : Entre Users (expéditeur/voyageur) et Packages (via expeditor_id et traveler_id).
1:N : Entre Packages et Proposals, Messages, Evaluations, Histories.
1:N : Entre Travelers et Proposals, ProposedTrips.

