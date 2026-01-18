<?php

class Participe {
    private static $pdo;
    
    // Propriétés correspondant à la table 'Participation'
    private $IdParticipation;
    private $IdMatch;
    private $IdJoueur;
    private $Poste;
    private $Etat;
    private $Evaluation;

    public static function setPdo($pdo_connection) {
        self::$pdo = $pdo_connection;
    }

    public function __construct($id_participation, $id_match, $id_joueur, $poste, $etat, $evaluation) {
        $this->IdParticipation = $id_participation;
        $this->IdMatch = $id_match;
        $this->IdJoueur = $id_joueur;
        $this->Poste = $poste;
        $this->Etat = $etat;
        $this->Evaluation = $evaluation;
    }

    // --- CRUD (Create, Read, Delete) ---

    // Créer une nouvelle participation
    public static function create($id_match, $id_joueur, $poste, $etat, $evaluation = null) {
        $sql = "INSERT INTO Participation (id_match, id_joueur, poste, etat, evaluation) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_match, $id_joueur, $poste, $etat, $evaluation]);
        return self::$pdo->lastInsertId();
    }

    // Supprimer une participation (retirer un joueur de la feuille de match)
    public static function delete($id_participation) {
        $stmt = self::$pdo->prepare("DELETE FROM Participation WHERE id_participation = ?");
        return $stmt->execute([$id_participation]);
    }

    // Récupérer toutes les participations pour un match précis
    public static function getByMatch($id_match) {
        $stmt = self::$pdo->prepare("SELECT * FROM Participation WHERE id_match = ?");
        $stmt->execute([$id_match]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $instances = [];
        foreach ($data as $row) {
            $instances[] = new Participe(
                $row['id_participation'],
                $row['id_match'],
                $row['id_joueur'],
                $row['poste'],
                $row['etat'],
                $row['evaluation']
            );
        }
        return $instances;
    }
    
    // Récupérer une participation précise par son ID
    public static function getById($id_participation) {
        $stmt = self::$pdo->prepare("SELECT * FROM Participation WHERE id_participation = ?");
        $stmt->execute([$id_participation]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Participe(
                $row['id_participation'],
                $row['id_match'],
                $row['id_joueur'],
                $row['poste'],
                $row['etat'],
                $row['evaluation']
            );
        }
        return null;
    }

    // Getters
    public function getId() { return $this->IdParticipation; }
    public function getIdMatch() { return $this->IdMatch; }
    public function getIdJoueur() { return $this->IdJoueur; }
    public function getPoste() { return $this->Poste; }
    public function getEtat() { return $this->Etat; }
    public function getEvaluation() { return $this->Evaluation; }

    //Setters 

    public function setPoste($poste) {
        $stmt = self::$pdo->prepare("UPDATE Participation SET poste = ? WHERE id_participation = ?");
        $stmt->execute([$poste, $this->IdParticipation]);
        $this->Poste = $poste;
    }

    public function setEtat($etat) {
        $stmt = self::$pdo->prepare("UPDATE Participation SET etat = ? WHERE id_participation = ?");
        $stmt->execute([$etat, $this->IdParticipation]);
        $this->Etat = $etat;
    }

    public function setEvaluation($evaluation) {
        $stmt = self::$pdo->prepare("UPDATE Participation SET evaluation = ? WHERE id_participation = ?");
        $stmt->execute([$evaluation, $this->IdParticipation]);
        $this->Evaluation = $evaluation;
    }
}
?>