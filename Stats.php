<?php
session_start();
require "database.php";
require "Statistiques.php"; 

$pdo = Database::getConnection();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

Statistiques::setPdo($pdo);


$statsGlobales = Statistiques::getGlobalStats();
$statsJoueurs = Statistiques::getJoueursFullStats();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="statistiques.css">
    <title>Statistiques de l'Équipe</title>
</head>
<body>

    <header>
        <nav class="navbar">
            <ul class="nav-list">
                <li class="nav-item"><a href="accueil.php" class="nav-link">Accueil</a></li>
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
                <li class="nav-item">
                    <a href="Stats.php" class="nav-link">Statistiques</a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Statistiques Générales de l'Équipe</h2>
        
        <div class="grille-stats">
            <div class="carte-stat">
                <h4>Matchs Joués</h4>
                <p class="chiffre-stat"><?= $statsGlobales['total'] ?></p>
            </div>
            <div class="carte-stat victoire">
                <h4>Gagnés</h4>
                <p class="chiffre-stat"><?= $statsGlobales['gagnes'] ?></p>
                <p class="pourcentage-stat"><?= $statsGlobales['pourcentage_gagnes'] ?>%</p>
            </div>
            <div class="carte-stat nul">
                <h4>Nuls</h4>
                <p class="chiffre-stat"><?= $statsGlobales['nuls'] ?></p>
                <p class="pourcentage-stat"><?= $statsGlobales['pourcentage_nuls'] ?>%</p>
            </div>
            <div class="carte-stat defaite">
                <h4>Perdus</h4>
                <p class="chiffre-stat"><?= $statsGlobales['perdus'] ?></p>
                <p class="pourcentage-stat"><?= $statsGlobales['pourcentage_perdus'] ?>%</p>
            </div>
        </div>

        <hr>

        <h2>Performances des Joueurs</h2>
        
        <table class="tableau-stats">
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Statut Actuel</th>
                    <th>Poste Préféré</th>
                    <th>Titularisations</th>
                    <th>Remplacements</th>
                    <th>Moyenne Éval.</th>
                    <th>% Victoires</th>
                    <th>Série (Titulaire)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statsJoueurs as $joueur): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($joueur['prenom'] . " " . $joueur['nom']) ?></strong></td>
                    <td><span class="badge-statut <?= $joueur['statut'] ?>"><?= ucfirst($joueur['statut']) ?></span></td>
                    <td><?= htmlspecialchars($joueur['poste_prefere']) ?></td>
                    <td><?= $joueur['nb_titulaire'] ?></td>
                    <td><?= $joueur['nb_remplacant'] ?></td>
                    <td><?= $joueur['moyenne_eval'] ?> / 5</td>
                    <td><?= $joueur['taux_victoire'] ?>%</td>
                    <td class="serie-nombre"><?= $joueur['selection_consecutive'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>