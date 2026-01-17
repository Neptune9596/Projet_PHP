<?php
    session_start();    
    require "database.php";
    require "Joueur.php";
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}   
    $pdo = Database::getConnection();
    Joueur::setPdo($pdo);
    $joueurs = Joueur::getTouslesJoueurs();
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100" rel="stylesheet">
    <title>
        Accueil
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
    <h1>Description de Match</h1>
    <div class="match-container">
        <div class="match-image">
            <img src="foot.jpg" alt="Match de foot">
        </div>
        <div class="match-content">
            <h2>Match à Barcelone</h2>
            <p>
                Ce match oppose deux équipes de haut niveau dans une rencontre spectaculaire. Les joueurs ont démontré une technique exceptionnelle et une détermination inébranlable sur le terrain. La première mi-temps a été marquée par des échanges intenses, avec des occasions de part et d'autre du terrain. En seconde période, le rythme s'est accéléré et les supporters ont vibré à chaque action. Un événement sportif inoubliable qui restera gravé dans les mémoires de tous les passionnés de football.
            </p>
        </div>
    </div>
    
    <h1>Les joueurs de l'équipe</h1>
    
  <table>
  <tr>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Rôle</th>
  </tr>

  <?php if (count($joueurs) > 0): ?>
      <?php foreach ($joueurs as $joueur): ?>
          <tr>
            <td><?= htmlspecialchars($joueur->getNom()) ?></td>
            <td><?= htmlspecialchars($joueur->getPrenom()) ?></td>
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