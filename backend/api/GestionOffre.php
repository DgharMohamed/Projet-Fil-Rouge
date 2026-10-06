<?php

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../classes.php";

$file = __DIR__ . "/../database/offres.json";
$domainesFile = __DIR__ . "/../database/domaines.json";

$offres = loadDb($file);
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $id = queryId();

        if ($id === null) {
            respond(array_map(fn (array $row) => Offre::fromArray($row), $offres));
        }

        $row = findRow($offres, $id);

        if ($row === null) {
            fail("Offre introuvable", 404);
        }

        respond(Offre::fromArray($row));
    }

    if ($method === "POST") {
        $offre = buildOffre(body(), $domainesFile);
        $offre->setId(nextId($offres));

        $offres[] = $offre;
        saveDb($file, $offres);

        respond($offre, 201);
    }

    if ($method === "PUT") {
        $id = queryId();

        if ($id === null) {
            fail("Paramètre id manquant", 400);
        }

        $index = array_search($id, array_column($offres, "id"), true);

        if ($index === false) {
            fail("Offre introuvable", 404);
        }

        $data = body();
        assertScalar($data, ["titre", "description", "salaire", "domaine_id"]);

        $offre = Offre::fromArray($offres[$index]);

        if (array_key_exists("titre", $data)) {
            $offre->setTitre(strField($data, "titre"));
        }

        if (array_key_exists("description", $data)) {
            $offre->setDescription(strField($data, "description"));
        }

        if (array_key_exists("salaire", $data)) {
            $offre->setSalaire($data["salaire"]);
        }

        if (array_key_exists("domaine_id", $data)) {
            $offre->setDomaineId((int) $data["domaine_id"]);
        }

        assertDomaineExists($domainesFile, $offre->getDomaineId());

        $offres[$index] = $offre;
        saveDb($file, $offres);

        respond($offre);
    }

    if ($method === "DELETE") {
        $id = queryId();

        if ($id === null) {
            fail("Paramètre id manquant", 400);
        }

        $index = array_search($id, array_column($offres, "id"), true);

        if ($index === false) {
            fail("Offre introuvable", 404);
        }

        $removed = Offre::fromArray($offres[$index]);
        array_splice($offres, $index, 1);
        saveDb($file, $offres);

        respond($removed);
    }

    fail("Méthode non autorisée", 405);
} catch (InvalidArgumentException $e) {
    fail($e->getMessage(), 422);
}

function buildOffre(array $data, string $domainesFile): Offre
{
    assertScalar($data, ["titre", "description", "salaire", "domaine_id"]);

    $offre = new Offre(
        0,
        strField($data, "titre"),
        strField($data, "description"),
        $data["salaire"] ?? 0,
        (int) ($data["domaine_id"] ?? 0)
    );

    assertDomaineExists($domainesFile, $offre->getDomaineId());

    return $offre;
}

function assertDomaineExists(string $domainesFile, int $domaineId): void
{
    if (!findRow(loadDb($domainesFile), $domaineId)) {
        throw new InvalidArgumentException("Le domaine sélectionné n'existe pas");
    }
}
