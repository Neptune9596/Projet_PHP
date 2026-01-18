<?php
    session_start();
    require "database.php";
    require "Commentaire.php";
    require "Joueur.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
    if (!isset($_GET["id_joueur"])) {
    exit("Erreur, ce joueur n'existe pas.");
}
    Joueur::setPdo($pdo);
    Commentaire::setPdo($pdo);
    $id = $_GET['id_joueur'];
    $id_supprimer = $_GET['supprimer_com'] ?? null;
    $joueur = Joueur::getJoueurById($id);
    $commentaires = Commentaire::getByJoueur($id);

    // Suppression du commentaire
    if ($id_supprimer) {
        Commentaire::delete($id_supprimer);
        header("Location: Voir_commentaire.php?id_joueur=" . $id);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {  
        Commentaire::create(
            $id,
            $_POST['contenu']
        );
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
    <title>Liste des joueurs</title>
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

<h3>Liste des commentaires pour <?= $joueur->getNom() ?> <?= $joueur->getPrenom() ?> </h3>

<table>
  <tr>
    <th>Date</th>
    <th>Commentaire</th>
    <th>Actions</th>
  </tr>

  <?php if (count($commentaires) > 0): ?>
      <?php foreach ($commentaires as $commentaire): ?>
          <tr>
            <td><?= htmlspecialchars($commentaire->getDate_Commentaire()) ?></td>
            <td><?= htmlspecialchars($commentaire->getContenu()) ?></td>
            <td>
                <a href="Voir_commentaire.php?id_joueur=<?= $joueur->getId() ?>&supprimer_com=<?= $commentaire->getID() ?>" class="delete-bouton"
                onclick="return confirm('Attention : Cette action est irréversible. Confirmer ?')">Supprimer</a>
            </td>
      <?php endforeach; ?>
  <?php else: ?>
      <tr>
          <td colspan="3" style="text-align:center;">Aucun commentaire pour ce joueur.</td>
      </tr>
  <?php endif; ?>
  </table>

<h3>Ajouter un commentaire</h3>

<div class="form-container">
    <form class="joueur-form" action="Voir_commentaire.php?id_joueur=<?= $joueur->getId() ?>" method="post">
        <label>Commentaire :</label> <input type="text" name="contenu" required>        
        <input type="submit" value="Ajouter Commentaire">
</form>

</body>
</html>
