<?php
    session_start();
    require "database.php";
    require "Joueur.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}      
    Joueur::setPdo($pdo);
    $joueurs = Joueur::getTouslesJoueurs();

    
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

<h3>Liste des joueurs détaillé</h3>

<table>
  <tr>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Numero de Licence</th>
    <th>Date de naissance</th>
    <th>Taille</th>
    <th>Poids</th>
    <th>Statut</th>
  </tr>

  <?php if (count($joueurs) > 0): ?>
      <?php foreach ($joueurs as $joueur): ?>
          <tr>
            <td><?= htmlspecialchars($joueur->getNom()) ?></td>
            <td><?= htmlspecialchars($joueur->getPrenom()) ?></td>
            <td><?= htmlspecialchars($joueur->getNumeroLicence()) ?></td>
            <td><?= htmlspecialchars($joueur->getDateNaisssance()) ?></td>
            <td><?= htmlspecialchars($joueur->getTaille()) ?></td>
            <td><?= htmlspecialchars($joueur->getPoids()) ?></td>
            <td><?= htmlspecialchars($joueur->getStatut()) ?></td>
          </tr>
      <?php endforeach; ?>
  <?php else: ?>
      <tr>
          <td colspan="3" style="text-align:center;">Aucun joueur dans l'équipe.</td>
      </tr>
  <?php endif; ?>
  </table>

</body>
</html>
