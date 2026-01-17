<?php
  session_start();
  require "database.php";
  $pdo = Database::getConnection();
  if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

  if (!isset($_POST["id_match"])) {
      exit("Erreur : aucun match reçu.");
  }
  $id_match = intval($_POST["id_match"]);
  $check = $pdo->prepare("SELECT id_match, date_match FROM matchs WHERE id_match = ?");
  $check->execute([$id_match]);
  $match = $check->fetch();
  if (!$match) {
      exit("Erreur : match introuvable.");
  }
  if ($match["date_match"] < date("Y-m-d")) {
      exit("Impossible de supprimer ce match : il a déjà eu lieu.");
  }
  $delete = $pdo->prepare("DELETE FROM matchs WHERE id_match = ?");
  $delete->execute([$id_match]);
  header("Location: liste_match.php");
  exit();
?>
