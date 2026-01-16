<?php
    require "database.php";
    $pdo = Database::getConnection();
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }
    $erreur = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $date = $_POST["date_match"];
        $heure = $_POST["heure"];
        $today = date("Y-m-d");
        $currentTime = date("H:i");
        if ($date < $today) {
            $erreur = "La date du match ne peut pas être dans le passé.";
        }
        if ($erreur === "" && $date == $today && $heure < $currentTime) {
            $erreur = "L'heure du match est déjà passée.";
        }
        if ($erreur === "") {
            $sql = "INSERT INTO matchs (date_match, heure, nom_adversaire, adresse, lieu_rencontre)
                    VALUES (:date_match, :heure, :nom_adversaire, :adresse, :lieu_rencontre)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":date_match"     => $date,
                ":heure"          => $heure,
                ":nom_adversaire" => $_POST["nom_adversaire"],
                ":adresse"        => $_POST["adresse"],
                ":lieu_rencontre" => $_POST["lieu_rencontre"],
            ]);
            header("Location: liste_match.php");
            exit();
        }
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
    <label>Adresse :</label>
    <input type="text" name="adresse" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" required>
    <label>Resultat :</label>
    <input type="text" name="resultat" readonly>
    <input type="submit" value="Créer le match" class="submit-button">
</form>
</div>
</body>
</html>
