<?php 
require("connection.php");

function checkLogin($dataBase) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = sprintf("SELECT * FROM Membre WHERE email='%s' AND motdepasse='%s'", $email, $password);

    $result = mysqli_query($dataBase, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id_membre'] = $row['id_membre']; 
        $_SESSION['Nom'] = $row['Nom']; 
        header("Location: ../pages/home.php");
        exit();
    } else {
        header("Location: ../pages/login.php?error=1");
        exit();
    }
}

?>
