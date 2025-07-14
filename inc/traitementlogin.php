<?php 
require("../inc/connexion.php");

function checkLogin($dataBase) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = sprintf("SELECT * FROM e_membre WHERE email='%s' AND mdp='%s'", $email, $password);

    $result = mysqli_query($dataBase, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        session_start();
        $_SESSION['id_membre'] = $row['id_membre']; 
        $_SESSION['nom'] = $row['nom'];
        header("Location: home.php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}

// Appel de la fonction
checkLogin($dataBase);
?>