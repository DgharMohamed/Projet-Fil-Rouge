<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

function respond($data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function fail(string $message, int $status): never
{
    respond(["message" => $message], $status);
}

function body(): array
{
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        fail("Corps de requête JSON invalide", 400);
    }

    return $data;
}

function queryId(): ?int
{
    return isset($_GET["id"]) && $_GET["id"] !== "" ? (int) $_GET["id"] : null;
}

function loadDb(string $file): array
{
    if (!file_exists($file)) {
        return [];
    }

    return json_decode(file_get_contents($file), true) ?: [];
}

function saveDb(string $file, array $rows): void
{
    file_put_contents($file, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function nextId(array $rows): int
{
    return $rows ? max(array_column($rows, "id")) + 1 : 1;
}

function findRow(array $rows, int $id): ?array
{
    foreach ($rows as $row) {
        if ((int) ($row["id"] ?? 0) === $id) {
            return $row;
        }
    }

    return null;
}

function assertScalar(array $data, array $keys): void
{
    foreach ($keys as $key) {
        if (array_key_exists($key, $data) && !is_scalar($data[$key])) {
            throw new InvalidArgumentException("Le champ '$key' est invalide");
        }
    }
}

function strField(array $data, string $key): string
{
    $value = $data[$key] ?? "";

    if (!is_scalar($value)) {
        throw new InvalidArgumentException("Le champ '$key' est invalide");
    }

    return (string) $value;
}
