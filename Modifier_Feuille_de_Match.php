<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require "database.php";
    require "Joueur.php";
    require "Participe.php";
    require "Partie.php";
    $pdo = Database::getConnection();
    if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}      
    Joueur::setPdo($pdo);
    Participe::setPdo($pdo);
    // On cherche l'ID dans GET ou POST, sinon on met null
    $id_match = $_GET['id_match'] ?? $_POST['id_match'] ?? null;

    // Si on n'a pas d'ID, on ne peut pas afficher la page
    if (!$id_match) {
    header("Location: liste_match.php"); 
    exit();
    }

    $participations = Participe::getByMatch ($id_match); // recuperer les participants du match
    $disponibles = Joueur::getJoueursDisponiblesPourMatch($id_match); //recuperer liste des joueurs actifs
    $match = Partie::getMatchById($id_match);

    if ($match ->getDate() < date("Y-m-d")) {
        // Rediriger si le match est passé
        header("Location: feuille_match.php");
        exit();
    }
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'Enregistrer':
                // ajouter un joueur au match
                if (!empty($_POST['id_joueur'])) {
                    Participe::create(
                        $id_match, 
                        $_POST['id_joueur'], 
                        $_POST['poste'], 
                        $_POST['état']
                        //L'evaluation sera vide
                    );
                }
                break;

            case 'Sauvegarder':
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

<h3>Titulaires</h3>
<table>
    <thead>
        <tr>
            <th>Joueur</th>
            <th>Poste</th>
            <th>Note</th>
            <th>Etat</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($participations as $p): 
            if (strtolower($p->getEtat()) == "titulaire"): // Filtre Titulaires
                $j = Joueur::getJoueurById($p->getIdJoueur());
        ?>
        <tr>
            <form method="post" action="Modifier_Feuille_de_Match.php?id_match=<?= $id_match ?>">
                <td><?= htmlspecialchars($j->getPrenom() . " " . $j->getNom()) ?></td>
                <td>
                    <select name="poste">
                        <option value="gardien" <?= strtolower($p->getPoste()) == "gardien" ? "selected" : "" ?>>Gardien</option>
                        <option value="défenseur" <?= strtolower($p->getPoste()) == "défenseur" ? "selected" : "" ?>>Défenseur</option>
                        <option value="milieu" <?= strtolower($p->getPoste()) == "milieu" ? "selected" : "" ?>>Milieu</option>
                        <option value="attaquant" <?= strtolower($p->getPoste()) == "attaquant" ? "selected" : "" ?>>Attaquant</option>
                    </select>
                    <input type="hidden" name="etat" value="titulaire">
                </td>
                <td>
                    <input type="number" name="evaluation" value="<?= $p->getEvaluation() ?>" min="0" max="5" style="width: 50px;">
                </td>
                <td>
                <select name="etat">
                    <option value="titulaire" <?= strtolower($p->getEtat()) == "titulaire" ? "selected" : "" ?>>Titulaire</option>
                    <option value="remplaçant" <?= (strtolower($p->getEtat()) == "remplaçant" || strtolower($p->getEtat()) == "remplacant") ? "selected" : "" ?>>Remplaçant</option>
                </select>
                </td>
                <td>
                    <input type="hidden" name="id_participation" value="<?= $p->getId() ?>">
                    <input type="hidden" name="id_match" value="<?= $id_match ?>">
                    <input type="submit" name="action" value="Sauvegarder">
                    <button type="submit" name="action" value="supprimer" onclick="return confirm('Retirer ?')">❌</button>
                </td>
            </form>
        </tr>
        <?php endif; endforeach; ?>
    </tbody>
</table>

<h3>Remplaçants</h3>
<table>
    <thead>
        <tr>
            <th>Joueur</th>
            <th>Poste</th>
            <th>Note</th>
            <th>Etat</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($participations as $p): 
            // Filtre Remplaçants (on vérifie avec et sans accent par sécurité)
            $etat = strtolower($p->getEtat());
            if ($etat == "remplaçant" || $etat == "remplacant"): 
                $j = Joueur::getJoueurById($p->getIdJoueur());
        ?>
        <tr>
            <form method="post" action="Modifier_Feuille_de_Match.php?id_match=<?= $id_match ?>">
                <td><?= htmlspecialchars($j->getPrenom() . " " . $j->getNom()) ?></td>
                <td>
                    <select name="poste">
                        <option value="gardien" <?= strtolower($p->getPoste()) == "gardien" ? "selected" : "" ?>>Gardien</option>
                        <option value="défenseur" <?= strtolower($p->getPoste()) == "défenseur" ? "selected" : "" ?>>Défenseur</option>
                        <option value="milieu" <?= strtolower($p->getPoste()) == "milieu" ? "selected" : "" ?>>Milieu</option>
                        <option value="attaquant" <?= strtolower($p->getPoste()) == "attaquant" ? "selected" : "" ?>>Attaquant</option>
                    </select>
                    <input type="hidden" name="etat" value="remplaçant">
                </td>
                <td>
                    <input type="number" name="evaluation" value="<?= $p->getEvaluation() ?>" min="0" max="5" style="width: 50px;">
                </td>
                <td>
                <select name="etat">
                    <option value="titulaire" <?= strtolower($p->getEtat()) == "titulaire" ? "selected" : "" ?>>Titulaire</option>
                    <option value="remplaçant" <?= (strtolower($p->getEtat()) == "remplaçant" || strtolower($p->getEtat()) == "remplacant") ? "selected" : "" ?>>Remplaçant</option>
                </select>
                 </td>
                <td>
                    <input type="hidden" name="id_participation" value="<?= $p->getId() ?>">
                    <input type="hidden" name="id_match" value="<?= $id_match ?>">
                    <input type="submit" name="action" value="Sauvegarder">
                    <button type="submit" name="action" value="supprimer" onclick="return confirm('Retirer ?')">❌</button>
                </td>
            </form>
        </tr>
        <?php endif; endforeach; ?>
    </tbody>
</table>




  <h3>Ajouter un joueur à la feuille de match</h3>

  <div class="form-container">
  <form action="Modifier_Feuille_de_Match.php?id_match=<?= $id_match ?>" method="post" class="joueur-form">

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
        <option value="Titulaire">Titulaire</option>
        <option value="Remplaçant">Remplaçant</option>
    </select>

    <input type="submit" name="action" value="Enregistrer">
</form>
</div>

</body>
</html>
