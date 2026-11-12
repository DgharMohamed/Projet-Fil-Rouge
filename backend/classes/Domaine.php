<?php

class Domaine implements JsonSerializable
{
    private int $id;
    private string $nom;

    public function __construct(int $id, string $nom)
    {
        $this->setId($id);
        $this->setNom($nom);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        if ($id < 0) {
            throw new InvalidArgumentException("L'identifiant doit être un entier positif");
        }

        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $nom = trim($nom);

        if ($nom === "") {
            throw new InvalidArgumentException("Le nom du domaine est obligatoire");
        }

        $this->nom = $nom;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "nom" => $this->getNom(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromArray(array $data): self
    {
        $nom = $data["nom"] ?? "";

        if (!is_scalar($nom)) {
            throw new InvalidArgumentException("Le champ 'nom' est invalide");
        }

        return new self((int) ($data["id"] ?? 0), (string) $nom);
    }
}
