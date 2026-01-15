<?php
$host = "localhost";
$user = "root";
$mdp = "";
$nomdb = "football";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nomdb;charset=utf8", $user, $mdp);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Erreur connexion : " . $e->getMessage());
}
?>