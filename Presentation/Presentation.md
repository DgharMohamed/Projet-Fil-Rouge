---
marp: true
theme: default
size: 16:9
paginate: true
lang: fr-FR
title: "Projet Fil Rouge — Offres d'emploi"
description: "Rapport de projet — Session 3 — Architecture 3 tiers & patron DAO"
style: |
  section {
    font-family: "Aptos", "Segoe UI", sans-serif;
    background: #f8fafc;
    color: #172033;
    padding: 48px 64px;
  }
  h1, h2 {
    color: #243b6b;
  }
  h1 {
    font-size: 2.4em;
  }
  h2 {
    font-size: 1.8em;
    margin-bottom: 0.7em;
  }
  ul {
    font-size: 1.1em;
    line-height: 1.55;
  }
  section.image-only {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 24px;
  }
  section.image-only img {
    max-width: 96%;
    max-height: 66vh;
    width: auto;
    height: auto;
    object-fit: contain;
  }
  section.split-left {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;
    grid-template-rows: auto 1fr;
    column-gap: 28px;
    align-items: center;
  }
  section.split-left h2 {
    grid-column: 1 / -1;
    align-self: start;
    margin-bottom: 0.35em;
  }
  section.split-left img {
    max-width: 100%;
    max-height: 76vh;
    width: auto;
    height: auto;
    object-fit: contain;
    justify-self: center;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 18px rgba(23, 32, 51, 0.12);
  }
  section.split-left ul {
    font-size: 0.8em;
    line-height: 1.5;
    margin: 0;
    padding-left: 1.1em;
  }
  section table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9em;
    margin-top: 0.3em;
  }
  section th, section td {
    border: 1px solid #d7deea;
    padding: 8px 12px;
    text-align: left;
    vertical-align: top;
  }
  section th {
    background: #eef2f8;
    color: #243b6b;
  }
  section.compact ul {
    font-size: 1em;
    line-height: 1.55;
  }
  section.statement blockquote {
    margin: 0.2em 0 0.7em;
    padding: 20px 26px;
    background: #eef2f8;
    border-left: 6px solid #4f46e5;
    border-radius: 8px;
    font-size: 1.15em;
    line-height: 1.5;
    font-weight: 600;
  }
  section.wide-image {
    display: flex;
    flex-direction: column;
  }
  section.wide-image img {
    width: 100%;
    max-height: 50vh;
    object-fit: contain;
  }
  section.wide-image ul {
    font-size: 1em;
    line-height: 1.5;
  }
  section.gallery table {
    border: none;
  }
  section.gallery td {
    border: none;
    text-align: center;
    vertical-align: middle;
    padding: 4px 10px;
  }
  section.gallery img {
    max-width: 100%;
    max-height: 52vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(23, 32, 51, 0.14);
  }
  section.gallery.three img {
    max-height: 44vh;
  }
  section.gallery td.caption {
    color: #475569;
    font-size: 0.95em;
  }
  section.title-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    padding: 34px 70px 40px;
    background: linear-gradient(160deg, #f8fafc 0%, #eef2ff 100%);
  }
  section.title-slide .kicker {
    margin: 0 0 10px;
    color: #4f46e5;
    font-size: 0.95em;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
  }
  section.title-slide h1 {
    margin: 0;
    font-size: 3.6em;
    line-height: 1.05;
    letter-spacing: -1px;
  }
  section.title-slide .subtitle {
    margin: 14px 0 0;
    font-size: 1.5em;
    color: #475569;
    font-weight: 500;
  }
  section.title-slide .rule {
    width: 96px;
    height: 5px;
    border-radius: 999px;
    background: #4f46e5;
    margin: 22px auto 0;
  }
  section.title-slide .meta {
    display: flex;
    gap: 64px;
    align-items: flex-start;
    justify-content: center;
    padding: 18px 40px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(36, 59, 107, 0.08);
  }
  section.title-slide .meta p {
    margin: 0;
    font-size: 1.05em;
    line-height: 1.5;
  }
  section.title-slide .meta .meta-label {
    display: block;
    font-size: 0.72em;
    font-weight: 700;
    letter-spacing: 1.6px;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 2px;
  }
  section.title-slide .meta strong {
    color: #172033;
    font-size: 1.15em;
  }
  section.sprints .lead {
    margin: 0 0 18px;
    font-size: 1.05em;
    color: #475569;
  }
  section.sprints .sprint-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
    align-items: stretch;
  }
  section.sprints .sprint-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-top: 5px solid #4f46e5;
    border-radius: 12px;
    padding: 20px 22px;
    box-shadow: 0 6px 18px rgba(36, 59, 107, 0.07);
  }
  section.sprints .sprint-num {
    display: inline-block;
    margin-bottom: 6px;
    font-size: 0.78em;
    font-weight: 700;
    letter-spacing: 1.6px;
    text-transform: uppercase;
    color: #4f46e5;
  }
  section.sprints h3 {
    margin: 0 0 10px;
    font-size: 1.2em;
    color: #243b6b;
  }
  section.sprints ul {
    margin: 0;
    padding-left: 1.1em;
    font-size: 0.95em;
    line-height: 1.55;
    color: #334155;
  }
---

<!-- _class: title-slide -->
<!-- _paginate: false -->

<div class="title-block">
<p class="kicker">Projet fil rouge · Développement Web</p>
<h1>Projet Fil Rouge</h1>
<p class="subtitle">Tableau de bord de gestion des offres d'emploi</p>
<div class="rule"></div>
</div>

<div class="meta">
<p>
<span class="meta-label">Session</span>
<strong>Session 3</strong>
</p>
<p>
<span class="meta-label">Architecture</span>
<strong>3 tiers &amp; patron DAO</strong>
</p>
</div>

---

## Sommaire

1. Contexte et objectifs du projet
2. Méthodologie : Scrum, 2TUP et Design Thinking
3. Planification et branche fonctionnelle
4. Conception technique : architecture, données et classes
5. Réalisation : maquettes
6. Bilan et perspectives

---

## Introduction

**Projet Fil Rouge — Offres d'emploi** est un tableau de bord d'administration qui centralise les offres d'emploi et leurs domaines. Exercice de **Session 3**, il applique une **architecture 3 tiers** et le **patron DAO**, sans framework et sans base de données, avec des fichiers **JSON**. L'application est fonctionnelle et testée sur <http://localhost:8000>.

---

## Contexte du projet

- Un point unique pour **consulter et administrer les offres**, classées par domaine.
- Le frontend parle **uniquement à l'API** : une seule couche (le DAO) lit et écrit les données.
- Pas de base de données → **fichiers JSON** ; pas de framework → **PHP 8.1+ et JavaScript natif**.
- Interface en **français**, sans authentification.
- **Règles de gestion** · une offre référence toujours un domaine existant ; un domaine rattaché à des offres est protégé.

---

<!-- _class: image-only -->
## Scrum

*Support à compléter : visuel de la méthode Scrum.*

---

<!-- _class: image-only -->
## Processus 2TUP

*Support à compléter : visuel du processus 2TUP.*

---

<!-- _class: image-only -->
## Design Thinking

*Support à compléter : visuel du Design Thinking.*

---

<!-- _class: compact -->
## Planification

*Planning des sprints à compléter.*

---

## Branche fonctionnelle

| Acteur | Besoins | Fonctions principales |
|---|---|---|
| **Administrateur** | Gérer les offres et les domaines depuis un point unique | Consulter, créer, modifier et supprimer les offres et les domaines |
| **Administrateur** | Suivre l'activité et retrouver une offre rapidement | Recherche, filtre par domaine, tri et statistiques |
| **Administrateur** | Garantir la cohérence du référentiel | Suppression d'un domaine protégée tant qu'il est rattaché à des offres |

**Périmètre livré** · CRUD complet des offres et des domaines, recherche, filtre, tri, statistiques et salaire moyen par domaine.

---

<!-- _class: compact -->
## Carte d'empathie

*Carte d'empathie à compléter.*

---

<!-- _class: statement -->
## Définition du problème

> Comment centraliser la gestion des offres d'emploi et de leurs domaines, avec une architecture 3 tiers et un accès aux données réservé au DAO ?

- **Besoin** · Un point unique pour consulter et administrer les offres, classées par domaine.
- **Contraintes** · Pas de base de données (JSON), pas de framework (PHP et JavaScript natifs), interface en français sans authentification.
- **Règles** · Une offre référence **toujours** un domaine existant ; un domaine rattaché à des offres est **protégé**.

---

<!-- _class: compact -->
## Idéation

*Idéation à compléter.*

---

<!-- _class: wide-image -->
## Diagramme de cas d'utilisation — Offres

![Diagramme de cas d'utilisation — Gestion des Offres](images/usecase_offres.png)

- **Acteur unique : l'Administrateur** — **UC1** Consulter · **UC2** Créer · **UC3** Modifier · **UC4** Supprimer.

---

<!-- _class: wide-image -->
## Diagramme de cas d'utilisation — Domaines & Dashboard

![Diagramme de cas d'utilisation — Gestion des Domaines et Dashboard](images/usecase_domaines_dashboard.png)

- **UC5 à UC8** pour les domaines, **UC9** pour les statistiques — suppression d'un domaine lié bloquée (**409**).

---

## Stack technologique

- **Serveur** · PHP 8.1+ (sans framework)
- **Client** · JavaScript natif + HTML
- **Styles** · Tailwind CSS via CDN
- **Persistance** · JSON (`Domaines.json`, `Offres.json`)
- **Serveur local** · `php -S localhost:8000`
- **Diagrammes** · Mermaid → PNG (`Presentation/images/`)

---

## Prototype — Offres et Domaines

- **Modèles** · `Domaine`, `Offre` — getters/setters validés, `toArray()` / `fromArray()`
- **DAO** · `AfficherTous()`, `TrouverParId()`, `ProchainId()`, `Ajouter()`, `Modifier()`, `Supprimer()`
- **Services** · `lister…()`, `ajouter…()`, `modifier…()`, `supprimer…()`
- **API** · un fichier par entité, aiguillage par `REQUEST_METHOD`
- **Front** · `api()`, `reload()`, `render()`, `toast()` — `state` centralisé

---

## Relations des données

- **Domaine** → regroupe plusieurs offres ; il est **protégé** tant qu'il est référencé.
- **Offre** → rattachée à un domaine existant (clé étrangère `domaine_id`), sinon **422**.
- **Suppression** → un domaine lié à des offres ne peut pas être supprimé (**409**).

---

## Structure technique

- **Frontend** · `Projet/index.html` + `Projet/assets/js/app.js`
- **API** · `Projet/backend/api/` — `apiDomaine.php`, `apiOffre.php`
- **Services** · `Projet/backend/services/` — `BaseService`, `DomaineService`, `OffreService`
- **DAO** · `Projet/backend/dao/` — `DomaineDAO`, `OffreDAO`
- **Modèles** · `Projet/backend/classes/` — `Domaine`, `Offre`
- **Données** · `Projet/backend/data/` — `Domaines.json`, `Offres.json`

---

<!-- _class: split-left -->
## Diagramme de classes — Présentation & API

![Diagramme de classes — couches Présentation et API](images/ClassDiagram_presentation_api.png)

- **Présentation** · `Projet/index.html` + `app.js`
- **API** · un contrôleur par entité
- **Transport** · HTTP / JSON et codes 200 / 201 / 400 / 404 / 409 / 422

---

<!-- _class: wide-image -->
## Diagramme de classes — Services

![Diagramme de classes — couche Services](images/ClassDiagram_services.png)

- `DomaineService` et `OffreService` étendent `BaseService` : validation et **codes HTTP**.

---

<!-- _class: wide-image -->
## Diagramme de classes — DAO

![Diagramme de classes — couche DAO](images/ClassDiagram_dao.png)

- Seule couche à lire/écrire les JSON ; `CompterParDomaine()` déclenche le **409**.

---

<!-- _class: split-left -->
## Diagramme de classes — Modèles

![Diagramme de classes — modèles Offre et Domaine](images/ClassDiagram_modeles.png)

- **Domaine** · identifiant, libellé
- **Offre** · titre, description, domaine, salaire
- **Relation** · `Offre` **n** ──▶ **1** `Domaine`

---

<!-- _class: gallery -->
## Maquettes

| Onglet Offres | Onglet Domaines |
|---|---|
| ![Interface — onglet Offres](images/ui_offres.png) | ![Interface — onglet Domaines](images/ui_domaines.png) |
| Liste des offres · recherche, filtre par domaine et tri | Référentiel des domaines · offres liées |

---

<!-- _class: split-left -->
## Interfaces

![Modale d'ajout d'une offre](images/ui_modale.png)

- Formulaires **modales** avec messages d'erreur **422**
- Confirmation avant suppression, **toasts** de retour
- États vides dédiés

---

<!-- _class: compact -->
## Réalisation — Back-end

| Couche | Réalisation |
|--------|-------------|
| **API** | `apiDomaine.php`, `apiOffre.php` — JSON + aiguillage HTTP |
| **Services** | Validation, règles métier, codes 200/201/400/404/409/422 |
| **DAO** | `Charger()` / `Enregistrer()`, `ProchainId()`, `CompterParDomaine()` |
| **Modèles** | `Domaine`, `Offre` — validation dans les setters |
| **Données** | 7 domaines et 7 offres au format JSON |

---

## Conclusion

- **Livré** · CRUD complet des offres et des domaines, recherche, tri, statistiques et salaire moyen par domaine.
- **Architecture maîtrisée** · Architecture 3 tiers et patron DAO effectivement respectés, API HTTP/JSON avec codes de statut corrects et messages en français.
- **À améliorer** · `BaseService` est vide : factoriser le code commun ; chemins relatifs dans les DAO → dépendre de la racine du projet.

**Merci de votre attention.**
