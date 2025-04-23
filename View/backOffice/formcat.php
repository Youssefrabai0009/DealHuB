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
  </style>
</head>
<body>

  <div class="form-container">
    <h1>Ajouter une Catégorie</h1>
    <form action="#" method="POST">
      <label for="id_categorie">ID Catégorie</label>
      <input type="text" id="id_categorie" name="id_categorie" >

      <label for="nom_categorie">Nom Catégorie</label>
      <input type="text" id="nom_categorie" name="nom_categorie" >

      <button type="submit">Ajouter</button>
    </form>

    <a class="back-link" href="dash.html">← Retour au Dashboard</a>
  </div>

</body>
</html>
