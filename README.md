# StockFlow — Application de Gestion de Stock

Application web développée avec Laravel 10+ pour la gestion de stock des PME marocaines.

## Prérequis

- PHP 8.1+
- MySQL 8.0+
- Composer
- XAMPP (ou équivalent)

## Installation

### 1. Cloner le projet
```
git clone https://github.com/votre-repo/stockflow.git
cd stockflow
```

### 2. Installer les dépendances
```
composer install
```

### 3. Configurer l'environnement
```
cp .env.example .env
php artisan key:generate
```

### 4. Configurer la base de données
Ouvrir `.env` et modifier :
```
DB_DATABASE=gestion_stock
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Créer la base de données
- Ouvrir phpMyAdmin
- Créer une base de données nommée `gestion_stock`
- Interclassement : `utf8mb4_unicode_ci`

### 6. Exécuter les migrations et seeders
```
php artisan migrate:fresh --seed
```

### 7. Créer le lien de stockage
```
php artisan storage:link
```

### 8. Lancer le serveur
```
php artisan serve
```

### 9. Accéder à l'application
Ouvrir le navigateur sur : http://127.0.0.1:8000

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@stock.ma | StockFlow@2026 |
| Gestionnaire | gestionnaire@stock.ma | StockFlow@2026 |
| Vendeur | vendeur@stock.ma | StockFlow@2026 |
| Manager | manager@stock.ma | StockFlow@2026 |

## Modules disponibles

- ✅ Authentification et gestion des rôles
- ✅ Gestion des produits (CRUD + upload image)
- ✅ Gestion des catégories et marques
- ✅ Gestion des fournisseurs et clients
- ✅ Approvisionnements avec traçabilité stock
- ✅ Ventes / Interface Caisse
- ✅ Génération factures PDF
- ✅ Gestion des commandes
- ✅ Bons de livraison PDF
- ✅ Tableau de bord avec KPIs et graphiques
- ✅ Alertes stock automatiques
- ✅ Historique mouvements de stock
- ✅ Top clients et clients inactifs

## Technologies utilisées

- **Backend** : Laravel 10+, PHP 8.1+
- **Base de données** : MySQL 8.0
- **Frontend** : Blade, Bootstrap 5.3, Chart.js
- **PDF** : DomPDF (barryvdh/laravel-dompdf)
- **Authentification** : Laravel Breeze

## Structure du projet
```
app/
├── Http/
│   ├── Controllers/     # Contrôleurs
│   └── Middleware/      # Middleware rôles
├── Models/              # Modèles Eloquent
database/
├── migrations/          # Migrations (14 tables)
└── seeders/             # Données de démonstration
resources/
└── views/               # Vues Blade
routes/
└── web.php              # Routes
```

## Auteur

Développé par **[OUSSAMA MOUSTARIH]** — PFE TSDI ETEC 2026