<?php

class Partie {
    private static $pdo;
    private int $IdMatch;
    private string $DateMatch;
    private string $HeureMatch;
    private string $NomAdversaire;
    private string $Adresse;
    private string $Resultat;
    private string $LieuDeRencontre;

    public static function setPdo($pdo_connection) {
        self::$pdo = $pdo_connection;
    }

    // Méthode pour récupérer tous les matchs
    public static function getTousLesMatchs(){
        // Correction : Matchs avec un 'M' majuscule
        $stmt = self::$pdo->query("SELECT * FROM Matchs");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $instances = [];
        foreach ($data as $row) {
            $instances[] = new Partie(
                $row['date_match'],
                $row['heure'],
                $row['nom_adversaire'],
                $row['adresse'],
                $row['resultat'],
                $row['lieu_de_rencontre'],
                $row['id_match']
            );
        }
        return $instances;
    }

    public static function getMatchById($id) {
        // Correction : Matchs avec un 'M' majuscule
        $stmt = self::$pdo->prepare("SELECT * FROM Matchs WHERE id_match = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Partie(
                $row['date_match'],
                $row['heure'],
                $row['nom_adversaire'],
                $row['adresse'],
                $row['resultat'],
                $row['lieu_de_rencontre'],
                $row['id_match']
            );
        }
        return null;
    }

    public function __construct($date, $heure, $adversaire, $adr, $res, $lieu, $id) {
        $this->DateMatch = $date;
        $this->HeureMatch = $heure;
        $this->NomAdversaire = $adversaire;
        $this->Adresse = $adr;
        $this->Resultat = $res ?? "";
        $this->LieuDeRencontre = $lieu;
        $this->IdMatch = $id;
    }

    // --- Getters ---
    public function getId(): int { return $this->IdMatch; }
    public function getDate(): string { return $this->DateMatch; }
    public function getHeure(): string { return $this->HeureMatch; }
    public function getAdresse(): string { return $this->Adresse; }
    public function getResultat(): string { return $this->Resultat; }
    public function getLieu(): string { return $this->LieuDeRencontre; }
    public function getNomAdv(): string { return $this->NomAdversaire; }

    public function setDateMatch(string $date): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET date_match = ? WHERE id_match = ?");
        $stmt->execute([$date, $this->IdMatch]);
        $this->DateMatch = $date;
    }

    public function setHeureMatch(string $heure): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET heure = ? WHERE id_match = ?");
        $stmt->execute([$heure, $this->IdMatch]);
        $this->HeureMatch = $heure;
    }

    public function setNomAdversaire(string $adversaire): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET nom_adversaire = ? WHERE id_match = ?");
        $stmt->execute([$adversaire, $this->IdMatch]);
        $this->NomAdversaire = $adversaire;
    }

    public function setAdresse(string $adresse): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET adresse = ? WHERE id_match = ?");
        $stmt->execute([$adresse, $this->IdMatch]);
        $this->Adresse = $adresse;
    }

    public function setLieuDeRencontre(string $lieu): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET lieu_de_rencontre = ? WHERE id_match = ?");
        $stmt->execute([$lieu, $this->IdMatch]);
        $this->LieuDeRencontre = $lieu;
    }

    public function setResultat(string $res): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET resultat = ? WHERE id_match = ?");
        $stmt->execute([$res, $this->IdMatch]);
        $this->Resultat = $res;
    }
}