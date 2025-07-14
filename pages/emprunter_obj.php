<?php
session_start();
include '../inc/connexion.php';

if (!isset($_SESSION['id_membre'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_membre = $_SESSION['id_membre'];
$id_objet = (int)$_POST['id_objet'];
$date_emprunt = $_POST['date_emprunt'];
$duree = (int)$_POST['duree'];

$date_retour = date('Y-m-d', strtotime($date_emprunt . " +$duree days"));

$sql = "INSERT INTO e_emprunt (id_objet, id_membre, date_emprunt, date_retour)
        VALUES ($id_objet, $id_membre, '$date_emprunt', '$date_retour')";

if (!mysqli_query($dataBase, $sql)) {
    die("Erreur lors de l'emprunt : " . mysqli_error($dataBase));
}

header("Location: liste_obj.php");
exit();
