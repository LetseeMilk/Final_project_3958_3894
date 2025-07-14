<?php
require("../inc/connexion.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Objet non spécifié.");
}

$id_objet = (int)$_GET['id'];


$sql_obj = "
    SELECT e_objet.nom_objet, e_categorie_objet.nom_categorie, e_membre.nom AS proprietaire
    FROM e_objet
    JOIN e_categorie_objet ON e_objet.id_categorie = e_categorie_objet.id_categorie
    JOIN e_membre ON e_objet.id_membre = e_membre.id_membre
    WHERE e_objet.id_objet = $id_objet
";
$res_obj = mysqli_query($dataBase, $sql_obj);
if (!$obj = mysqli_fetch_assoc($res_obj)) {
    die("Objet introuvable.");
}

$sql_images = "SELECT nom_image FROM e_images_objet WHERE id_objet = $id_objet ORDER BY id_image ASC";
$res_images = mysqli_query($dataBase, $sql_images);

$sql_emprunts = "
    SELECT e_membre.nom AS emprunteur, date_emprunt, date_retour
    FROM e_emprunt
    JOIN e_membre ON e_emprunt.id_membre = e_membre.id_membre
    WHERE id_objet = $id_objet
    ORDER BY date_emprunt DESC
";
$res_emprunts = mysqli_query($dataBase, $sql_emprunts);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche objet - <?= $obj['nom_objet'] ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../inc/header.php'; ?>

<div class="container mt-4">
    <h1><?= $obj['nom_objet'] ?></h1>
    <p><strong>Catégorie :</strong> <?= $obj['nom_categorie'] ?></p>
    <p><strong>Propriétaire :</strong> <?= $obj['proprietaire'] ?></p>

    <div class="row mb-4">
        <?php 
        $hasImages = false;
        while ($img = mysqli_fetch_assoc($res_images)) {
            $hasImages = true;
            ?>
            <div class="col-md-4 mb-3">
                <img src="../assets/uploads/<?= $img['nom_image'] ?>"  class="img-fluid rounded shadow-sm" />
            </div>
        <?php } 
        if (!$hasImages) { ?>
            <div class="col-12">
                <img src="../assets/images/img.jpg" alt="Image par défaut" class="img-fluid rounded shadow-sm" />
            </div>
        <?php } ?>
    </div>

    <h3>Historique des emprunts</h3>
    <?php if (mysqli_num_rows($res_emprunts) === 0): ?>
        <p>Aucun emprunt pour cet objet.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Emprunteur</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($emprunt = mysqli_fetch_assoc($res_emprunts)): ?>
                    <tr>
                        <td><?= $emprunt['emprunteur'] ?></td>
                        <td><?= $emprunt['date_emprunt'] ?></td>
                        <td><?= $emprunt['date_retour'] ? $emprunt['date_retour'] : '<em>Non retourné</em>' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
