<?php
require "database.php";
$pdo = Database::getConnection();
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET["id_match"])) {
    exit("Aucun match sélectionné.");
}
$id_match = intval($_GET["id_match"]);
$stmt = $pdo->prepare("SELECT * FROM matchs WHERE id_match = ?");
$stmt->execute([$id_match]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$match) {
    exit("Match introuvable.");
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "UPDATE matchs
            SET date_match = ?, heure = ?, nom_adversaire = ?, adresse = ?, lieu_rencontre = ?, resultat = ?
            WHERE id_match = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST["date_match"],
        $_POST["heure"],
        $_POST["nom_adversaire"],
        $_POST["adresse"],
        $_POST["lieu_rencontre"],
        $_POST["resultat"],
        $id_match
    ]);
    header("Location: modif_match.php");
    exit();
}

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
    <input type="date" name="date_match" value="<?= $match['date_match'] ?>" required>
    <label>Heure du match :</label>
    <input type="time" name="heure" value="<?= $match['heure'] ?>" required> 
    <label>Nom de l'adversaire :</label>
    <input type="text" name="nom_adversaire" value="<?= $match['nom_adversaire'] ?>" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" value="<?= $match['lieu_rencontre'] ?>">
    <label>Adresse :</label>
    <input type="text" name="adresse" value="<?= $match['adresse'] ?>">
    <label>Résultat :</label>
    <input type="text" name="resultat" value="<?= $match['resultat'] ?>">
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
