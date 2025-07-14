<?php
session_start();
require("connexion.php");

if (!isset($_SESSION['id_membre'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_membre = $_SESSION['id_membre'];
$nom_objet = $_POST['nom_objet'];
$id_categorie = $_POST['categorie'];

$sql = sprintf("INSERT INTO e_objet (nom_objet, id_categorie, id_membre) VALUES ('%s', %d, %d)",
    $nom_objet, (int)$id_categorie, (int)$id_membre);

if (!mysqli_query($dataBase, $sql)) {
    die("Erreur insertion objet : " . mysqli_error($dataBase));
}

$id_objet = mysqli_insert_id($dataBase);

$upload_dir = __DIR__ . '/../assets/uploads/';


if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $key => $filename) {
        $tmp_name = $_FILES['images']['tmp_name'][$key];
        $error = $_FILES['images']['error'][$key];
        if ($error === UPLOAD_ERR_OK) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $new_name = uniqid('img_') . '.' . $ext;
            $target_file = $upload_dir . $new_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
               
                $sql_img = sprintf("INSERT INTO e_images_objet (id_objet, nom_image) VALUES (%d, '%s')",
                    (int)$id_objet, $new_name);
                mysqli_query($dataBase, $sql_img);
            }
        }
    }
}

header("Location: ../pages/fiche_obj.php?id=" . $id_objet);
exit();
