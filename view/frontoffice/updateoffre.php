<?php
session_start();
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__.'/../../Controller/categoriecontroller.php';
$cat = new catcontroller();
$categories = $cat->listcategories();
$error = '';
$offer = null;
$offrecontroller = new offrecontroller();

if (isset($_POST['montant'], $_POST['date_offre'], $_POST['statut'], $_POST['id_categorie'], $_GET['id_offre'])) {
    $id_offre = $_GET['id_offre']; // Récupération de l'ID de la catégorie à partir de l'URL
    $statut=$_POST['statut'];
    $montant=$_POST['montant'];
    $date_offre=$_POST['date_offre'];
    // Vérifie si les champs sont non vides
    if (!empty($_POST['montant']) && !empty($_POST['date_offre']) && !empty($_POST['statut']) && !empty($_POST['id_categorie'])) {
        
        // Convertir la date en objet DateTime
        $date_offre = new DateTime($_POST['date_offre']);  // ici on crée un objet DateTime
        
        // Créer un objet offre avec les nouvelles données
        $offer = new offer(
            $id_offre,
            $_POST['montant'],
            $date_offre,           // Objet DateTime ici
            $_POST['statut'],
            $_POST['id_categorie'],
            $_SESSION['user']['id']
        );

        // Appel à la méthode de mise à jour du contrôleur
        $offrecontroller->updateoffer($offer, $id_offre);
        if ($statut == 'acceptée') {
          // Nom, prénom et email de l'investisseur
          $investisseurName = 'Snoussi Rami';  // Nom et prénom saisis manuellement
          $investisseurEmail = 'rami.snoussi@esprit.tn';  // Email de l'investisseur

          // Détails de l'offre
          $offerDetails = [
              'montant' => $montant,
              'date_offre' => $date_offre->format('Y-m-d'), // Récupère la catégorie par ID
          ];

          // Appeler la fonction pour envoyer l'email
          $offrecontroller->sendOfferAcceptedEmail($investisseurName, $investisseurEmail, $offerDetails);
      } else if ($statut == 'refusée') {
        // Nom, prénom et email de l'investisseur
        $investisseurName = 'Snoussi Rami';  // Nom et prénom saisis manuellement
        $investisseurEmail = 'rami.snoussi@esprit.tn';  // Email de l'investisseur

        // Détails de l'offre
        $offerDetails = [
            'montant' => $montant,
            'date_offre' => $date_offre->format('Y-m-d'), // Récupère la catégorie par ID
        ];

        // Appeler la fonction pour envoyer l'email
        $offrecontroller->sendOfferrefuseEmail($investisseurName, $investisseurEmail, $offerDetails);
        $offrecontroller->deleteOffer($_GET['id_offre']);
    }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

if ($error) {
    echo "<p style='color:red;'>$error</p>";
}

if (isset($_GET['id_offre'])) {
    // Récupération des informations de la catégorie à modifier
    $offer = $offrecontroller->showoffer($_GET['id_offre']); // Passe l'ID pour obtenir la catégorie à éditer
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier une offre</title>
  <link rel="stylesheet" href="formcss.css">
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
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">DealHub</a>
      <div class="navbar-nav ml-auto">
        <a class="nav-link" href="acceuil.html">Home</a>
        <a class="nav-link" href="#">Profile</a>
        <a class="nav-link" href="acceuil.html">Logout</a>
      </div>
    </div>
  </nav>
  <div class="form-container">
    <h2>Modifier une offre</h2>
    <form action="#" method="POST">
      <div class="form-group">
        <label for="montant">Montant (DT)</label>
        <input type="text" name="montant" id="montant" value="<?php echo $offer ? $offer['montant'] : ''; ?>">
        <span class="error" id="error-montant"></span>
      </div>

      <div class="form-group">
        <label for="date_offre">Date de l'offre</label>
        <input type="date" name="date_offre" id="date_offre" value="<?php echo $offer ? $offer['date_offre'] : ''; ?>">
        <span class="error" id="error-date"></span>
      </div>
      <div class="form-group">
        <label for="statut">Statut</label>
        <select name="statut" id="statut">
    <option value="" <?php echo empty($offre['statut']) ? 'selected' : '' ?>>Sélectionner le statut</option>
    <option value="en_attente" <?php echo ($offre['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
    <option value="acceptée" <?php echo ($offre['statut'] ?? '') === 'acceptée' ? 'selected' : '' ?>>Acceptée</option>
    <option value="refusée" <?php echo ($offre['statut'] ?? '') === 'refusée' ? 'selected' : '' ?>>Refusée</option>
</select>

        <span class="error" id="error-statut"></span>
      </div>

      <div class="form-group">
  <label for="id_categorie">Catégorie</label>
  <select name="id_categorie" id="id_categorie" class="form-control">
    <?php foreach ($categories as $categorie): ?>
      <option value="<?= $categorie['id_categorie'] ?>"
        <?= ($offer && $offer['id_categorie'] == $categorie['id_categorie']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($categorie['libelle_categorie']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <span class="error" id="error-categorie"></span>
</div>

      

      <button type="submit">Modifier l'offre</button>
      <a class="back-link" href="index.php">← Retour à la page d'acceuil</a>
    </form>
  </div>

  <script src="controleform.js"></script>
</body>
</html>