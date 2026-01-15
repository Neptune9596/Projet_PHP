<?php
require_once 'database.php';
$db = Database::getConnection();

$db->exec("
CREATE DATABASE IF NOT EXISTS football;
USE football;

-- =========================================================
-- TABLE : Joueur
-- =========================================================
CREATE TABLE Joueur (
    id_joueur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    numero_licence INT UNIQUE NOT NULL,
    date_naissance DATE NOT NULL,
    taille INT,
    poids INT,
    statut ENUM('Actif', 'Blessé', 'Suspendu', 'Absent') DEFAULT 'Actif'
);

-- =========================================================
-- TABLE : Match
-- =========================================================
CREATE TABLE Matchs (
    id_match INT AUTO_INCREMENT PRIMARY KEY,
    date_match DATE NOT NULL,
    heure TIME NOT NULL,
    nom_adversaire VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    resultat VARCHAR(50), -- Exemple : "2-1", "0-3", "Victoire"
    lieu_de_rencontre VARCHAR(255) NOT NULL
);

-- =========================================================
-- TABLE : Participation (relation Joueur <-> Match)
-- =========================================================
CREATE TABLE Participation (
    id_participation INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    id_match INT NOT NULL,
    poste ENUM('Gardien', 'Défenseur', 'Milieu', 'Attaquant') NOT NULL,
    etat ENUM('Titulaire', 'Remplaçant') NOT NULL,
    evaluation INT CHECK (evaluation BETWEEN 0 AND 10),

    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE RESTRICT,  -- empêche la suppression d'un joueur ayant joué
    FOREIGN KEY (id_match) REFERENCES Matchs(id_match)
        ON DELETE CASCADE     -- si un match est supprimé → les participations aussi
);

-- =========================================================
-- TABLE : Feuille de match (1 match = 1 feuille)
-- =========================================================
CREATE TABLE FeuilleDeMatch (
    id_feuille INT AUTO_INCREMENT PRIMARY KEY,
    id_match INT NOT NULL UNIQUE,

    FOREIGN KEY (id_match) REFERENCES Matchs(id_match)
        ON DELETE CASCADE
);

-- =========================================================
-- TABLE : Commentaires (lié à un joueur)
-- =========================================================
CREATE TABLE Commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_commentaire DATE DEFAULT CURRENT_DATE,

    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE CASCADE
);

-- =========================================================
-- TABLE : Statistiques (optionnel selon ton UML)
-- =========================================================
CREATE TABLE Statistiques (
    id_stats INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    matchs_gagnes INT DEFAULT 0,
    matchs_perdus INT DEFAULT 0,
    matchs_nuls INT DEFAULT 0,
    moyenne_evaluation FLOAT,
    
    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE CASCADE
);

CREATE TABLE coach (
    id_coach INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
);
);

echo "Tables crées";
