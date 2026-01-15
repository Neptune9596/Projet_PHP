<?php
  class Partie {
    private date $DateMatch;
    private int $HeureMatch;
    private string $NomAdversaire;
    private string $Adresse;
    private string $Resultat;
    private string $LieuDeRecontre;
    private int $IdMatch;

    public function __construct($date, $heure, $adversaire, $adr, $res, $lieu, $id) {
      $this->DateMatch = $date;
      $this->HeureMatch = $heure;
      $this->NomAdversaire = $adversaire;
      $this->Adresse = $adr;
      $this->Resultat = $res;
      $this->LieuDeRencontre = $lieu;
      $this->IdMatch = $id;
    }

    public function setResultat($res): void {
      ($this->Resultat = $res);
    }

    public function getDate(): string{
      return $this->DateMatch;
    }

    public function getHeure(): int{
      return $this->HeureMatch;
    }

    public function getNomAdv(): string{
      if ($this->NomAdversaire == "") {
        throw new InvalidArgumentException("Aucun n'adversaire n'a été trouvé");
      }
      return $this->NomAdversaire;
    }

    public function getLieu(): string{
      return $this->LieuDeRecontre;
    }

    public function getResultat(): string{
      return $this->Resultat;
    }

  }

?>