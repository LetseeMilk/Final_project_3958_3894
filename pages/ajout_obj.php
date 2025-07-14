<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header("Location: ../pages/login.php");
    exit();
}
require("../inc/connexion.php");

$categories = mysqli_query($dataBase, "SELECT * FROM e_categorie_objet ORDER BY nom_categorie ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../inc/header.php'; ?>

<div class="container mt-5">
    <h1>Ajouter un nouvel objet</h1>
    <form action="../inc/traitement_ajout_obj.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom de l'objet</label>
            <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="categorie" name="categorie" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Veuillez choisir les images </label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'objet</button>
    </form>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
