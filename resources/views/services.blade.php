@extends('layouts.app')

@section('title', 'Nos services - ZENDO')

@section('content')

<div class="container mt-5">
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Un service 
de livraison pensé 
pour tous</h1>
            <p class="lead mb-4">
Avec Zendo, nous facilitons la livraison en connectant 
expéditeurs et coursiers. Notre objectif : offrir des envois rapides.        </p>
            
            <div class="app-download-icons mt-4">
                <img src="{{ asset('publicpic/image 1.png') }}" alt="Download on App Store" class="me-3" style="height: 60px;">
                <img src="{{ asset('publicpic/image 2.png') }}" alt="Get it on Google Play" style="height: 60px;">
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/livreuraccueil.png') }}" alt="Livreur ZENDO" class="img-fluid" style="max-height: 700px;">
        </div>
    </div>

    <center>   <h2 class="display-5 fw-bold mb-4">Nos services</h2></center>
    
    <!-- Services Section -->
    <div class="row mt-5">
        <!-- Service Expéditeurs -->
        <div class="col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title fw-bold mb-3">Expéditeurs</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted">Avec Zendo, expédier un colis devient un jeu d'enfant. Vous pouvez envoyer vos paquets aussi bien dans votre ville qu'à l'international, en profitant d'un suivi en temps réel et de tarifs totalement transparents.</p>
                </div>
            </div>
        </div>
        
        <!-- Service Coursiers -->
        <div class="col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17,11 19,13 23,9"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title fw-bold mb-3">Coursiers</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted">Être coursier Zendo, c'est garder votre indépendance tout en générant des revenus flexibles. Vous choisissez vos missions, travaillez à votre rythme et profitez de paiements sécurisés directement via l'application.</p>
                </div>
            </div>
        </div>
        
        <!-- Service Couverture étendue -->
        <div class="col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title fw-bold mb-3">Couverture étendue</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted">Zendo vous connecte partout : que vous soyez en Côte d'Ivoire, ailleurs en Afrique ou dans le monde, notre service assure la livraison de vos colis sans frontières et avec fiabilité.</p>
                </div>
            </div>
        </div>
        
        <!-- Service Sécurité garantie -->
        <div class="col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <circle cx="12" cy="16" r="1"></circle>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title fw-bold mb-3">Sécurité garantie</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted">Votre tranquillité d'esprit est notre priorité. Tous les expéditeurs et coursiers sont vérifiés, vos paiements sont sécurisés et notre plateforme intuitive vous accompagne à chaque étape.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection