<?php

class Joueur {
    private static $pdo;
    private int $IdJoueur;
    private string $Nom;
    private string $Prenom;
    private int $NumeroLicence;
    private string $DateNaissance;
    private int $Taille;
    private int $Poids;
    private string $Statut;

    public static function setPdo($pdo_connection) {
        self::$pdo = $pdo_connection;
    }

    public static function getTouslesJoueurs(): array {
        $stmt = self::$pdo->query("SELECT * FROM joueur");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $instances = [];
        foreach ($data as $row) {
            $instances[] = new Joueur(
                $row['id_joueur'],
                $row['nom'],
                $row['prenom'],
                $row['numero_licence'],
                $row['date_naissance'],
                $row['taille'],
                $row['poids'],
                $row['statut']
            );
        }

        return $instances;
    }

    public function __construct($idJoueur, $nom, $prenom, $numeroLicence, $dateNaissance, $taille, $poids, $statut) {
        $this->IdJoueur = $idJoueur;
        $this->Nom = $nom;
        $this->Prenom = $prenom;
        $this->NumeroLicence = $numeroLicence;
        $this->DateNaissance = $dateNaissance;
        $this->Taille = $taille;
        $this->Poids = $poids;
        $this->Statut = $statut;
    }

    public function setStatut($statut): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET statut = ? WHERE id_joueur = ?");
        $stmt->execute([$statut, $this->IdJoueur]);
        $this->Statut = $statut;
    }

    public function setNom($nom): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET nom = ? WHERE id_joueur = ?");
        $stmt->execute([$nom, $this->IdJoueur]);
        $this->Nom = $nom;
    }

    public function setPrenom($prenom): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET prenom = ? WHERE id_joueur = ?");
        $stmt->execute([$prenom, $this->IdJoueur]);
        $this->Prenom = $prenom;
    }

    public function setNumeroLicence($numeroLicence): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET numero_licence = ? WHERE id_joueur = ?");
        $stmt->execute([$numeroLicence, $this->IdJoueur]);
        $this->NumeroLicence = $numeroLicence;
    }

    public function setDateNaissance($dateNaissance): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET date_naissance = ? WHERE id_joueur = ?");
        $stmt->execute([$dateNaissance, $this->IdJoueur]);
        $this->DateNaissance = $dateNaissance;
    }

    public function setTaille($taille): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET taille = ? WHERE id_joueur = ?");
        $stmt->execute([$taille, $this->IdJoueur]);
        $this->Taille = $taille;
    }

    public function setPoids($poids): void {
        $stmt = self::$pdo->prepare("UPDATE joueur SET poids = ? WHERE id_joueur = ?");
        $stmt->execute([$poids, $this->IdJoueur]);
        $this->Poids = $poids;
    }

    public function getNom(): string {
        $stmt = self::$pdo->prepare("SELECT nom FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->Nom = $data['nom'];
            return $data['nom'];
        }
        throw new Exception("Nom introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getPrenom(): string {
        $stmt = self::$pdo->prepare("SELECT prenom FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->Prenom = $data['prenom'];
            return $data['prenom'];
        }
        throw new Exception("Prénom introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getNumeroLicence(): int {
        $stmt = self::$pdo->prepare("SELECT numero_licence FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->NumeroLicence = $data['numero_licence'];
            return $data['numero_licence'];
        }
        throw new Exception("Numéro de licence introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getDateNaissance(): string {
        $stmt = self::$pdo->prepare("SELECT date_naissance FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->DateNaissance = $data['date_naissance'];
            return $data['date_naissance'];
        }
        throw new Exception("Date de naissance introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getTaille(): int {
        $stmt = self::$pdo->prepare("SELECT taille FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->Taille = $data['taille'];
            return $data['taille'];
        }
        throw new Exception("Taille introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getPoids(): int {
        $stmt = self::$pdo->prepare("SELECT poids FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->Poids = $data['poids'];
            return $data['poids'];
        }
        throw new Exception("Poids introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public function getStatut(): string {
        $stmt = self::$pdo->prepare("SELECT statut FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->Statut = $data['statut'];
            return $data['statut'];
        }
        throw new Exception("Statut introuvable pour le joueur ID {$this->IdJoueur}");
    }

    public static function create($nom, $prenom, $numeroLicence, $dateNaissance, $taille, $poids, $statut): void {
        $stmt = self::$pdo->prepare("INSERT INTO joueur (nom, prenom, numero_licence, date_naissance, taille, poids, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $numeroLicence, $dateNaissance, $taille, $poids, $statut]);
    }

    public function delete(): void {
        $stmt = self::$pdo->prepare("DELETE FROM joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
    }
}

?>