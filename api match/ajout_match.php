<?php
// API et page pour ajouter un match
session_start();
require "database.php";
require "Partie.php";
require "api match/match.php";
$pdo = Database::getConnection();
Partie::setPdo($pdo);

$erreur = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        // requête API JSON
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['date_match'], $data['heure'], $data['nom_adversaire'], $data['lieu_rencontre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs manquants']);
            exit;
        }
        try {
            Partie::create($data['date_match'], $data['heure'], $data['nom_adversaire'], $data['lieu_rencontre']);
            http_response_code(201);
            echo json_encode(['message' => 'Match créé avec succès']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur', 'details' => $e->getMessage()]);
        }
        exit;
    } else {
        // soumission du formulaire web
        $date = $_POST['date_match'];
        $heure = $_POST['heure'];
        $nom_adv = $_POST['nom_adversaire'];
        $lieu = $_POST['lieu_rencontre'];
        try {
            Partie::create($date, $heure, $nom_adv, $lieu);
            $success = 'Match créé avec succès';
        } catch (Exception $e) {
            $erreur = 'Erreur lors de la création : ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Créer un match</title>
</head>
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

<body>

<h3>Créer un match</h3>

<?php if ($erreur): ?>
    <p style="color:red; font-weight:bold; text-align:center;"><?= $erreur ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green; font-weight:bold; text-align:center;"><?= $success ?></p>
<?php endif; ?>

<div class="form-container">
<form class="joueur-form" method="post">
    <label>Date du match :</label>
    <input type="date" name="date_match" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
    <label>Heure du match :</label>
    <input type="time" name="heure" required>
    <label>Nom adversaire :</label>
    <input type="text" name="nom_adversaire" required>
    <label>Lieu de la rencontre :</label>
    <input type="text" name="lieu_rencontre" required>
    <input type="submit" value="Créer le match" class="submit-button">
</form>


</div>
</body>
</html>
