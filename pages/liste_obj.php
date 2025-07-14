<?php
include '../inc/connexion.php';

$id_categorie = '';
if (isset($_GET['categorie'])) {
    $id_categorie = $_GET['categorie'];
}

$condition = '';
if ($id_categorie !== '') {
    $condition = "WHERE e_objet.id_categorie = $id_categorie";
}

$sql = "
    SELECT e_objet.id_objet, e_objet.nom_objet, e_categorie_objet.nom_categorie, e_membre.nom AS proprietaire,
           e_emprunt.date_retour, e_images_objet.nom_image
    FROM e_objet
    JOIN e_categorie_objet ON e_objet.id_categorie = e_categorie_objet.id_categorie
    JOIN e_membre ON e_objet.id_membre = e_membre.id_membre
    LEFT JOIN (
        SELECT * FROM e_emprunt WHERE date_retour IS NULL
    ) AS e_emprunt ON e_emprunt.id_objet = e_objet.id_objet
    LEFT JOIN (
        SELECT id_objet, MIN(id_image) AS min_id FROM e_images_objet GROUP BY id_objet
    ) AS img_ids ON img_ids.id_objet = e_objet.id_objet
    LEFT JOIN e_images_objet ON e_images_objet.id_objet = img_ids.id_objet AND e_images_objet.id_image = img_ids.min_id
    $condition
    ORDER BY e_objet.nom_objet ASC
";


$result = mysqli_query($dataBase, $sql);


$categories = mysqli_query($dataBase, "SELECT * FROM e_categorie_objet");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../inc/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Liste des objets</h1>

 <form method="get" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <select name="categorie" class="form-select">
                <option value="">-- Toutes les catégories --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)) {
                    echo '<option value="' . $cat['id_categorie'] . '"';
                    if ($cat['id_categorie'] == $id_categorie) {
                        echo ' selected';
                    }
                    echo '>' . $cat['nom_categorie'] . '</option>';
                } ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </div>
</form>

   <div class="row">
<?php while ($obj = mysqli_fetch_assoc($result)): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <a href="fiche_obj.php?id=<?= $obj['id_objet'] ?>" class="text-decoration-none text-dark">
                <?php if ($obj['nom_image']): ?>
                    <img src="../assets/uploads/<?= htmlspecialchars($obj['nom_image']) ?>" class="card-img-top" alt="Image de <?= htmlspecialchars($obj['nom_objet']) ?>">
                <?php else: ?>
                    <img src="../assets/images/img.jpg" class="card-img-top" alt="Image par défaut">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($obj['nom_objet']) ?></h5>
                    <p class="card-text">
                        <strong>Catégorie :</strong> <?= htmlspecialchars($obj['nom_categorie']) ?><br>
                        <strong>Propriétaire :</strong> <?= htmlspecialchars($obj['proprietaire']) ?><br>
                        <strong>Disponibilité :</strong>
                        <?= $obj['date_retour'] ? 'Emprunté jusqu’au ' . htmlspecialchars($obj['date_retour']) : '<span class="text-success">Disponible</span>' ?>
                    </p>
                </div>
            </a>
        </div>
    </div>
<?php endwhile; ?>
</div>

</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
