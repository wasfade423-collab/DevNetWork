# DevNetwork

DevNetwork est une petite application PHP qui réunit les profils de développeurs d’une équipe. Elle permet de consulter l’annuaire, rechercher des profils par nom, spécialité ou technologie, et gérer les profils enregistrés dans MongoDB.

## Fonctionnalités

- Annuaire responsive avec recherche instantanée et statistiques.
- Fiche détaillée pour chaque développeur.
- Création, modification et suppression de profils.
- Gestion de spécialités et de technologies personnalisées.
- Validation des formulaires et protection CSRF des opérations d’écriture.
- Prise en charge en lecture des anciens documents MongoDB utilisant les champs anglais (`firstname`, `lastname`, `xp` et `level`).

## Prérequis

- PHP 8.2 ou plus récent.
- L’extension PHP MongoDB (`mongodb`) activée.
- Composer.
- MongoDB en fonctionnement et accessible à l’application.

## Installation

1. Cloner ou copier le projet dans le répertoire de votre serveur web.
2. Installer les dépendances PHP :

   ```bash
   composer install
   ```

3. Démarrer MongoDB. Par défaut, l’application s’y connecte à l’adresse `mongodb://localhost:27017`.
4. Ouvrir l’application dans un navigateur :

   - Avec XAMPP, placer le projet dans `htdocs`, démarrer Apache et ouvrir `http://localhost/phpWithMongoDB/`.
   - Ou, pour un lancement local de développement depuis la racine du projet :

     ```bash
     php -S 127.0.0.1:8000
     ```

     Puis ouvrir `http://127.0.0.1:8000`.

MongoDB crée la base et la collection au premier enregistrement si elles n’existent pas encore. L’application utilise actuellement la base `baseFormation1` et la collection `devs`.

## Configuration MongoDB

La connexion est définie dans [`db.php`](./db.php). Pour utiliser un autre serveur, une authentification ou une autre base/collection, adaptez l’URI et les noms transmis à `selectCollection()` dans ce fichier.

## Utilisation

Depuis l’annuaire, il est possible d’ouvrir une fiche, de modifier un profil ou d’en ajouter un. La suppression est disponible sur la fiche détaillée et nécessite une confirmation.

Un profil contient les champs suivants :

| Champ | Description |
| --- | --- |
| `prenom` | Prénom du développeur |
| `nom` | Nom du développeur |
| `type` | Spécialité, par exemple `Frontend`, `Backend` ou `FullStack` |
| `anneeDexperience` | Nombre entier d’années d’expérience, égal ou supérieur à zéro |
| `niveau` | Niveau (`Junior`, `Medium`, `Senior` ou `Lead`) |
| `pays` | Pays, facultatif |
| `technos` | Liste des technologies maîtrisées |

Les nouveaux profils sont enregistrés avec ces noms de champs. Les documents plus anciens sont normalisés à l’affichage pour préserver la compatibilité.

## Structure du projet

| Fichier ou dossier | Rôle |
| --- | --- |
| `index.php` | Point d’entrée, session et jeton CSRF |
| `control.php` | Routage et traitement des actions |
| `model.php` | Accès aux profils MongoDB |
| `db.php` | Connexion MongoDB |
| `home.php` | Annuaire et recherche |
| `add.php` | Formulaire de création et de modification |
| `showDev.php` | Fiche détaillée d’un développeur |
| `error.php` | Page d’erreur |
| `partials/` | En-tête et pied de page partagés |
| `assets/` | Feuille de style et interactions JavaScript |

## Vérifications

Le projet ne contient pas actuellement de suite de tests automatisés. Après une modification, vérifiez la syntaxe PHP et JavaScript :

```bash
find . -path './vendor' -prune -o -name '*.php' -type f -print0 | xargs -0 -n1 php -l
node --check assets/app.js
```
