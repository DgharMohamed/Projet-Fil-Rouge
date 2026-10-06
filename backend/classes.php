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

class Offre implements JsonSerializable
{
    private int $id;
    private string $titre;
    private string $description;
    private float $salaire;
    private int $domaineId;

    public function __construct(
        int $id,
        string $titre,
        string $description,
        int|float|string $salaire,
        int $domaineId
    ) {
        $this->setId($id);
        $this->setTitre($titre);
        $this->setDescription($description);
        $this->setSalaire($salaire);
        $this->setDomaineId($domaineId);
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

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $titre = trim($titre);

        if ($titre === "") {
            throw new InvalidArgumentException("Le titre est obligatoire");
        }

        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function getSalaire(): float
    {
        return $this->salaire;
    }

    public function setSalaire(int|float|string $salaire): void
    {
        if (!is_numeric($salaire)) {
            throw new InvalidArgumentException("Le salaire doit être un nombre positif");
        }

        $salaire = (float) $salaire;

        if ($salaire < 0) {
            throw new InvalidArgumentException("Le salaire doit être un nombre positif");
        }

        $this->salaire = $salaire;
    }

    public function getDomaineId(): int
    {
        return $this->domaineId;
    }

    public function setDomaineId(int $domaineId): void
    {
        if ($domaineId < 1) {
            throw new InvalidArgumentException("Le domaine est obligatoire");
        }

        $this->domaineId = $domaineId;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "titre" => $this->getTitre(),
            "description" => $this->getDescription(),
            "salaire" => $this->getSalaire(),
            "domaine_id" => $this->getDomaineId(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromArray(array $data): self
    {
        foreach (["titre", "description", "salaire", "domaine_id"] as $key) {
            if (isset($data[$key]) && !is_scalar($data[$key])) {
                throw new InvalidArgumentException("Le champ '$key' est invalide");
            }
        }

        return new self(
            (int) ($data["id"] ?? 0),
            (string) ($data["titre"] ?? ""),
            (string) ($data["description"] ?? ""),
            $data["salaire"] ?? 0,
            (int) ($data["domaine_id"] ?? 0)
        );
    }
}
