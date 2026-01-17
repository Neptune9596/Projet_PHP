<?php
    session_start();
    require "database.php";
    $pdo = Database::getConnection();
    $email = $_POST['email'] ?? "";
    $mdp   = $_POST['mdp'] ?? "";

    $erreurlogin = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($email !== "" && $mdp !== "") {
            $stmt = $pdo->prepare("SELECT * FROM coach WHERE email = ? AND mdp = ?");
            $stmt->execute([$email, $mdp]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["email"] = $user["email"];

                header("Location: accueil.php");
                exit();
            }
            else {
                $erreurlogin = "Les identifiants que vous avez fournis sont incorrects.";
            }
        }
        else {
            $erreurlogin = "Veuillez remplir tous les champs.";
        }
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
