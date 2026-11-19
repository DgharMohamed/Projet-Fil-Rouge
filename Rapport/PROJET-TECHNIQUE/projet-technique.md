# Projet technique (Prototype CRUD)
Ce projet technique est un tableau de bord d'administration permettant de gérer des **offres d'emploi** et les **domaines** auxquels elles sont rattachées. Il sert de prototype complet pour la mise en œuvre d'une architecture 3 tiers et du patron DAO, sans framework et sans base de données relationnelle.
La plateforme permet à un administrateur de gérer les deux référentiels depuis un point unique, avec des formulaires modaux, une recherche, un filtre par domaine et des statistiques.
## Fonctionnalités Clés
- **Gestion des domaines :** CRUD complet (création, consultation, modification et suppression) sur un référentiel unique.
- **Gestion des offres :** CRUD complet avec titre, description, salaire et domaine associé.
- **Recherche et tri :** recherche texte, filtre par domaine et tri côté client.
- **Statistiques :** total des offres, total des domaines et salaire moyen par domaine.
- **Règles métier :** une offre doit référencer un domaine existant (**422**) ; un domaine utilisé par des offres ne peut pas être supprimé (**409**).
## Classes principales du modèle
- **Domaine :** identifiant entier positif et nom obligatoire ; validation dans les setters, `toArray()` et `fromArray()`.
- **Offre :** identifiant, titre obligatoire, description, salaire numérique positif et `domaine_id` obligatoire.
- **DomaineDAO :** accès au fichier `Domaines.json` ; `Charger()` et `Enregistrer()` privés.
- **OffreDAO :** accès au fichier `Offres.json`, avec en plus `CompterParDomaine()`.
- **DomaineService / OffreService :** validation et règles métier, codes HTTP.
- **BaseService :** classe abstraite, encore vide, destinée à factoriser le code commun.
## Relations principales
- **Domaine ↔ Offre :** relation One-to-Many (un domaine peut regrouper plusieurs offres).
- **Offre → Domaine :** relation Many-to-One matérialisée par la clé étrangère `domaine_id`.
- **Suppression protégée :** `OffreDAO::CompterParDomaine()` vérifie l'usage d'un domaine avant sa suppression ; le service renvoie alors le code **409**.
