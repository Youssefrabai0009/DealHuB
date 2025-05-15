<?php
session_start();
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__.'/../../Controller/categoriecontroller.php';
$catController = new catcontroller();
$categories = $catController->listCategories(); // cette méthode doit retourner toutes les catégories


$error = '';
$offer = new offrecontroller();

if (isset($_POST['montant'], $_POST['date_offre'], $_POST['id_categorie'])) {
    // Remove the line for id_offre if it's not part of the form
    $montant = $_POST['montant'];
    $date_offre = new DateTime($_POST['date_offre']); // Convert the string to DateTime object
    $statut = "en_attente";
    $id_categorie = $_POST['id_categorie'];
    
    if (!empty($montant) && !empty($date_offre) && !empty($statut) && !empty($id_categorie)) {
        // Crée une nouvelle catégorie avec l'ID et le libellé
        $offre = new offer(null, $montant, $date_offre, $statut, $id_categorie, $_SESSION['user']['id']); // Passing null for id_offre if auto-generated
        $offer->addOffer($offre);
        if ($statut == 'en_attente') {
          // Nom, prénom et email de l'investisseur
          $investisseurName = 'Snoussi Rami';  // Nom et prénom saisis manuellement
          $investisseurEmail = 'rami.snoussi@esprit.tn';  // Email de l'investisseur

          // Détails de l'offre
          $offerDetails = [
              'montant' => $montant,
              'date_offre' => $date_offre->format('Y-m-d'), // Récupère la catégorie par ID
          ];

          // Appeler la fonction pour envoyer l'email
          $offer->sendOfferEmail($investisseurName, $investisseurEmail, $offerDetails);
      }
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter une offre</title>
  <!-- Inclure Bootstrap pour la mise en page et le style -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Styling pour la navbar */
    .navbar {
      background-color: #2F1A4A;
    }
    .navbar-brand {
      color: #F5F2F6;
    }
    .navbar-nav .nav-link {
      color: #F5F2F6;
    }
    .navbar-nav .nav-link:hover {
      color: #A093AF;
    }

    /* Styling du formulaire */
    .form-container {
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #F5F2F6;
    }

    .form-container h2 {
      margin-bottom: 20px;
      color: #2F1A4A;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: bold;
      color: #2F1A4A;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #A093AF;
      border-radius: 5px;
    }

    .form-group .error {
      color: red;
      font-size: 0.8em;
    }

    .form-container button {
      background-color: #2F1A4A;
      color: #F5F2F6;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: #A093AF;
    }

    .back-link {
      display: inline-block;
      margin-top: 10px;
      color: #2F1A4A;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">DealHub</a>
      <div class="navbar-nav ml-auto">
        <a class="nav-link" href="acceuil.html">Home</a>
        <a class="nav-link" href="#">Profile</a>
        <a class="nav-link" href="acceuil.html">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Contenu du formulaire -->
  <div class="container">
    <div class="form-container">
      <h2>Ajouter une offre</h2>
      <form action="#" method="POST">
        <div class="form-group">
          <label for="montant">Montant (DT)</label>
          <input type="text" name="montant" id="montant">
          <span class="error" id="error-montant"></span>
        </div>

        <div class="form-group">
          <label for="date_offre">Date de l'offre</label>
          <input type="date" name="date_offre" id="date_offre">
          <span class="error" id="error-date"></span>
        </div>

        <div class="form-group">
  <label for="statut">Statut</label>
  <input type="text" name="statut_display" value="en_attente" disabled>
  <input type="hidden" name="statut" value="en_attente">
</div>

        <div class="form-group">
          <label for="id_categorie">Catégorie</label>
          <select name="id_categorie" id="id_categorie">
            <option value="">Sélectionner une catégorie</option>
            <?php foreach ($categories as $categorie): ?>
              <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>">
                <?= htmlspecialchars($categorie['libelle_categorie']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <span class="error" id="error-categorie"></span>
        </div>

        <button type="submit">Ajouter l'offre</button>
        <a class="back-link" href="index.php">← Retour à la page d'acceuil</a>
      </form>
    </div>
  </div>

  <script src="controleform.js"></script>
</body>
</html>
