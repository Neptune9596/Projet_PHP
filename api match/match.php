<?php
    //Objet Match DTO
    class Match{
        public static $pdo;
        public int $IdMatch;
        public string $DateMatch;
        public string $HeureMatch;
        public string $NomAdversaire;
        public string $Resultat;
        public string $LieuDeRencontre;

        public function __construct($date, $heure, $adversaire, $res, $lieu, $id)
        {
            $this->DateMatch = $date;
            $this->HeureMatch = $heure;
            $this->NomAdversaire = $adversaire;
            $this->Resultat = $res ?? "";
            $this->LieuDeRencontre = $lieu;
            $this->IdMatch = $id;
        }
    }
?>