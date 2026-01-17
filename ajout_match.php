<?php
    session_start();
    require "database.php";
    require "Partie.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
    Partie::setPdo($pdo);
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $matchs = Partie::create(
            $_POST['date_match'],
            $_POST['heure'],
            $_POST['nom_adversaire'],
            $_POST['lieu_rencontre']
        );
        header("Location: liste_match.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Créer un match</title>
</head>
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

<body>

<h3>Créer un match</h3>

<?php if ($erreur): ?>
    <p style="color:red; font-weight:bold; text-align:center;"><?= $erreur ?></p>
<?php endif; ?>

<div class="form-container">
<form class="player-form" method="post">
    <label>Date du match :</label>
    <input type="date" name="date_match" required>
    <label>Heure du match :</label>
    <input type="time" name="heure" required>
    <label>Nom adversaire :</label>
    <input type="text" name="nom_adversaire" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" required>
    <label>Resultat :</label>
    <input type="text" name="resultat" readonly>
    <input type="submit" value="Créer le match" class="submit-button">
</form>
</div>
</body>
</html>
