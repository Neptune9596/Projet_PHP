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
    public static function getTousLesMatchs(): array {
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

    public function __construct($date, $heure, $adversaire, $adr, $res, $lieu, $id) {
        $this->DateMatch = $date;
        $this->HeureMatch = $heure;
        $this->NomAdversaire = $adversaire;
        $this->Adresse = $adr;
        $this->Resultat = $res ?? ""; // Évite les erreurs si le résultat est NULL
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

    public function getNomAdv(): string {
        if (empty($this->NomAdversaire)) {
            throw new InvalidArgumentException("Aucun adversaire n'a été trouvé");
        }
        return $this->NomAdversaire;
    }

    public static function getMatchById($id){
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

    // --- Setters avec mise à jour DB (Optionnel, selon votre besoin) ---
    public function setResultat($res): void {
        $stmt = self::$pdo->prepare("UPDATE Matchs SET resultat = ? WHERE id_match = ?");
        $stmt->execute([$res, $this->IdMatch]);
        $this->Resultat = $res;
    }
}