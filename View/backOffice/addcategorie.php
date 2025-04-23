<?php
include __DIR__.'/../../Controller/categoriecontroller.php';

$error = '';
$catcontroller = new catcontroller();

if (isset($_POST['id_categorie'], $_POST['libelle_categorie'])) {
    $id_categorie = $_POST['id_categorie'];
    $libelle_categorie = $_POST['libelle_categorie'];

    if (!empty($id_categorie) && !empty($libelle_categorie)) {
        // Crée une nouvelle catégorie avec l'ID et le libellé
        $newCategorie = new categorie($id_categorie, $libelle_categorie);
        $catcontroller->addCategorie($newCategorie);
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

if ($error) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter une Catégorie</title>
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
    .erreur-message {
    color: red;
    font-size: 0.9em;
  }
  </style>
</head>
<body>

  <div class="form-container">
    <h1>Ajouter une Catégorie</h1>
    <form action="addcategorie.php" method="POST">
      <label for="id_categorie">ID Catégorie</label>
      <input type="text" id="id_categorie" name="id_categorie" required>
      <span id="erreurId" class="erreur-message"></span>
      <label for="nom_categorie">Nom Catégorie</label>
      <input type="text" id="nom_categorie" name="libelle_categorie" required>
      <span id="erreurNom" class="erreur-message"></span>
      <button type="submit" onclick="validerFormulaire()">Ajouter</button>
    </form>

    <a class="back-link" href="showcategorie.php">← Retour au Dashboard</a>
  </div>
   <script src="controle.js"></script>
</body>
</html>
