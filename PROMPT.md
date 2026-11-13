# PROMPT — Projet Fil Rouge (Session 3)

Ce fichier contient le **prompt** donné à une IA pour générer ce projet,
comme demandé par le professeur.

---

## Le prompt

> Build a project called **"Projet fil rouge"** — a simple admin dashboard to
> manage **job offers (Offres)** and their **domains (Domaines)**.
> Everything in French, no login required.
>
> ### Architecture (mandatory)
> Use a **3-tier architecture with the DAO pattern**, one PHP file per class:
>
> 1. **Classes (models)** — `backend/classes/`, POO entities with validation
>    - `Domaine` : `id` (int >= 0), `nom` (required)
>    - `Offre` : `id`, `titre` (required), `description` (optional),
>      `salaire` (number >= 0), `domaine_id` (must reference an existing domaine)
>    - Each entity has getters/setters with validation (French error messages),
>      plus `toArray()` and `fromArray()`
> 2. **DAO (data access tier)** — `backend/dao/`, one DAO per entity
>    - The DAO is the ONLY layer that reads/writes the JSON data files
>    - Methods: `AfficherTous()`, `TrouverParId($id)`, `ProchainId()`,
>      `Ajouter()`, `Modifier()`, `Supprimer($id)`
>    - No business logic and no HTTP output here
> 3. **Services (business logic tier)** — `backend/services/`, one service per
>    entity, each extends an abstract `BaseService`
>    - Validates input, enforces business rules, sets HTTP status codes
> 4. **API (one simple file per entity)** — `backend/api/apiDomaine.php` and
>    `backend/api/apiOffre.php`; each file requires its service, sets the JSON
>    content type, then checks the HTTP method with simple `if` statements
>    and calls the matching service method:
>
>    ```php
>    if($_SERVER['REQUEST_METHOD'] == 'GET'){
>        echo json_encode($service->listerDomaines());
>    }
>    if($_SERVER['REQUEST_METHOD'] == 'POST'){
>        echo json_encode($service->ajouterDomaine());
>    }
>    ```
>
> The frontend talks ONLY to the API files — it never touches the JSON files directly.
>
> ### CRUD (mandatory)
> Implement **full CRUD** for both entities:
>
> | Method | URL | Action |
> |--------|-----|--------|
> | GET    | `apiOffre.php`      | list all |
> | GET    | `apiOffre.php?id=2` | get one  |
> | POST   | `apiOffre.php`      | create   |
> | PUT    | `apiOffre.php?id=2` | update   |
> | DELETE | `apiOffre.php?id=2` | delete   |
>
> - Return correct status codes: 200, 201, 400, 404, 409, 422
> - `PUT` for Offre is partial (only the sent fields are updated)
> - A Domaine cannot be deleted while offres are linked to it (409)
> - An Offre must always have a valid `domaine_id` (422)
> - Errors return a JSON message in French
>
> ### Stack
> - PHP 8.1+, no framework, run with `php -S localhost:8000`
> - Vanilla JS + HTML, Tailwind CSS via CDN
> - Database = JSON files: `backend/data/Domaines.json`, `backend/data/Offres.json`
>
> ### File structure
> ```
> index.html
> assets/js/app.js
> backend/api/apiDomaine.php
> backend/api/apiOffre.php
> backend/classes/Domaine.php
> backend/classes/Offre.php
> backend/dao/DomaineDAO.php
> backend/dao/OffreDAO.php
> backend/services/BaseService.php
> backend/services/DomaineService.php
> backend/services/OffreService.php
> backend/data/Domaines.json
> backend/data/Offres.json
> ```
>
> ### Frontend
> - Header "Offres d'emploi", 2 stat cards (total offres / total domaines),
>   2 tabs: Offres / Domaines
> - Offres tab: search bar, filter by domaine, sort (recent, title A→Z, salary),
>   table with edit/delete actions, empty state
> - Domaines tab: cards with initials, number of linked offres, average salary
> - Modals for add/edit, confirmation before delete, toast notifications
> - Seed the data with 6 domaines and 7 offres
>
> Keep the code clean and fully working.

---

## Ce que le prompt impose (résumé)

- ✅ **Architecture 3 tiers** : modèles → DAO → services → API
- ✅ **Patron DAO** : une seule couche accède aux fichiers JSON
- ✅ **API simple** : un fichier par entité, des `if` sur la méthode HTTP
- ✅ **CRUD complet** sur les deux entités avec les codes HTTP corrects
