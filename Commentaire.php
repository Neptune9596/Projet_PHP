<?php

class Commentaire {
    private static $pdo;
    private $Id_Commentaire;
    private $Id_Joueur;
    private $Contenu;
    private $Date_Commentaire;

    public static function setPdo($pdo_connection) {
        self::$pdo = $pdo_connection;
    }

    public function __construct($id_commentaire, $id_joueur, $contenu, $date_commentaire) {
        $this->Id_Commentaire = $id_commentaire;
        $this->Id_Joueur = $id_joueur;
        $this->Contenu = $contenu;
        $this->Date_Commentaire = $date_commentaire;
    }

    // --- Méthodes de communication avec la BD ---

    // Ajouter un commentaire dans la BD
    public static function create($id_joueur, $contenu) {
        $date = date('Y-m-d'); // Date du jour
        $stmt = self::$pdo->prepare("INSERT INTO Commentaires (id_joueur, contenu, date_commentaire) VALUES (?, ?, ?)");
        $stmt->execute([$id_joueur, $contenu, $date]);
        return self::$pdo->lastInsertId();
    }

    // Supprimer un commentaire de la BD
    public static function delete($id_commentaire) {
        $stmt = self::$pdo->prepare("DELETE FROM Commentaires WHERE id_commentaire = ?");
        return $stmt->execute([$id_commentaire]);
    }

    // Récupérer les commentaires d'un joueur spécifique
    public static function getByJoueur($id_joueur) {
        $stmt = self::$pdo->prepare("SELECT * FROM Commentaires WHERE id_joueur = ? ORDER BY date_commentaire DESC");
        $stmt->execute([$id_joueur]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $commentaires = [];
        foreach ($data as $row) {
            $commentaires[] = new Commentaire(
                $row['id_commentaire'],
                $row['id_joueur'],
                $row['contenu'],
                $row['date_commentaire']
            );
        }
        return $commentaires;
    }

    // --- Getters ---
    public function getID() { return $this->Id_Commentaire; }
    public function getIdJoueur() { return $this->Id_Joueur; }
    public function getContenu() { return $this->Contenu; }
    public function getDate_Commentaire() { return $this->Date_Commentaire; }
}
?>