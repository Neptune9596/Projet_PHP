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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Bloquer la modification/suppression des matchs passés
    $dateMatchComplete = new DateTime($match->getDate() . ' ' . $match->getHeure());
    $maintenant = new DateTime();

    if ($dateMatchComplete < $maintenant) {
        header("Location: liste_match.php");
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        Partie::delete($id);
        header("Location: liste_match.php");
        exit();
    }
    else {
    $match->setDateMatch($_POST['date_match']);
    $match->setHeureMatch($_POST['heure']);
    $match->setNomAdversaire($_POST['nom_adversaire']);
    $match->setLieuDeRencontre($_POST['lieu_rencontre']);
    $match->setResultat($_POST['resultat']);
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

<form class="joueur-form" method="post" action="modif_match.php?id_match=<?= $match->getId() ?>">
    <label>Date du match :</label>
    <input type="date" name="date_match" value="<?= htmlspecialchars($match->getDate()) ?>" required>
    <label>Heure du match :</label>
    <input type="time" name="heure" value="<?= htmlspecialchars($match->getHeure()) ?>" required> 
    <label>Nom de l'adversaire :</label>
    <input type="text" name="nom_adversaire" value="<?= htmlspecialchars($match->getNomAdv()) ?>" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" value="<?= htmlspecialchars($match->getLieu()) ?>">
    <label>Résultat :</label>
    <input type="text" name="resultat" value="<?= htmlspecialchars($match->getResultat()) ?>">
    <input type="submit" value="Enregistrer">
</form>

<form method="post" action="modif_match.php?id_match=<?= $match->getId() ?>" class="delete-form">
        <input type="hidden" name="action" value="delete">
        <input type="submit" class="delete-bouton" value="Supprimer le match"
               onclick="return confirm('Attention : Cette action est irréversible. Confirmer ?')">
    </form>

</div>

</body>
</html>
