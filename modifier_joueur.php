<?php
    session_start();
    require "database.php";
    require "Joueur.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
    if (!isset($_GET["id_joueur"])) {
    exit("Erreur, ce match n'existe pas.");
}
    Joueur::setPdo($pdo);
    $id = $_GET['id_joueur'];
    $joueur = Joueur::getJoueurById($id);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $joueur->delete();
        header("Location: liste_joueur.php");
        exit();
    }
        $joueur->setNom($_POST['nom']);
        $joueur->setPrenom($_POST['prenom']);
        $joueur->setDateNaissance($_POST['naissance']);
        $joueur->setTaille($_POST['taille']);
        $joueur->setPoids($_POST['poids']);
        $joueur->setStatut($_POST['statut']);
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
    <input type="text" name="nom" value="<?= htmlspecialchars($joueur->getNom()) ?>" required>
    <label>Prénom :</label>
    <input type="text" name="prenom" value="<?= htmlspecialchars($joueur->getPrenom()) ?>" required>
    <label>Numéro de licence :</label>
    <input type="number" name="licence" value="<?= htmlspecialchars($joueur->getNumeroLicence()) ?>">
    <label>Date de naissance :</label>
    <input type="date" name="naissance" value="<?= htmlspecialchars($joueur->getDateNaisssance()) ?>" required>
    <label>Taille (cm) :</label>
    <input type="number" name="taille" value="<?= htmlspecialchars($joueur->getTaille()) ?>">
    <label>Poids (kg) :</label>
    <input type="number" name="poids" value="<?= htmlspecialchars($joueur->getPoids()) ?>">
    <label>Statut :</label>
    <select name="statut">
        <option value="">-- Sélectionner un statut --</option>
        <option value="actif"     <?= $joueur['statut']=="actif" ? "selected" : "" ?>>Actif</option>
        <option value="blessé"    <?= $joueur['statut']=="blessé" ? "selected" : "" ?>>Blessé</option>
        <option value="suspendu"  <?= $joueur['statut']=="suspendu" ? "selected" : "" ?>>Suspendu</option>
        <option value="absent"    <?= $joueur['statut']=="absent" ? "selected" : "" ?>>Absent</option>
    </select>
    <input type="submit" value="Enregistrer les modifications">
</form>
<form method="post" action="modifier_joueur.php?id_joueur=<?= $joueur->getId() ?>" class="delete-form">
        <input type="hidden" name="action" value="delete">
        <input type="submit" class="delete-bouton" value="Supprimer le joueur"
               onclick="return confirm('Attention : Cette action est irréversible. Confirmer ?')">
</form>
</div>
</body>
</html>