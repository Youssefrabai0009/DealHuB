<?php
include __DIR__.'/../../Controller/offrecontroller.php';

$error = '';
$offer = new offrecontroller();

if (isset($_POST['montant'], $_POST['date_offre'], $_POST['statut'], $_POST['id_categorie'])) {
    // Remove the line for id_offre if it's not part of the form
    $montant = $_POST['montant'];
    $date_offre = new DateTime($_POST['date_offre']); // Convert the string to DateTime object
    $statut = $_POST['statut'];
    $id_categorie = $_POST['id_categorie'];
    
    if (!empty($montant) && !empty($date_offre) && !empty($statut) && !empty($id_categorie)) {
        // Crée une nouvelle catégorie avec l'ID et le libellé
        $offre = new offer(null, $montant, $date_offre, $statut, $id_categorie); // Passing null for id_offre if auto-generated
        $offer->addOffer($offre);
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
  <title>Ajouter une offre</title>
  <link rel="stylesheet" href="formcss.css">
</head>
<body>

  <div class="form-container">
    <h2>Ajouter une offre</h2>
    <form action="#" method="POST">
      <div class="form-group">
        <label for="montant">Montant (DT)</label>
        <input type="text" name="montant" id="montant" >
        <span class="error" id="error-montant"></span>
      </div>

      <div class="form-group">
        <label for="date_offre">Date de l'offre</label>
        <input type="date" name="date_offre" id="date_offre">
        <span class="error" id="error-date"></span>
      </div>
      <div class="form-group">
        <label for="statut">Statut</label>
        <select name="statut" id="statut">
    <option value="">Sélectionner le statut</option>
    <option value="en_attente" >En attente</option>
    <option value="acceptée">Acceptée</option>
    <option value="refusée">Refusée</option>
</select>

        <span class="error" id="error-statut"></span>
      </div>

      <div class="form-group">
        <label for="id_categorie">ID Catégorie</label>
        <input type="text" name="id_categorie" id="id_categorie">
        <span class="error" id="error-categorie"></span>
      </div>

      <button type="submit">Ajouter l'offre</button>
      <a class="back-link" href="index.php">← Retour à la page d'acceuil</a>
    </form>
  </div>

  <script src="controleform.js"></script>
</body>
</html>
