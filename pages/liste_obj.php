<?php
include '../inc/connexion.php';

$id_categorie = $_GET['categorie'] ?? '';
$nom_objet = $_GET['nom_objet'] ?? '';
$disponible = isset($_GET['disponible']);

$conditions = [];

if (!empty($id_categorie)) {
    $conditions[] = "e_objet.id_categorie = " . (int)$id_categorie;
}

if (!empty($nom_objet)) {
    $nom_objet = mysqli_real_escape_string($dataBase, $nom_objet);
    $conditions[] = "e_objet.nom_objet LIKE '%$nom_objet%'";
}

if ($disponible) {
    $conditions[] = "e_objet.id_objet NOT IN (
        SELECT id_objet FROM e_emprunt WHERE date_retour > CURDATE()
    )";
}


$condition = '';
if (!empty($conditions)) {
    $condition = "WHERE " . implode(" AND ", $conditions);
}

$sql = "
    SELECT e_objet.id_objet, e_objet.nom_objet, e_categorie_objet.nom_categorie, e_membre.nom AS proprietaire,
           e_emprunt.date_retour, e_images_objet.nom_image
    FROM e_objet
    JOIN e_categorie_objet ON e_objet.id_categorie = e_categorie_objet.id_categorie
    JOIN e_membre ON e_objet.id_membre = e_membre.id_membre
    LEFT JOIN (
    SELECT * FROM e_emprunt WHERE date_retour > CURDATE()
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
            <label>Catégorie</label>
            <select name="categorie" class="form-select">
                <option value="">-- Toutes les catégories --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)) {
                    $selected = ($cat['id_categorie'] == $id_categorie) ? 'selected' : '';
                    echo "<option value='{$cat['id_categorie']}' $selected>" . $cat['nom_categorie']. "</option>";
                } ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Nom de l’objet</label>
            <input type="text" class="form-control" name="nom_objet" value="<?= $nom_objet ?? '' ?>">
        </div>

        <div class="col-md-2 mt-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="disponible" id="disponible" <?= $disponible ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponible">Disponible seulement</label>
            </div>
        </div>

        <div class="col-md-2 mt-4">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </div>
</form>


   <div class="row">
<?php while ($obj = mysqli_fetch_assoc($result)): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
    <div class="card-img-top-wrapper">
        <a href="fiche_obj.php?id=<?= $obj['id_objet'] ?>">
            <?php if ($obj['nom_image']): ?>
                <img src="../assets/uploads/<?= $obj['nom_image'] ?>" class="card-img-top" alt="Image de <?= $obj['nom_objet'] ?>">
            <?php else: ?>
                <img src="../assets/images/img.jpg" class="card-img-top" alt="Image par défaut">
            <?php endif; ?>
        </a>
    </div>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">
            <a href="fiche_obj.php?id=<?= $obj['id_objet'] ?>" class="text-decoration-none text-dark">
                <?= $obj['nom_objet'] ?>
            </a>
        </h5>
        <p class="card-text mb-3">
            <strong>Catégorie :</strong> <?= $obj['nom_categorie'] ?><br>
            <strong>Propriétaire :</strong> <?= $obj['proprietaire'] ?><br>
            <strong>Disponibilité :</strong>
            <?= $obj['date_retour'] ? '<span class="text-danger">Emprunté</span>' : '<span class="text-success">Disponible</span>' ?>
        </p>

        <?php if (!$obj['date_retour']): ?>
            <form action="emprunter_obj.php" method="post" class="mt-auto">
                <input type="hidden" name="id_objet" value="<?= $obj['id_objet'] ?>">

                <div class="mb-2">
                    <label for="date_<?= $obj['id_objet'] ?>" class="form-label">Date d'emprunt</label>
                    <input type="date" name="date_emprunt" class="form-control" id="date_<?= $obj['id_objet'] ?>" required>
                </div>

                <div class="mb-2">
                    <label for="duree_<?= $obj['id_objet'] ?>" class="form-label">Durée (en jours)</label>
                    <input type="number" name="duree" class="form-control" id="duree_<?= $obj['id_objet'] ?>" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-down"></i> Emprunter
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

    </div>
<?php endwhile; ?>
</div>

</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
