<?php
    require "database.php";
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
    exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $sql = "INSERT INTO Joueur (Nom, Prenom, Numero_licence, Date_naissance, Taille, Poids, Statut)
                VALUES (:nom, :prenom, :licence, :naissance, :taille, :poids, :statut)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":nom"       => $_POST["nom"],
            ":prenom"    => $_POST["prenom"],
            ":licence"   => $_POST["licence"],
            ":naissance" => $_POST["naissance"],
            ":taille"    => $_POST["taille"],
            ":poids"     => $_POST["poids"],
            ":statut"    => $_POST["statut"],
        ]);
        header("Location: liste_joueur.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100" rel="stylesheet">
    <title>
        Ajouter un joueur
    </title>
</head>
<body>
    <header>
    <nav class="navbar">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="accueil.php" class="nav-link">Accueil</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link">Joueur</a>
                <ul class="dropdown-menu">
                    <li><a href="ajout_joueur.php" class="dropdown-link">Ajouter un joueur</a></li>
                    <li><a href="liste_joueur.php" class="dropdown-link">Liste des joueurs</a></li>
                </ul>
             </li>
            <li class="nav-item dropdown">
                <a class="nav-link">Matchs</a>
                <ul class="dropdown-menu">
                    <li><a href="ajout_match.php" class="dropdown-link">Créer un match</a></li>
                    <li><a href="liste_match.php" class="dropdown-link">Liste des matchs</a></li>
                    <li><a href="feuille_match.php" class="dropdown-link">Feuille de match</a></li>
                </ul>
             </li>
             <li class="nav-item dropdown">
                <a class="nav-link">Statistiques</a>
                <ul class="dropdown-menu">
                    <li><a href="stats_equipe.php" class="dropdown-link">Statistiques équipe</a></li>
                </ul>
             </li>
        </ul>
    </nav>
    </header>

<h3>Ajouter un joueur</h3>

<div class="form-container">
    <form class="player-form" action="ajout_joueur.php" method="post">
        <label>Nom :</label> <input type="text" name="nom" required>
        <label>Prénom :</label> <input type="text" name="prenom" required>
        <label>Numéro de licence :</label> <input type="text" name="licence" required>
        <label>Date de naissance :</label> <input type="date" name="naissance" required>
        <label>Taille (cm) :</label> <input type="number" name="taille">
        <label>Poids (kg) :</label> <input type="number" name="poids" min="0">
        <label>Statut :</label>
        <select name="statut" required>
            <option value="">-- Sélectionner un statut --</option>
            <option value="Actif">Actif</option>
            <option value="Blessé">Blessé</option>
            <option value="Suspendu">Suspendu</option>
            <option value="Absent">Absent</option>
        </select>
        <label>Commentaire :</label> <textarea name="commentaire" rows="4"></textarea>
        <input type="submit" value="Enregistrer">
    </form>
</div>
