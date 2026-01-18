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
    $participations = Participe::getByMatch ($id_match); // recuperer les participants du match
    $disponibles = Joueur::getJoueursDisponiblesPourMatch($id_match); //recuperer liste des joueurs actifs
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'ajouter':
                // ajouter un joueur au match
                if (!empty($_POST['id_joueur'])) {
                    Participe::create(
                        $id_match, 
                        $_POST['id_joueur'], 
                        $_POST['poste'], 
                        $_POST['etat']
                        //L'evaluation sera vide
                    );
                }
                break;

            case 'sauvegarder':
                // modifier une participation existante
                $p = Participe::getById($_POST['id_participation']);
                if ($p) {
                    $p->setPoste($_POST['poste']);
                    $p->setEtat($_POST['etat']);
                    if (isset($_POST['evaluation'])) {
                        $p->setEvaluation($_POST['evaluation']);
                    }
                }
                break;

            case 'supprimer':
                // SUPPRIMER un joueur de la feuille de match
                if (isset($_POST['id_participation'])) {
                    Participe::delete($_POST['id_participation']);
                }
                break;
        }
        header("Location: Modifier_Feuille_de_Match.php?id_match=$id_match");
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
            <div class="form-container">
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
                    <input type="submit" value="sauvegarder">

                    <button type="submit" name="action" value="supprimer" 
                    onclick="return confirm('Retirer ce joueur du match ?')">Supprimer</button>
                </td>
            </form>
            </div>
        </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
<div class="form-container">
  <h3>Ajouter un joueur à la feuille de match</h3>
  <form action="Modifier_Feuille_de_Match.php" method="post" class="joueur-form">

    <input type="hidden" name="id_match" value="<?= $id_match ?>">    
    <select name="id_joueur" required>
        <option value="">-- Sélectionner un joueur à ajouter --</option>
        <?php foreach ($disponibles as $j): ?>
            <option value="<?= $j->getId() ?>">
                <?= htmlspecialchars($j->getNom() . " " . $j->getPrenom()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="poste">
        <option value="gardien">Gardien</option>
        <option value="défenseur">Défenseur</option>
        <option value="milieu">Milieu</option>
        <option value="attaquant">Attaquant</option>
    </select>

    <select name="état">
        <option value="gardien">Titulaire</option>
        <option value="défenseur">Remplaçant</option>
    </select>

    <button type="submit" name="action" value="ajouter">Ajouter au match</button>
</form>
</div>

</body>
</html>
