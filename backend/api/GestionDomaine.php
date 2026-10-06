<?php

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../classes.php";

$file = __DIR__ . "/../database/domaines.json";
$offresFile = __DIR__ . "/../database/offres.json";

$domaines = loadDb($file);
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $id = queryId();

        if ($id === null) {
            respond(array_map(fn (array $row) => Domaine::fromArray($row), $domaines));
        }

        $row = findRow($domaines, $id);

        if ($row === null) {
            fail("Domaine introuvable", 404);
        }

        respond(Domaine::fromArray($row));
    }

    if ($method === "POST") {
        $domaine = new Domaine(nextId($domaines), strField(body(), "nom"));

        $domaines[] = $domaine;
        saveDb($file, $domaines);

        respond($domaine, 201);
    }

    if ($method === "PUT") {
        $id = queryId();

        if ($id === null) {
            fail("Paramètre id manquant", 400);
        }

        $index = array_search($id, array_column($domaines, "id"), true);

        if ($index === false) {
            fail("Domaine introuvable", 404);
        }

        $domaine = new Domaine($id, strField(body(), "nom"));
        $domaines[$index] = $domaine;
        saveDb($file, $domaines);

        respond($domaine);
    }

    if ($method === "DELETE") {
        $id = queryId();

        if ($id === null) {
            fail("Paramètre id manquant", 400);
        }

        $index = array_search($id, array_column($domaines, "id"), true);

        if ($index === false) {
            fail("Domaine introuvable", 404);
        }

        $offres = loadDb($offresFile);
        $used = count(array_filter($offres, fn (array $o) => (int) ($o["domaine_id"] ?? 0) === $id));

        if ($used > 0) {
            fail("Impossible de supprimer : $used offre(s) rattachée(s) à ce domaine", 409);
        }

        $removed = Domaine::fromArray($domaines[$index]);
        array_splice($domaines, $index, 1);
        saveDb($file, $domaines);

        respond($removed);
    }

    fail("Méthode non autorisée", 405);
} catch (InvalidArgumentException $e) {
    fail($e->getMessage(), 422);
}
