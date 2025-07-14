<?php 
require("../inc/connexion.php");

function checkLogin($dataBase) {
    $email = $_POST['email'];
    $password = $_POST['mdp'];

    $sql = sprintf("SELECT * FROM e_membre WHERE email='%s'", $email);
    $result = mysqli_query($dataBase, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['mdp'] === $password) {
            session_start();
            $_SESSION['id_membre'] = $row['id_membre']; 
            $_SESSION['nom'] = $row['nom'];
            header("Location: ../pages/liste_obj.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Utilisateur non trouvé.";
    }

    session_start();
    $_SESSION['login_error'] = $error;
    header("Location: ../pages/login.php");
    exit();
}

checkLogin($dataBase);
?>
