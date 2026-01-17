<?php
    session_start();
    require "database.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}      

    $joueurs = $pdo->query("SELECT * FROM joueur")->fetchAll(PDO::FETCH_ASSOC);
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

<h3>Liste des joueurs</h3>

<table class="table-joueurs">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>N° Licence</th>
            <th>Naissance</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($joueurs as $j): ?>
        <tr>
            <td><?= $j["nom"] ?></td>
            <td><?= $j["prenom"] ?></td>
            <td><?= $j["numero_licence"] ?></td>
            <td><?= $j["date_naissance"] ?></td>
            <td><?= $j["taille"] ?> cm</td>
            <td><?= $j["poids"] ?> kg</td>
            <td><?= $j["statut"] ?></td>
            <td>
                <a href="modifier_joueur.php?licence=<?= $j["numero_licence"] ?>">Modifier</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
