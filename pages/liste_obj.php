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
    SELECT e_objet.nom_objet, e_categorie_objet.nom_categorie, e_membre.nom AS proprietaire,
           e_emprunt.date_retour
    FROM e_objet
    JOIN e_categorie_objet ON e_objet.id_categorie = e_categorie_objet.id_categorie
    JOIN e_membre ON e_objet.id_membre = e_membre.id_membre
    LEFT JOIN (
        SELECT * FROM e_emprunt 
        WHERE date_retour IS NULL
    ) AS e_emprunt ON e_emprunt.id_objet = e_objet.id_objet
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

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Objet</th>
                <th>Catégorie</th>
                <th>Propriétaire</th>
                <th>Date de retour (si emprunté)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($obj = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $obj['nom_objet'] ?></td>
                    <td><?= $obj['nom_categorie']?></td>
                    <td><?= $obj['proprietaire'] ?></td>
                    <td><?= $obj['date_retour'] ? $obj['date_retour']: '<em>Disponible</em>' ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
