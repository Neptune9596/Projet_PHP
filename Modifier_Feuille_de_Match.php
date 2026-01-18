<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require "database.php";
    require "Joueur.php";
    require "Participe.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}      
    Joueur::setPdo($pdo);
    Participe::setPdo($pdo);
    $id_match = $_GET['id_match'];
    $participations = Participe::getByMatch ($id_match); 
    
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] != 'delete') {
    $p = Participe::getById($_POST['id_participation']);
    if ($p) {
        $p->setPoste($_POST['poste']);
        $p->setEtat($_POST['etat']);
        // Optionnel : ajouter l'évaluation si le match est fini
        if(isset($_POST['evaluation'])) $p->setEvaluation($_POST['evaluation']);
        
        header("Location: Modifier_Feuille_de_Match.php?id_match=$id_match");
        exit;
    }
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

<h3>Gérer les joueurs de cette match</h3>

<table>
  <thead>
        <tr>
            <th>Joueur</th>
            <th>Poste</th>
            <th>État</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
  </thead>
  <tbody>
<?php foreach ($participations as $p): 
            $j = Joueur::getJoueurById($p->getIdJoueur());
        ?>
        <tr>
            <form class="joueur-form" method="post">
                <td><?= htmlspecialchars($j->getPrenom() . " " . $j->getNom()) ?></td>
                
                <td>
                    <select name="poste">
                        <option value="gardien" <?= $p->getPoste() == "Gardien" ? "selected" : "" ?>>Gardien</option>
                        <option value="défenseur" <?= $p->getPoste() == "Défenseur" ? "selected" : "" ?>>Défenseur</option>
                        <option value="milieu" <?= $p->getPoste() == "Milieu" ? "selected" : "" ?>>Milieu</option>
                        <option value="attaquant" <?= $p->getPoste() == "Attaquant" ? "selected" : "" ?>>Attaquant</option>
                    </select>
                </td>

                <td>
                    <select name="etat">
                        <option value="titulaire" <?= $p->getEtat() == "Titulaire" ? "selected" : "" ?>>Titulaire</option>
                        <option value="remplaçant" <?= $p->getEtat() == "Remplaçant" ? "selected" : "" ?>>Remplaçant</option>
                    </select>
                </td>

                <td>
                    <input type="number" name="evaluation" value="<?= $p->getEvaluation() ?>" min="0" max="5" style="width: 50px;">
                </td>

                <td>
                    <input type="hidden" name="id_participation" value="<?= $p->getId() ?>">
                    <input type="submit" value="Sauvegarder">
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
