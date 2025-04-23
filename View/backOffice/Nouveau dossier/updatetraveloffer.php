<?php
include __DIR__.'/../../Controller/categoriecontroller.php';

$error = '';
$categorie = null;
$catcontroller = new catcontroller();

if (isset($_POST['libelle_categorie'], $_GET['id_categorie'])) {
    $id_categorie = $_GET['id_categorie']; // Récupération de l'ID de la catégorie à partir de l'URL
    if (!empty($_POST['libelle_categorie'])) {
        // Créer un objet catégorie avec les nouvelles données
        $categorie = new categorie(
            $id_categorie,  // L'ID de la catégorie que l'on veut mettre à jour
            $_POST['libelle_categorie'] ?? ''
        );

        // Appel à la méthode de mise à jour du contrôleur
        $catcontroller->updateCategorie($categorie, $id_categorie);
    } else {
        $error = "Le nom de la catégorie est obligatoire.";
    }
}

if ($error) {
    echo "<p style='color:red;'>$error</p>";
}

if (isset($_GET['id_categorie'])) {
    // Récupération des informations de la catégorie à modifier
    $categorie = $catcontroller->showcategorie($_GET['id_categorie']); // Passe l'ID pour obtenir la catégorie à éditer
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Catégorie</title>
</head>
<body>
    <div class="form-container">
        <h1>Modifier une Catégorie</h1>
        <form action="#" method="POST">
            <label for="id_categorie">ID Catégorie</label>
            <input type="text" id="id_categorie" name="id_categorie" value="<?php echo $categorie ? $categorie->getIdCategorie() : ''; ?>" readonly>

            <label for="nom_categorie">Nom Catégorie</label>
            <input type="text" id="nom_categorie" name="libelle_categorie" value="<?php echo $categorie ? $categorie->getLibelleCategorie() : ''; ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>

        <a class="back-link" href="dash.html">← Retour au Dashboard</a>
    </div>

</body>
</html>
