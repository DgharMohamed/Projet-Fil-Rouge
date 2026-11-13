# Projet Fil Rouge — Offres d'emploi

Tableau de bord d'administration pour gérer des **offres d'emploi** et leurs **domaines**.
Réalisé en **Session 3** avec une **architecture 3 tiers** et le patron de conception **DAO**.

## Fonctionnalités

- **Domaines** : CRUD complet (lister, afficher, créer, modifier, supprimer)
- **Offres** : CRUD complet avec titre, description, salaire et domaine associé
- Un domaine lié à des offres ne peut pas être supprimé (code 409)
- Une offre doit toujours pointer vers un domaine existant (code 422)
- Recherche, filtre par domaine et tri côté client
- Modals d'ajout/édition, confirmation avant suppression, notifications (toasts)
- Statistiques : total offres, total domaines, salaire moyen par domaine

## Stack technique

- PHP 8.1+ (sans framework) — lancé avec `php -S localhost:8000`
- JavaScript vanilla + HTML (sans framework)
- Tailwind CSS via CDN
- Base de données : fichiers JSON (aucun SGBD)

## Architecture 3 tiers (avec DAO)

```
┌──────────────────────────────────────────────┐
│ Présentation : index.html + assets/js/app.js │
├──────────────────────────────────────────────┤
│ Contrôleurs : apiDomaine.php · apiOffre.php  │
├──────────────────────────────────────────────┤
│ Logique métier : backend/services/           │
│   DomaineService · OffreService              │
├──────────────────────────────────────────────┤
│ Accès aux données (DAO) : backend/dao/       │
│   DomaineDAO · OffreDAO  →  data/*.json      │
├──────────────────────────────────────────────┤
│ Modèles (POO) : backend/classes/             │
│   Domaine · Offre                            │
└──────────────────────────────────────────────┘
```

- Le **frontend** ne parle qu'aux fichiers API, jamais aux JSON directement.
- Le **DAO** est le seul à lire/écrire les fichiers JSON.
- Le **service** valide les données et applique les règles métier.

## Structure du projet

```
index.html                  → interface (présentation)
assets/js/app.js            → logique du frontend
backend/
├── api/                    → un fichier simple par entité
│   ├── apiDomaine.php      → CRUD du Domaine
│   └── apiOffre.php        → CRUD de l'Offre
├── classes/                → modèles POO (1 classe = 1 fichier)
│   ├── Domaine.php
│   └── Offre.php
├── dao/                    → accès aux données (fichiers JSON)
│   ├── DomaineDAO.php
│   └── OffreDAO.php
├── services/               → logique métier
│   ├── BaseService.php
│   ├── DomaineService.php
│   └── OffreService.php
└── data/
    ├── Domaines.json
    └── Offres.json
docs/                       → diagrammes de classes et cas d'utilisation
PROMPT.md                   → prompt IA utilisé pour générer ce projet
```

## API — CRUD

Un fichier simple par entité : `backend/api/apiDomaine.php` et `backend/api/apiOffre.php`.
Chaque fichier vérifie la méthode HTTP (`GET`, `POST`, `PUT`, `DELETE`) et appelle la méthode du service correspondante.

**Domaines :**

| Méthode | URL | Action | Code |
|---------|-----|--------|------|
| GET     | `apiDomaine.php`        | Lister tous les domaines | 200 |
| GET     | `apiDomaine.php?id=1`   | Afficher un domaine      | 200 / 404 |
| POST    | `apiDomaine.php`        | Créer un domaine         | 201 |
| PUT     | `apiDomaine.php?id=1`   | Modifier un domaine      | 200 / 422 |
| DELETE  | `apiDomaine.php?id=1`   | Supprimer un domaine     | 200 / 409 |

**Offres :** mêmes routes sur `apiOffre.php` (le `PUT` des offres est **partiel** : seuls les champs envoyés sont modifiés).

## Entités et validation

- **Domaine** : `id` (entier ≥ 0), `nom` (obligatoire)
- **Offre** : `id`, `titre` (obligatoire), `description` (optionnelle), `salaire` (nombre ≥ 0), `domaine_id` (doit exister)
- Les erreurs de validation renvoient un message en français (code 422)

## Lancement

```bash
php -S localhost:8000
```

Puis ouvrir <http://localhost:8000> dans le navigateur.
