<?php
    session_start();
    require "database.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

    if (!isset($_GET["licence"])) {
        exit("Licence manquante.");
    }
    $licence = $_GET["licence"];
    $stmt = $pdo->prepare("SELECT * FROM joueur WHERE numero_licence = ?");
    $stmt->execute([$licence]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$joueur) {
        exit("Joueur introuvable.");
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $naissance = $_POST["naissance"];
        $taille = $_POST["taille"];
        $poids = $_POST["poids"];
        $statut = $_POST["statut"];
        $update = $pdo->prepare("
            UPDATE joueur 
            SET nom = ?, prenom = ?, date_naissance = ?, taille = ?, poids = ?, statut = ?
            WHERE numero_licence = ?
        ");
        $update->execute([$nom, $prenom, $naissance, $taille, $poids, $statut, $licence]);
        header("Location: liste_joueur.php");
        exit();
    }
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE id_joueur = ?");
    $stmt2->execute([$joueur["id_joueur"]]);
    $nb_matchs = $stmt2->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Modifier un joueur</title>
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

<h3>Modifier un joueur</h3>

<div class="form-container">
<form class="player-form" method="post" action="modifier_joueur.php?licence=<?= $joueur['numero_licence'] ?>">
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" required>
    <label>Prénom :</label>
    <input type="text" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" required>
    <input type="hidden" name="licence" value="<?= $joueur['numero_licence'] ?>">
    <label>Date de naissance :</label>
    <input type="date" name="naissance" value="<?= $joueur['date_naissance'] ?>" required>
    <label>Taille (cm) :</label>
    <input type="number" name="taille" value="<?= $joueur['taille'] ?>">
    <label>Poids (kg) :</label>
    <input type="number" name="poids" value="<?= $joueur['poids'] ?>">
    <label>Statut :</label>
    <select name="statut">
        <option value="">-- Sélectionner un statut --</option>
        <option value="actif"     <?= $joueur['statut']=="actif" ? "selected" : "" ?>>Actif</option>
        <option value="blessé"    <?= $joueur['statut']=="blessé" ? "selected" : "" ?>>Blessé</option>
        <option value="suspendu"  <?= $joueur['statut']=="suspendu" ? "selected" : "" ?>>Suspendu</option>
        <option value="absent"    <?= $joueur['statut']=="absent" ? "selected" : "" ?>>Absent</option>
    </select>
    <label>Nombre de matchs joués :</label>
    <input type="number" value="<?= $nb_matchs ?>" readonly>
    <input type="submit" value="Enregistrer" action="liste_joueur.php" method="post">
</form>
    <?php
        if ($nb_matchs == 0) {
            echo '
            <form method="post" action="supprimer_joueur.php" class="delete-form">
                <input type="hidden" name="licence" value="'.$joueur["numero_licence"].'">
                <input type="submit" class="delete-button" value="Supprimer le joueur">
            </form>
            ';
        } else {
            echo '
            <button class="delete-button disabled"
                    onclick="alert(\'Impossible de supprimer ce joueur : il a déjà participé à un match.\')">
                Supprimer le joueur
            </button>
            ';
        }
    ?>
</div>
</body>
</html>