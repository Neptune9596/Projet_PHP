<?php   
session_start();
require "database.php";
require "Partie.php";
$pdo = Database::getConnection();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id_match"])) {
    exit("Erreur, ce match n'existe pas.");
}
$id = $_GET['id_match'];
Partie::setPdo($pdo);
$match = Partie::getMatchById($id);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Modifier le match</title>
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

<h3>Modifier le match</h3>

<div class="form-container">

<form class="player-form" method="post" action="liste_match.php?id_match=<?= $id_match ?>">
    <label>Date du match :</label>
    <input type="date" name="date_match" value="<?= htmlspecialchars($match->getDate()) ?>" required>
    <label>Heure du match :</label>
    <input type="time" name="heure" value="<?= htmlspecialchars($match->getHeure()) ?>" required> 
    <label>Nom de l'adversaire :</label>
    <input type="text" name="nom_adversaire" value="<?= htmlspecialchars($match->getNomAdv()) ?>" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" value="<?= htmlspecialchars($match->getLieu()) ?>">
    <label>Adresse :</label>
    <input type="text" name="adresse" value="<?= htmlspecialchars($match->getAdresse()) ?>">
    <label>Résultat :</label>
    <input type="text" name="resultat" value="<?= htmlspecialchars($match->getResultat()) ?>">
    <input type="submit" value="Enregistrer">
</form>
<form method="post" action="supprimer_match.php" class="delete-form">
    <input type="hidden" name="id_match" value="<?= $match['id_match'] ?>">
    <input type="submit" class="delete-button" value="Supprimer le match"
           onclick="return confirm('Voulez-vous vraiment supprimer ce match ?')">
</form>

</div>

</body>
</html>
