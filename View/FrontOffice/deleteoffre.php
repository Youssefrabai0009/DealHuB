<?php
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__ .'/../../Controller/categoriecontroller.php';
$offer = new offrecontroller();
$cat = new catcontroller();
$offer->deleteOffer($_GET['id_offre']);
$list = $offer->listoffers();
$categories = $cat->listcategories();




?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DealHub Vidéos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .video-card {
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .video-card video {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .video-info {
      padding: 10px;
    }
    .navbar {
      background-color: #1e3a8a;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">DealHub</a>
      <div class="ml-auto header-buttons">
        <a class="btn btn-outline-light" href="acceuil.html">Home</a>
        <a class="btn btn-outline-light" href="#">Profile</a>
        <a class="btn btn-outline-danger" href="acceuil.html">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2 class="text-center mb-4">Nos Vidéos de Pitch</h2>

    <div class="d-flex justify-content-center">
      <select class="form-select w-50" id="categoryFilter" onchange="filtrerParCategorie()">
        <option value="all">Toutes les catégories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['libelle_categorie']) ?>"><?= htmlspecialchars($cat['libelle_categorie']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="video-grid mt-4" id="videoContainer">
      <!-- Ici tu peux aussi afficher les vidéos depuis ta base si tu veux -->
      <div class="video-card" data-categorie="Startup">
        <video src="Boarding Ring - notre pitch dans Qui Veut Être Mon Associé.mp4" controls></video>
        <div class="video-info">
          <h5>Startup A</h5>
          <p>Catégorie : Startup</p>
        </div>
      </div>
    </div>
    <div class="mt-5">
  <h3 class="text-center mb-4">Nos Offres</h3>
  <div class="text-end mb-3">
    <a href="addoffre.php" class="btn btn-success">
      <i class="fas fa-plus"></i> Ajouter une Offre
    </a>
  </div>
  <table class="table table-bordered table-hover text-center">
  <thead class="table-dark">
    <tr>
      <th>ID_offre</th>
      <th>Montant</th>
      <th>Date_offre</th>
      <th>Statut</th>
      <th>ID_categorie</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($list as $offre): ?>
      <tr>
        <td><?= htmlspecialchars($offre['id_offre']) ?></td>
        <td><?= htmlspecialchars($offre['montant']) ?> DT</td>
        <td><?= htmlspecialchars($offre['date_offre']) ?></td>
        <td><?= htmlspecialchars($offre['statut']) ?></td>
        <td><?= htmlspecialchars($offre['id_categorie']) ?></td>
        <td>
          <a href="updateoffre.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-sm btn-primary me-2" title="Modifier">
            <i class="fas fa-edit"></i>
          </a>
          <a href="deleteoffre.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
            onclick="return confirm('Supprimer cette offre ?');">
            <i class="fas fa-trash-alt"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
  </div>

  <script>
    function filtrerParCategorie() {
      const selected = document.getElementById("categoryFilter").value.toLowerCase();
      const videos = document.querySelectorAll(".video-card");

      videos.forEach(video => {
        const cat = video.getAttribute("data-categorie").toLowerCase();
        video.style.display = (selected === "all" || selected === cat) ? "block" : "none";
      });
    }
  </script>

</body>
</html>

