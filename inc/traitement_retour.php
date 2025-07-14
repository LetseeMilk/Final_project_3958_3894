<?php
session_start();
require("../inc/connexion.php");

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_emprunt = (int) $_POST['id_emprunt'];
    $etat_retour = $_POST['etat_retour'];
    
    $query = "SELECT * FROM e_emprunt WHERE id_emprunt = $id_emprunt AND id_membre = " . $_SESSION['id_membre'];
    $result = mysqli_query($dataBase, $query);
    
    if (mysqli_num_rows($result) === 1) {
        $emprunt = mysqli_fetch_assoc($result);
        
        if ($emprunt['date_retour']) {
            $_SESSION['erreur'] = "Cet objet a déjà été retourné.";
            header('Location: profil.php');
            exit();
        }

        $date_retour = date('Y-m-d H:i:s');
        $update = "UPDATE e_emprunt SET date_retour = '$date_retour', etat_retour = '$etat_retour' 
                   WHERE id_emprunt = $id_emprunt";
        
        if (mysqli_query($dataBase, $update)) {
            $_SESSION['message'] = "L'objet a été retourné avec succès.";
        } else {
            $_SESSION['erreur'] = "Erreur lors du retour de l'objet: " . mysqli_error($dataBase);
        }
    } else {
        $_SESSION['erreur'] = "Emprunt non trouvé ou non autorisé.";
    }
    
    header('Location: profil.php');
    exit();
}
?>