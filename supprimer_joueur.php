<?php
  require "database.php";
  $pdo = Database::getConnection();
  session_start();
  if (!isset($_SESSION["user_id"])) {
      header("Location: login.php");
  exit();
  }
  if (!isset($_POST["licence"])) {
      exit("Erreur : aucune licence reçue.");
  }
  $licence = $_POST["licence"];
  $check = $pdo->prepare("SELECT id_joueur FROM joueur WHERE numero_licence = ?");
  $check->execute([$licence]);
  $joueur = $check->fetch();
  if (!$joueur) {
      exit("Erreur : joueur introuvable.");
  }
  $checkMatch = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE id_joueur = ?");
  $checkMatch->execute([$joueur["id_joueur"]]);
  $nb_matchs = $checkMatch->fetchColumn();
  if ($nb_matchs > 0) {
      exit("Impossible de supprimer ce joueur : il a déjà participé à un match.");
  }
  $delete = $pdo->prepare("DELETE FROM joueur WHERE numero_licence = ?");
  $delete->execute([$licence]);
  header("Location: liste_joueur.php");
  exit;
?>