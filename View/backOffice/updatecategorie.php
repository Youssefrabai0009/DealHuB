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
    <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #1e3a8a;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #1e3a8a;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3b4cca;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #1e3a8a;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
    <div class="form-container">
        <h1>Modifier une Catégorie</h1>
        <form action="#" method="POST">
            <label for="id_categorie">ID Catégorie</label> 
            <input type="text" id="id_categorie" name="id_categorie" value="<?php echo $categorie ? $categorie['id_categorie'] : ''; ?>">

            <label for="nom_categorie">Nom Catégorie</label>
            <input type="text" id="nom_categorie" name="libelle_categorie" value="<?php echo $categorie ? $categorie['libelle_categorie'] : ''; ?>" >

            <button type="submit">Mettre à jour</button>
        </form>

        <a class="back-link" href="showcategorie.php">← Retour au Dashboard</a>
    </div>

</body>
</html>
