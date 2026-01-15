<?php

class Joueur {
    private array $ListCommenatire;
    private int $IdJoueur;
    private string $Nom;
    private string $Prenom;
    private string $NumeroLicence;
    private string $DateNaissance;
    private int $Taille;
    private int $Poids;
    private string $Statut;

    public function __construct($idJoueur,$nom,$prenom,
    $numeroLicence,$dateNaissance,$taille,$poids,$statut) {
        $this->IdJoueur = $idJoueur;
        $this->Nom = $nom;
        $this->Prenom = $prenom;
        $this->NumeroLicence = $numeroLicence;
        $this->DateNaissance = $dateNaissance;
        $this->Taille = $taille;
        $this->Poids = $poids;
        $this->Statut = $statut;
    }

    public function getNom(){
        return $this->Nom;
    }

    public function getPrenom(){
        return $this->Prenom;
    }

    public function getNumeroLicence(){
        return $this->NumeroLicence;
    }

    public function getDateNaisssance(){
        return $this->DateNaissance;
    }

    public function getTaille(){
        return $this->Taille;
    }

    public function getPoids(){
        return $this->Poids;
    }

    public function setStatut($statut):void{
        ($this->Statut=$statut);
    }

    public function getStatut(){
        return $this->Statut;
    }

    public function ajouterCommentaire($commentaire){
        $this->ListCommenatire[] = $commentaire->getIdCommentaire();
    }

}

?>