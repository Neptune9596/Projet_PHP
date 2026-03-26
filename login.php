
<?php
    session_start();
    $email = $_POST['email'] ?? "";
    $mdp   = $_POST['mdp'] ?? "";
    $role   = $_POST['role'] ?? "";

    $erreurlogin = "";

    if ($email !== "" && $mdp !== "") {
        $url = "https://authks.page.gd/connexion.php";
        $postData = [
            'email' => $email,
            'mdp' => $mdp,
            'role' => $role
        ];

        $ch = curl_init($url);
        //Permet de récupérer la réponse de l'API d'authentification
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Cette option permet d'envoyer les données de mail et mdp
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $reponse = curl_exec($ch);
        var_dump($reponse);
        // On décode le JSON reçu
        $resultat = json_decode($reponse, true);
    if (is_array($resultat) && isset($resultat['status_code'])) {
        // On vérifie le status_code défini par deliver_reponse
        if($resultat['status_code'] === 200) {
            $_SESSION['user_token'] = $resultat['data']; // Le token est dans 'data'
            header("Location: accueil.php");
            exit();
        } else {
            $erreurlogin = "Mauvais identifiants.";
        }
    curl_close($ch);
    }
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $erreurlogin = "Veuillez remplir tous les champs.";
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-login.css">
    <title>Page de connexion</title>
</head>
<body>
<header>
    <nav class="navbar">
        <ul class="nav-list">
        </ul>
    </nav>
</header>
    <h1>Bienvenue sur la page de connexion</h1>
    <div class="form-container">
        <form class="login-form" method="POST" action="">
            <?php
            if ($erreurlogin !== "") {
                echo "<p class='error-message'>$erreurlogin</p>";
            }
            ?>
            <label>Email :</label>
            <input type="text" name="email">
            <label>Mot de passe :</label>
            <input type="password" name="mdp">
            <select name="role" id="role-select">
                <option value="Coach">Coach</option>
                <option value="Entraîneurs">Entraîneurs</option>
                <option value="Directeurs">Directeurs</option>
                <option value="Arbitres">Arbitres</option>
            </select>
            <input type="submit" value="Se connecter">
        </form>
    </div>

</body>
</html>
