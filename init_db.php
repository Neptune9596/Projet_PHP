<?php
require_once 'database.php';
$db = Database::getConnection();

// --- Table Joueur ---
$db->exec("
CREATE TABLE IF NOT EXISTS Joueur (
    id_joueur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    numero_licence INT UNIQUE NOT NULL,
    date_naissance DATE NOT NULL,
    taille INT,
    poids INT,
    statut ENUM('Actif','Blessé','Suspendu','Absent') DEFAULT 'Actif'
)
");

// --- Table Matchs ---
$db->exec("
CREATE TABLE IF NOT EXISTS Matchs (
    id_match INT AUTO_INCREMENT PRIMARY KEY,
    date_match DATE NOT NULL,
    heure TIME NOT NULL,
    nom_adversaire VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    resultat VARCHAR(50), 
    lieu_de_rencontre VARCHAR(255) NOT NULL
)
");

// --- Table Participation ---
$db->exec("
CREATE TABLE IF NOT EXISTS Participation (
    id_participation INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    id_match INT NOT NULL,
    poste ENUM('Gardien','Défenseur','Milieu','Attaquant') NOT NULL,
    etat ENUM('Titulaire','Remplaçant') NOT NULL,
    evaluation INT,

    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE RESTRICT,
    FOREIGN KEY (id_match) REFERENCES Matchs(id_match)
        ON DELETE CASCADE
)
");

// --- Table FeuilleDeMatch ---
$db->exec("
CREATE TABLE IF NOT EXISTS FeuilleDeMatch (
    id_feuille INT AUTO_INCREMENT PRIMARY KEY,
    id_match INT NOT NULL UNIQUE,

    FOREIGN KEY (id_match) REFERENCES Matchs(id_match)
        ON DELETE CASCADE
)
");

// --- Table Commentaires ---
$db->exec("
CREATE TABLE IF NOT EXISTS Commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_commentaire DATE DEFAULT CURRENT_DATE,

    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE CASCADE
)
");

// --- Table Statistiques ---
$db->exec("
CREATE TABLE IF NOT EXISTS Statistiques (
    id_stats INT AUTO_INCREMENT PRIMARY KEY,
    id_joueur INT NOT NULL,
    matchs_gagnes INT DEFAULT 0,
    matchs_perdus INT DEFAULT 0,
    matchs_nuls INT DEFAULT 0,
    moyenne_evaluation FLOAT,

    FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur)
        ON DELETE CASCADE
)
");

// --- Table Coach ---
$db->exec("
CREATE TABLE IF NOT EXISTS Coach (
    id_coach INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
)
");

echo "Tables créées !";
