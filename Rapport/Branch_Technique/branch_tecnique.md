# Branche technique
Dans cette section, nous présentons la branche technique du projet, qui consiste à décrire les choix technologiques, les outils et les architectures adoptés pour assurer le bon fonctionnement de l'application, tout en garantissant une solution performante, maintenable et adaptée aux besoins identifiés.
---
## Choix technologiques
Dans le cadre de ce projet, les technologies ont été choisies pour respecter une contrainte forte : **aucun framework, aucune base de données**. Le tableau de bord devait rester simple à lancer, tout en appliquant une architecture 3 tiers et le patron DAO.
### Technologies Backend
- **PHP 8.1+** : langage du serveur, utilisé en programmation orientée objet (une classe par fichier). Le projet se lance avec le serveur intégré `php -S localhost:8000`.
- **API HTTP/JSON native** : deux points d'entrée, `backend/api/apiDomaine.php` et `backend/api/apiOffre.php`, qui aiguillent la requête selon `REQUEST_METHOD` et renvoient systématiquement du JSON.
- **Services** : `DomaineService` et `OffreService`, héritant de `BaseService`, appliquent la validation et les règles métier, et positionnent les codes HTTP (200, 201, 400, 404, 409, 422).
- **DAO** : `DomaineDAO` et `OffreDAO` sont les seules classes à lire et écrire les fichiers JSON ; elles exposent `AfficherTous()`, `TrouverParId()`, `ProchainId()`, `Ajouter()`, `Modifier()` et `Supprimer()`.
### Technologies Frontend
- **HTML5** : structure de la page `index.html`, avec deux onglets (Offres et Domaines) et des modales.
- **Tailwind CSS** : chargé via CDN, pour une interface responsive sans étape de compilation.
- **JavaScript natif (Fetch API)** : `assets/js/app.js` centralise les appels HTTP dans une fonction `api()`, un état `state` et des fonctions `render()`, `reload()` et `toast()`. La recherche, le filtre par domaine et le tri sont effectués côté client.
### Stockage des données
- **Fichiers JSON** : `backend/data/Domaines.json` et `backend/data/Offres.json`, lus et écrits par les DAO, sans SGBD ni ORM. Les données sont encodées avec `JSON_PRETTY_PRINT` et `JSON_UNESCAPED_UNICODE`.
### Outils et utilitaires externes
- **Mermaid** : génération des diagrammes de cas d'utilisation et de classes à partir d'une syntaxe textuelle.
- **Git** : versionnement du code et des documents.
