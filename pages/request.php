<?php
include "../inc/connexion.php";

$email = $_POST['email'];
$date = $_POST['date_de_naissance'];
$password = $_POST['mdp'];
$nom = $_POST['nom'];

$genre = "Non spécifié";
$ville = "Inconnue";
$image_profil = null;

// Requête SQL
$sql = "
    INSERT INTO e_membre (nom, email, date_de_naissance, mdp, genre, ville, id_image_profil)
    VALUES ('$nom', '$email', '$date', '$password', '$genre', '$ville', NULL)
";

// Exécution
$statement = mysqli_query($dataBase, $sql);

// Redirection ou message d’erreur
if ($statement) {
    header("Location: login.php");
    exit();
} else {
    echo "Erreur lors de l'inscription : " . mysqli_error($dataBase);
}
?>
