# Prompt — Recreate This Project


Build a project called **"Projet fil rouge"** — a simple Admin Dashboard to manage job offers and domains. No login, everything in French.

### Stack
- PHP 8.1+ (no framework)
- Vanilla JS + HTML (no framework)
- Tailwind CSS via CDN
- Database = JSON files
- Run with `php -S localhost:8000`

### Folder Structure
```
backend/classes.php
backend/api/helpers.php
backend/api/GestionDomaine.php
backend/api/GestionOffre.php
backend/database/domaines.json
backend/database/offres.json
frontend/index.html
frontend/index.js
docs/classDiagram.mmd
docs/usecaseDiagram.mmd
```

### Backend

**1. Models — `backend/classes.php`**
- `Domaine` : `id` (number >=0), `nom` (required text)
- `Offre` : `id`, `titre` (required), `description` (optional), `salaire` (number >=0), `domaine_id` (must link to an existing domaine)
- Both have `toArray()`, `jsonSerialize()`, `fromArray()`. Show errors in French if validation fails.

**2. Helpers — `backend/api/helpers.php`**
- Allow CORS, handle OPTIONS.
- Helper functions to send JSON responses, read request body, read/write JSON files, get next ID.

**3. API — `GestionDomaine.php` and `GestionOffre.php`**
- Each file handles all methods: `GET` (list + `?id=` for one item), `POST` (create), `PUT ?id=` (update), `DELETE ?id=` (delete).
- Domaine cannot be deleted if offres are still linked to it.
- Offre must always have a valid `domaine_id`.
- `PUT` for Offre is partial (update only sent fields).
- Return correct status codes: 200, 201, 400, 404, 409, 422.

**4. Database — `backend/database/`**
- `domaines.json` : array of `{id, nom}` — seed 6 domaines
- `offres.json` : array of `{id, titre, description, salaire, domaine_id}` — seed 7 offres

### Frontend — `frontend/index.html + index.js`

**Design:**
- Header with logo `OF` and title `Offres d'emploi`
- 2 stat cards: Total offres + Total domaines
- 2 tabs: Offres / Domaines

**Offres tab:**
- Search bar + filter by domaine + sort (recent, title A-Z, salary)
- Table with columns: Offre, Domaine, Salaire (in DH), Actions (edit/delete)
- Empty state if no results

**Domaines tab:**
- Search bar + grid of cards (show initials, how many offres linked, average salary)

**Modals & UX:**
- Modal to add/edit offre (titre, description, salaire, domaine)
- Modal to add/edit domaine (nom)
- Confirm modal before delete
- Toast notifications (success/error)
- Client-side search, filter, and sort. Protect against XSS.

### Docs
- `classDiagram.mmd` : class diagram for Domaine, Offre, APIs, Helpers, Frontend
- `usecaseDiagram.mmd` : use `usecase-beta` with actor `Admin`, 3 system boundaries (Gestion des Offres UC1-UC6, Gestion des Domaines UC7-UC10, Dashboard UC11)

Keep it clean, responsive, and fully working.

---

