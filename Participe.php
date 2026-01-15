<?php
    class Participe {
        private int $IdMatch;
        private int $IdJoueur;
        private string $Poste;
        private string $Etat;
        public string $Evaluation;

        public function __construct($poste, $etat, $evaluation, $idMatch, $idJoueur){
            $this->IdMatch = $idMatch;
            $this->IdJoueur = $idJoueur;
            $this->Poste = $poste;
            $this->Etat = $etat;
            $this->Evaluation = $evaluation;
        }

        public function getPoste(): string{
            return $this->Poste;
        }

        public function getEtat(): string{
            return $this->Etat;
        }

        public function setPoste($poste): void {
            ($this->Poste = $poste);
        }

        public function setEtat($etat): void {
            ($this->Etat = $etat);
        }
    }
?>