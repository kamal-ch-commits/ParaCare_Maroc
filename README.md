# ParaCare Maroc — Boutique et gestion de parapharmacie

**Laravel 12 · PHP · Blade · Bootstrap 5 · MySQL · FR / EN / AR**

Application Laravel pour gerer une parapharmacie avec une boutique en ligne, un espace client et une administration dediee au catalogue, au stock, aux ventes et aux commandes.

## Contexte et contribution

Projet de stage réalisé chez **Next Media Tech**. J'ai développé la majeure partie de l'application individuellement, avec de l'assistance ponctuelle.

L'application relie le parcours client (catalogue, panier, commandes) au travail quotidien de l'administration (produits, stocks, ventes et alertes).

## Parcours de découverte

Après l'installation locale :
1. Ouvrir la boutique et consulter un produit.
2. Ajouter un produit au panier et parcourir la commande avec des données fictives.
3. Se connecter comme administrateur pour explorer le catalogue et le tableau de bord.
4. Enregistrer une entrée de stock, puis une vente, et observer les quantités.
5. Consulter les alertes de stock et changer la langue de l'interface.

## Fonctionnalites

- Authentification administrateur par nom d'utilisateur et mot de passe.
- Boutique publique avec catalogue produits, panier et commande.
- Comptes clients avec suivi des commandes.
- Gestion des categories.
- Gestion des fournisseurs.
- Gestion des produits avec prix, quantite, seuil minimum et date d'expiration.
- Entrees de stock avec mise a jour automatique de la quantite.
- Ventes multi-produits avec calcul automatique du total.
- Blocage des ventes si le stock est insuffisant.
- Dashboard avec statistiques principales.
- Alertes de stock faible, rupture de stock, produits expires et expiration proche.
- Historiques consultables des entrees de stock et des ventes.
- Avis clients sur les produits.
- Interface multilingue FR / EN / AR.

## Stack technique

- Backend: Laravel 12
- Frontend: Blade + Bootstrap 5
- Base de donnees: MySQL
- Authentification: espace admin et espace client

## Installation

1. Installer les dependances PHP:

```bash
composer install
```

2. Copier le fichier d'environnement:

```bash
cp .env.example .env
```

Sur Windows PowerShell:

```powershell
copy .env.example .env
```

3. Generer la cle de l'application:

```bash
php artisan key:generate
```

4. Creer la base de donnees MySQL:

```sql
CREATE DATABASE parapharmacie_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Configurer `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parapharmacie_stock
DB_USERNAME=root
DB_PASSWORD=
```

Adaptez `DB_PASSWORD` selon votre configuration XAMPP ou MySQL.

6. Lancer les migrations et les donnees initiales:

```bash
php artisan migrate --seed
```

7. Demarrer le serveur:

```bash
php artisan serve
```

Puis ouvrir:

```text
http://127.0.0.1:8000
```

## Compte administrateur local

Ces identifiants servent uniquement à la démonstration locale. Les remplacer avant toute mise en ligne.

```text
Nom d'utilisateur: admin
Mot de passe: password
```

## Structure principale

- `app/Models`: modeles Eloquent et relations.
- `app/Http/Controllers`: logique metier et validation.
- `database/migrations`: schema de la base de donnees.
- `database/seeders/DatabaseSeeder.php`: donnees initiales de l'application.
- `resources/views`: interfaces Blade Bootstrap.
- `routes/web.php`: routes web protegees par authentification.

## Regles metier implementees

- Un produit appartient a une categorie.
- Un produit peut etre associe a un fournisseur.
- Une entree de stock augmente automatiquement la quantite du produit.
- Une vente diminue automatiquement la quantite du produit.
- Une vente est refusee si la quantite demandee depasse le stock disponible.
- Les produits avec stock inferieur ou egal au seuil minimum apparaissent dans les alertes.
- Les produits expires ou proches de l'expiration sont visibles dans le dashboard et la page d'alertes.

## Tests

```bash
php artisan test
```

Les tests fonctionnels couvrent les filtres du catalogue, la localisation, les avis produits et les ventes : diminution du stock, total calculé et refus d'une quantité indisponible. Les tests utilisent une base SQLite en mémoire.

## Apercu

ParaCare Maroc permet de piloter le catalogue, les stocks, les ventes et les commandes depuis une interface simple, tout en proposant une boutique en ligne claire et rassurante pour les clients.
