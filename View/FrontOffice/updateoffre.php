<?php
include __DIR__.'/../../Controller/offrecontroller.php';

$error = '';
$offer = null;
$offrecontroller = new offrecontroller();

if (isset($_POST['montant'], $_POST['date_offre'], $_POST['statut'], $_POST['id_categorie'], $_GET['id_offre'])) {
    $id_offre = $_GET['id_offre']; // Récupération de l'ID de la catégorie à partir de l'URL
    
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
            $_POST['id_categorie']
        );

        // Appel à la méthode de mise à jour du contrôleur
        $offrecontroller->updateoffer($offer, $id_offre);
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
</head>
<body>

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
        <label for="id_categorie">ID Catégorie</label>
        <input type="text" name="id_categorie" id="id_categorie" value="<?php echo $offer ? $offer['id_categorie'] : ''; ?>">
        <span class="error" id="error-categorie"></span>
      </div>

      <button type="submit">Modifier l'offre</button>
      <a class="back-link" href="index.php">← Retour à la page d'acceuil</a>
    </form>
  </div>

  <script src="controleform.js"></script>
</body>
</html>