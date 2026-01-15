<?php

class Commentaire {
    private int $Id_Commentaire;
    private String $Contenu;
    private date $Date_Commentaire;

    public function __construct($Id_joueur, $Id_Commentaire, $Contenu, $Date_Commentaire){
        $this->Id_Commentaire = $Id_Commentaire;
        $this->Contenu = $Contenu;
        $this->Date_Commentaire = $Date_Commentaire;
        $Id_joueur.ajouterCommentaire($this);
    }

    public function getContenu(): String{
        return $this->Contenu;
    }

    public function getDate_Commentaire(): date{
        return $this->Date_Commentaire;
    }

    public function getID(): int {
        return $this->Id_Commentaire;
    }
}
?>