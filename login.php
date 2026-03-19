
<?php
    session_start();
    $email = $_POST['email'] ?? "";
    $mdp   = $_POST['mdp'] ?? "";

    $erreurlogin = "";

    if ($email !== "" && $mdp !== "") {
        $url = "https://authks.page.gd/connexion.php";
        $postData = [
            'email' => $email,
            'mdp' => $mdp
        ];

        $ch = curl_init($url);
        //Permet de récupérer la réponse de l'API d'authentification
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Cette option permet d'envoyer les données de mail et mdp
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $reponse = curl_exec($ch);
        curl_close($ch);

        // On décode le JSON reçu
        $resultat = json_decode($reponse, true);

        // On vérifie le status_code défini par deliver_response
        if($resultat['status_code'] === 200) {
            $_SESSION['user_token'] = $resultat['data']; // Le token est dans 'data'
            header("Location: accueil.php");
            exit();
        } else {
            $erreurlogin = "Mauvais identifiants.";
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
            <input type="submit" value="Se connecter">
        </form>
    </div>

</body>
</html>
