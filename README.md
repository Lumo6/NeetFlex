# Gestion d'Événements Musicaux

## Description du projet

Le projet consiste en une application Symfony permettant de gérer des événements musicaux. L'application est composée d'une partie **fullstack** (front-end + back-end) et d'une partie **API**. Elle permet aux utilisateurs de consulter, créer, modifier et s'inscrire à des événements musicaux, ainsi que de gérer des artistes et des événements.

## Technologies utilisées

- **Symfony** (back-end)
- **Doctrine ORM** (gestion de la base de données)
- **Swagger UI** (documentation API)
- **SQLite** (base de données)

## Fonctionnalités

### Partie Fullstack

L'application permet de :

- **Page d'accueil publique** : accessible sans connexion.
- **Page d'administration** : accessible uniquement par un utilisateur ayant le rôle `ROLE_ADMIN`.
- **Gestion des artistes** : ajout, modification, suppression d'artistes par l'admin.
- **Gestion des événements** :
  - Création, modification, suppression d'événements par l'utilisateur ayant créé l'événement.
  - Inscription et désinscription à un événement.
  - Visualisation des événements auxquels un utilisateur est inscrit.
- **Navigation** :
  - La navigation s'adapte à l'utilisateur connecté (admin ou user).
  - L'admin peut voir la liste des utilisateurs et gérer les artistes.
  - Les utilisateurs peuvent voir la liste des artistes et des événements.
  - Recherche et filtrage par nom d'artiste et date d'événement.

### Partie API

L'API permet de consulter :

- **Liste des artistes** : accessible publiquement.
- **Détails d'un artiste en particulier**.
- **Liste des événements** : accessible publiquement.
- **Détails d'un événement**.
- **Documentation Swagger UI** : accessible via la route `/api/doc`.

### Routes de l'application

- **Page d'accueil** : `/`
- **Page des artistes** : `/artists`
- **Page d'un artiste** : `/artists/{id}`
- **Page de création d'un artiste** : `/artists/create` (accessible uniquement aux admins)
- **Page de modification d'un artiste** : `/artists/{id}/edit` (accessible uniquement aux admins)
- **Page des événements** : `/events`
- **Page d'un événement** : `/events/{id}`
- **Page de création d'événement** : `/events/create`
- **Page de modification d'événement** : `/events/{id}/edit`
- **Page de connexion** : `/login`
- **Page de déconnexion** : `/logout`
- **Page d'inscription** : `/register`
- **Page de gestion des utilisateurs** : `/users` (accessible uniquement aux admins)

### Routes API

- **Liste des artistes** : `/api/artists`
- **Détails d'un artiste** : `/api/artists/{id}`
- **Liste des événements** : `/api/events`
- **Détails d'un événement** : `/api/events/{id}`
- **Documentation API** : `/api/doc`

## Gestion d'erreur

L'application utilise des **pages personnalisées** pour les erreurs (403, 404, 500...).

## Installation

### Prérequis

- PHP >= 8.2
- Composer
- Symfony
- Symfony CLI
- SQLite

### Étapes d'installation

1. **Cloner le repository :**

```bash
git clone https://github.com/Lumo6/NeetFlex.git
cd NeetFlex
```

2. **ACTIVER l'EXTENSION FILEINFO DANS VOTRE PHP.INI**

Le php.ini est accessible via :

```bash
php --ini
```

3. **Installer les dépendances :**

```bash
composer install
```

4. **Exécuter les migrations :**

```bash
php bin/console doctrine:migrations:migrate
```

5. **Lancer le serveur Symfony :**

```bash
symfony server:start --port=44444
```

Accède à l'application en visitant `http://127.0.0.1:44444` dans ton navigateur.

### Installation du Front-end (React)

1. **Aller dans le dossier `react`**

```bash
cd react
```

ou depuis le dossier symfony :

```bash
cd ../react
```

2. **Installer les dépendances**

```bash
npm install
```

3. **Lancer l'application React :**

```bash
npm run dev
```

Accède à l'application en visitant `http://127.0.0.1:5173` dans ton navigateur.
