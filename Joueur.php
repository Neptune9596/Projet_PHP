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
        $stmt = self::$pdo->query("SELECT * FROM Joueur");
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

    public static function getJoueurById($id) {
        $stmt = self::$pdo->prepare("SELECT * FROM Joueur WHERE id_joueur = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Joueur(
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
        return null;
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
        $stmt = self::$pdo->prepare("UPDATE Joueur SET statut = ? WHERE id_joueur = ?");
        $stmt->execute([$statut, $this->IdJoueur]);
        $this->Statut = $statut;
    }

    public function setNom($nom): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET nom = ? WHERE id_joueur = ?");
        $stmt->execute([$nom, $this->IdJoueur]);
        $this->Nom = $nom;
    }

    public function setPrenom($prenom): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET prenom = ? WHERE id_joueur = ?");
        $stmt->execute([$prenom, $this->IdJoueur]);
        $this->Prenom = $prenom;
    }

    public function setNumeroLicence($numeroLicence): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET numero_licence = ? WHERE id_joueur = ?");
        $stmt->execute([$numeroLicence, $this->IdJoueur]);
        $this->NumeroLicence = $numeroLicence;
    }

    public function setDateNaissance($dateNaissance): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET date_naissance = ? WHERE id_joueur = ?");
        $stmt->execute([$dateNaissance, $this->IdJoueur]);
        $this->DateNaissance = $dateNaissance;
    }

    public function setTaille($taille): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET taille = ? WHERE id_joueur = ?");
        $stmt->execute([$taille, $this->IdJoueur]);
        $this->Taille = $taille;
    }

    public function setPoids($poids): void {
        $stmt = self::$pdo->prepare("UPDATE Joueur SET poids = ? WHERE id_joueur = ?");
        $stmt->execute([$poids, $this->IdJoueur]);
        $this->Poids = $poids;
    }

    public function getId(){ 
        return $this->IdJoueur; 
    }


    public function getNom() : string {
        return $this->Nom;
    }

    public function getPrenom() : string {
        return $this->Prenom;
    }

    public function getNumeroLicence() : int {
        return $this->NumeroLicence;
    }

    public function getDateNaisssance() : string {
        return $this->DateNaissance;
    }

    public function getTaille() : int {
        return $this->Taille;
    }

    public function getPoids() : int {
        return $this->Poids;
    }
    public function getStatut(){
        return $this->Statut;
    }

    public static function create($nom, $prenom, $numeroLicence, $dateNaissance, $taille, $poids, $statut): void {
        $stmt = self::$pdo->prepare("INSERT INTO Joueur (nom, prenom, numero_licence, date_naissance, taille, poids, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $numeroLicence, $dateNaissance, $taille, $poids, $statut]);
    }

    public function delete(): void {
        $stmt = self::$pdo->prepare("DELETE FROM Joueur WHERE id_joueur = ?");
        $stmt->execute([$this->IdJoueur]);
    }
}

?>