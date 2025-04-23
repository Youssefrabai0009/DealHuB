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
    <form action="" method="POST">
      <div class="form-group">
        <label for="montant">Montant (€)</label>
        <input type="text" name="montant" id="montant">
        <span class="error" id="error-montant"></span>
      </div>

      <div class="form-group">
        <label for="date_offre">Date de l'offre</label>
        <input type="date" name="date_offre" id="date_offre" >
        <span class="error" id="error-date"></span>
      </div>

      <div class="form-group">
        <label for="statut">Statut</label>
        <select name="statut" id="statut" >
          <option value="">-- Choisir un statut --</option>
          <option value="en_attente">En attente</option>
          <option value="acceptée">Acceptée</option>
          <option value="refusée">Refusée</option>
        </select>
        <span class="error" id="error-statut"></span>
      </div>

      <button type="submit">Ajouter l'offre</button>
    </form>
  </div>
   <script src="form.js"></script>
</body>
</html>
