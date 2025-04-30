<?php
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__.'/../../Controller/categoriecontroller.php';

$offer = new offrecontroller();
$cat = new catcontroller();
$categories = $cat->listcategories();
$list = $offer->listoffers();
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
    body {
      background-color: #F5F2F6; /* Fond clair */
      color: #2F1A4A; /* Texte sombre */
    }

    .navbar {
      background-color: #2F1A4A; /* Couleur du fond de la navbar */
    }

    .navbar a {
      color: #F5F2F6 !important; /* Liens de la navbar en blanc */
    }

    .navbar a:hover {
      color: #A093AF !important; /* Couleur des liens au survol */
    }

    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .video-card {
      background-color: #FFFFFF; /* Fond blanc des cartes vidéo */
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border: 1px solid #A093AF; /* Bordure subtile autour des vidéos */
    }

    .video-card:hover {
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .video-card video {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .video-info {
      padding: 10px;
    }

    .video-info h5 {
      color: #2F1A4A;
    }

    .video-info p {
      color: #846CA0; /* Couleur plus douce pour les catégories */
    }

    .btn-success {
      background-color: #A093AF;
      border-color: #A093AF;
    }

    .btn-success:hover {
      background-color: #846CA0;
      border-color: #846CA0;
    }

    .btn-primary {
      background-color: #2F1A4A;
      border-color: #2F1A4A;
    }

    .btn-primary:hover {
      background-color: #846CA0;
      border-color: #846CA0;
    }

    .btn-danger {
      background-color: #F5F2F6;
      border-color: #A093AF;
    }

    .btn-danger:hover {
      background-color: #847C84;
      border-color: #847C84;
    }

    .table-dark {
      background-color: #2F1A4A; /* Fond sombre pour le tableau */
    }

    .table-dark th {
      color: #F5F2F6; /* Texte des en-têtes en clair */
    }

    .table-dark td {
      color: #846CA0; /* Couleur des cellules du tableau */
    }

    #categoryFilter {
      background-color: #F5F2F6; /* Fond clair du filtre */
      color: #2F1A4A;
      border: 1px solid #A093AF;
    }

    #categoryFilter:focus {
      border-color: #846CA0;
    }

    .header-buttons a {
      color: #F5F2F6 !important;
    }

    .header-buttons a:hover {
      color: #A093AF !important;
    }

  </style>
</head>
<body>

  <!-- Navbar -->
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

  <!-- Page Container -->
  <div class="container mt-4">
    <h2 class="text-center mb-4">Nos Vidéos de Pitch</h2>

    <!-- Filtre Catégorie -->
    <div class="d-flex justify-content-center">
      <select class="form-select w-50" id="categoryFilter" onchange="filtrerParCategorie()">
        <option value="all">Toutes les catégories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['libelle_categorie']) ?>">
            <?= htmlspecialchars($cat['libelle_categorie']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      

    </div>
    <input type="text" id="searchInput" class="form-control w-50 mt-3" placeholder="Rechercher une vidéo..." oninput="rechercherVideo()">
    <!-- Grille de Vidéos -->
    <div class="video-grid mt-4" id="videoContainer">
      <!-- Exemple Vidéo 1 -->
      <div class="video-card" data-categorie="chefaa">
        <video src="tech.mp4" controls></video>
        <div class="video-info">
          <h5>Pitch Startup 1</h5>
          <p>Catégorie : chefaa</p>
          <a href="addoffre.php?categorie=chefaa" class="btn btn-success mt-2">
            <i class="fas fa-plus"></i> Ajouter une offre
          </a>
        </div>
      </div>

      <!-- Exemple Vidéo 2 -->
      <div class="video-card" data-categorie="technologie">
        <video src="pitch.mp4" controls></video>
        <div class="video-info">
          <h5>Pitch Tech</h5>
          <p>Catégorie : Technologie</p>
          <a href="addoffre.php?categorie=Technologie" class="btn btn-success mt-2">
            <i class="fas fa-plus"></i> Ajouter une offre
          </a>
        </div>
      </div>
    </div>

    <!-- Bouton pour afficher le tableau -->
    <div class="text-center mt-5">
      <button class="btn btn-primary" onclick="toggleOffres()">Voir les Offres</button>
    </div>



    <div id="offresTable" style="display: none;" class="mt-4">
    <h3 class="text-center mb-4">Vos Offres</h3>
    <div class="d-flex justify-content-center mb-3">
  <select id="sortOption" class="form-select w-25 me-2">
    <option value="">-- Choisir un tri --</option>
    <option value="montant">Trier par Montant</option>
    <option value="date">Trier par Date</option>
  </select>
  <button class="btn btn-primary" onclick="sortTable()">Trier</button>
</div>
      <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
          <tr>
            <th>Montant</th>
            <th>Date_offre</th>
            <th>Statut</th>
            <th>categorie</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list as $offre): ?>
            <tr>
              <td><?= htmlspecialchars($offre['montant']) ?> DT</td>
              <td><?= htmlspecialchars($offre['date_offre']) ?></td>
              <td><?= htmlspecialchars($offre['statut']) ?></td>
              <td><?= htmlspecialchars($offre['libelle_categorie']) ?></td>
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

  <!-- JavaScript -->
  <script>
    function toggleOffres() {
      const table = document.getElementById("offresTable");
      table.style.display = table.style.display === "none" ? "block" : "none";
    }

    function filtrerParCategorie() {
      const selected = document.getElementById("categoryFilter").value.toLowerCase();
      const videos = document.querySelectorAll(".video-card");

      videos.forEach(video => {
        const cat = video.getAttribute("data-categorie").toLowerCase();
        video.style.display = (selected === "all" || selected === cat) ? "block" : "none";
      });
    }

    // Rechargement automatique (optionnel)
    setTimeout(function() {
      location.reload();
    }, 20000);
    function rechercherVideo() {
  const searchValue = document.getElementById("searchInput").value.toLowerCase();
  const videos = document.querySelectorAll(".video-card");

  videos.forEach(video => {
    const title = video.querySelector(".video-info h5").innerText.toLowerCase();
    video.style.display = title.includes(searchValue) ? "block" : "none";
  });
  let montantAsc = true;
  let dateAsc    = true;

  function sortTable() {
    const option = document.getElementById("sortOption").value;
    const tbody  = document.getElementById("offresBody");
    const rows   = Array.from(tbody.querySelectorAll("tr"));

    if (option === "montant") {
      rows.sort((a, b) => {
        const aVal = parseFloat(a.children[0].textContent);
        const bVal = parseFloat(b.children[0].textContent);
        return montantAsc ? aVal - bVal : bVal - aVal;
      });
      montantAsc = !montantAsc;
    }
    else if (option === "date") {
      rows.sort((a, b) => {
        const aDate = new Date(a.children[1].textContent);
        const bDate = new Date(b.children[1].textContent);
        return dateAsc ? aDate - bDate : bDate - aDate;
      });
      dateAsc = !dateAsc;
    }

    // réinsère les lignes triées
    tbody.innerHTML = "";
    rows.forEach(r => tbody.appendChild(r));
  }


}

  </script>

</body>
</html>

