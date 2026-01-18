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
    $matchs = Partie::getTousLesMatchs();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste des matchs</title>
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

<h3>Liste des matchs</h3>

<table class="table-joueurs">
    <thead>
        <tr>
            <th>Date</th>
            <th>Adversaire</th>
            <th>Résultat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($matchs)): ?>
            <?php foreach ($matchs as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m->getDate()) ?></td>
                <td><?= htmlspecialchars($m->getNomAdv()) ?></td>>
                <td><?= htmlspecialchars($m->getResultat()) ?></td>
                <td>
                    <a href="Modifier_Feuille_de_Match.php?id_match=<?= $m->getId() ?>" class="btn-modif">Visualiser/Modifier</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">Aucun match prévu pour le moment.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
