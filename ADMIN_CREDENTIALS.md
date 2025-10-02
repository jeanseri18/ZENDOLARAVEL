# Informations de Connexion Administrateur

## Compte Administrateur Principal

**Email:** admin@zendo.com  
**Mot de passe:** admin123  
**Rôle:** both (équivalent administrateur)  
**Statut:** Vérifié et actif  

## Utilisateurs de Test Créés

### 1. Jean Dupont (Expéditeur)
- **Email:** jean.dupont@example.com
- **Mot de passe:** password123
- **Rôle:** expeditor
- **Ville:** Lyon, France

### 2. Marie Martin (Expéditrice)
- **Email:** marie.martin@example.com
- **Mot de passe:** password123
- **Rôle:** expeditor
- **Ville:** Marseille, France

### 3. Ahmed Diallo (Voyageur)
- **Email:** ahmed.diallo@example.com
- **Mot de passe:** password123
- **Rôle:** traveler
- **Ville:** Dakar, Sénégal

### 4. Fatou Sow (Voyageuse)
- **Email:** fatou.sow@example.com
- **Mot de passe:** password123
- **Rôle:** traveler
- **Ville:** Abidjan, Côte d'Ivoire

## Comment utiliser

1. Accédez à la page de connexion admin : `http://localhost:8000/admin/login`
2. Utilisez les identifiants du compte administrateur principal
3. Vous pouvez également tester avec les autres comptes utilisateurs

## Commandes utiles

```bash
# Exécuter le seeder
php artisan db:seed --class=AdminUserSeeder

# Réinitialiser et re-seeder la base de données
php artisan migrate:fresh --seed

# Exécuter tous les seeders
php artisan db:seed
```

## Notes importantes

- Tous les comptes sont vérifiés (email et téléphone)
- Le compte administrateur a un score de fiabilité maximum (5.00)
- Les mots de passe sont hashés avec bcrypt
- Les numéros de téléphone sont uniques dans le système