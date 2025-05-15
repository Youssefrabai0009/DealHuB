<?php
session_start();
// index.php
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__.'/../../Controller/categoriecontroller.php';
require_once __DIR__ . '/../../model/Speechesmodel.php';
  
$offer      = new offrecontroller();
$cat        = new catcontroller();
if (!isset($_SESSION['user']['id'])) {
    header("Location: user/login.html");
}
$list = $offer->listoffers($_SESSION['user']['id']);
$categories = $cat->listcategories();   // liste des catégories
$l=$offer->listoffres();
$last = end($l) ?: null;
$content = $last
    ? sprintf(
        "Offre #%d\nMontant : %d DT\nDate : %s\nStatut : %s\nCatégorie : %s",
        $last['id_offre'],
        $last['montant'],
        $last['date_offre'],
        $last['statut'],
        $last['libelle_categorie']
      )
    : "Aucune offre disponible";
$qrData = urlencode($content);   // liste des offres

// Fetch all speeches
$speechesModel = new SpeechesModel($pdo);
$speeches = $speechesModel->getAllSpeeches();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DealHub Vidéos</title>

  <!-- Google Fonts -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

    /* Reset and base */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', Arial, sans-serif;
        margin: 0;
        padding: 0;
        text-align: center;
        position: relative;
        overflow-x: hidden;
        background: linear-gradient(135deg, #1e1e2f, #2f1a4a);
        color: #e0d7f5;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(47, 26, 74, 0.85);
        backdrop-filter: blur(6px);
        z-index: -1;
    }

    video#bg-video {
        position: fixed;
        top: 0;
        left: 0;
        min-width: 100%;
        min-height: 100%;
        object-fit: cover;
        z-index: -2;
        filter: brightness(0.6) saturate(1.2);
        transition: filter 0.5s ease;
    }

    header {
        background: linear-gradient(90deg, #3a1a6a, #5a3a9e);
        color: #e0d7f5;
        padding: 20px 0;
        position: relative;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        font-weight: 600;
        letter-spacing: 1px;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 85%;
        margin: auto;
        max-width: 1200px;
    }

    .header-container span {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #f0e9ff;
        text-shadow: 0 0 8px #a18aff;
    }

    .header-container nav a {
        color: #dcd6f7;
        text-decoration: none;
        margin: 0 18px;
        font-weight: 500;
        font-size: 1rem;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .header-container nav a:hover {
        background: #a18aff;
        color: #2f1a4a;
        box-shadow: 0 0 8px #a18aff;
    }

    .main-content {
        display: flex;
        width: 85%;
        margin: 30px auto 50px;
        justify-content: center;
        align-items: flex-start;
        max-width: 1200px;
        gap: 30px;
    }

    .pitch-container {
        width: 100%;
        text-align: left;
        position: relative;
    }

    .pitch-container h2 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #f0e9ff;
        text-shadow: 0 0 10px #a18aff;
        text-align: center;
    }

    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .video-card {
        background: rgba(132, 108, 160, 0.85);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(132, 108, 160, 0.6);
        color: #f0e9ff;
        border: none;
        padding: 0;
    }

    .video-card:hover {
        box-shadow: 0 8px 20px rgba(161, 138, 255, 0.8);
        transform: scale(1.02);
        transition: transform 0.3s ease;
    }

    .video-card video {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
    }

    .video-info {
        padding: 10px;
        font-size: 1rem;
        color: #f0e9ff;
    }

    .video-info h5 {
        margin: 0 0 5px 0;
        color: #f0e9ff;
    }

    .video-info p {
        margin: 0;
        color: #dcd6f7;
    }

    .btn-success {
        background: linear-gradient(135deg, #6a4a9e, #a18aff);
        border: none;
        color: #2f1a4a;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        user-select: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #8e6edb, #c3b7ff);
        box-shadow: 0 6px 20px rgba(195, 183, 255, 0.9);
    }

    .btn-primary {
        background: linear-gradient(90deg, #3a1a6a, #5a3a9e);
        border: none;
        color: #f0e9ff;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        user-select: none;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #5a3a9e, #7a5aff);
        box-shadow: 0 6px 20px rgba(122, 90, 255, 0.9);
    }

    .btn-danger {
        background: #e74c3c;
        border: none;
        color: #fff;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        user-select: none;
    }

    .btn-danger:hover {
        background: #ff4c4c;
        box-shadow: 0 6px 20px rgba(255, 76, 76, 0.9);
    }

    #categoryFilter {
        background: #2f1a4a;
        color: #f0e9ff;
        border: 1px solid #a18aff;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 1rem;
        font-weight: 500;
        transition: border-color 0.3s ease;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        display: block;
    }

    #categoryFilter:focus {
        border-color: #c3b7ff;
        outline: none;
        box-shadow: 0 0 8px #a18aff;
    }

    #searchInput {
        background: #2f1a4a;
        color: #f0e9ff;
        border: 1px solid #a18aff;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 1rem;
        font-weight: 500;
        transition: border-color 0.3s ease;
        width: 100%;
        max-width: 400px;
        margin: 15px auto 0 auto;
        display: block;
    }

    #searchInput:focus {
        border-color: #c3b7ff;
        outline: none;
        box-shadow: 0 0 8px #a18aff;
    }

    .table-dark {
        background: #2f1a4a;
        color: #f0e9ff;
    }

    .table-dark th {
        color: #f0e9ff;
        border-bottom: 1px solid #a18aff;
    }

    .table-dark td {
        color: #dcd6f7;
        border-bottom: 1px solid #a18aff;
    }

    .table-bordered {
        border: 1px solid #a18aff;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #a18aff;
    }

    .text-center {
        text-align: center;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-center {
        justify-content: center;
    }

    .w-50 {
        width: 50%;
    }

    .w-25 {
        width: 25%;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .btn-outline-light {
        background: transparent;
        border: 1px solid #dcd6f7;
        color: #dcd6f7;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease, color 0.3s ease;
        text-decoration: none;
        user-select: none;
    }

    .btn-outline-light:hover {
        background: #a18aff;
        color: #2f1a4a;
        border-color: #a18aff;
    }

    .btn-outline-danger {
        background: transparent;
        border: 1px solid #e74c3c;
        color: #e74c3c;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease, color 0.3s ease;
        text-decoration: none;
        user-select: none;
    }

    .btn-outline-danger:hover {
        background: #ff4c4c;
        color: #2f1a4a;
        border-color: #ff4c4c;
    }
  </style>


  <!-- External Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

  <video autoplay muted loop playsinline id="bg-video">
    <source src="../../assets/backgroundloop.mp4" type="video/mp4" />
    Votre navigateur ne supporte pas la vidéo HTML5.
  </video>

  <header>
    <div class="header-container">
      <span>DealHub</span>
      <nav>
        <a href="accuil.php">Home</a>
        <a href="investisseur.php">Profile</a>
        <a href="../backoffice/showcategorie.php">dashboard</a>
        <a href="../backoffice/logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <div class="main-content">
    <div class="pitch-container">
      <h2>Nos Vidéos de Pitch</h2>

      <!-- Filtre Catégorie -->
      <select id="categoryFilter" onchange="filtrerParCategorie()">
        <option value="all">Toutes les catégories</option>
        <?php foreach($categories as $c): ?>
          <option value="<?= htmlspecialchars($c['libelle_categorie']) ?>">
            <?= htmlspecialchars($c['libelle_categorie']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Recherche Texte -->
      <input type="text" id="searchInput" placeholder="Rechercher une vidéo..." oninput="rechercherVideo()">

      <!-- Grille de Vidéos -->
      <div class="video-grid" id="videoContainer">
        <?php
        foreach ($speeches as $speech) {
            // Extract YouTube video ID from URL
            parse_str(parse_url($speech['video'], PHP_URL_QUERY), $video_params);
            $video_id = $video_params['v'] ?? '';
            $category = $speech['category'] ?? 'startups';
            ?>
            <div class="video-card" data-categorie="<?= htmlspecialchars($category) ?>">
                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video_id) ?>" allowfullscreen></iframe>
                <div class="video-info">
                    <h5><?= htmlspecialchars($speech['Titre'] ?? 'Sans titre') ?></h5>
                    <p>Catégorie : <?= htmlspecialchars($category) ?></p>
                    <a href="addoffre.php?categorie=<?= urlencode($category) ?>" class="btn-success">
                        <i class="fas fa-plus"></i> Ajouter une offre
                    </a>
                </div>
            </div>
            <?php
        }
        ?>
      </div>

      <!-- Bouton afficher / cacher le tableau d’offres -->
      <div class="text-center" style="margin-top: 2rem;">
        <button class="btn-primary" onclick="toggleOffres()">Voir les Offres</button>
      </div>

      <!-- Tableau + QR-Code -->
      <div id="offresTable" style="display:none; margin-top: 1.5rem;">
        <h3 class="text-center">Vos Offres</h3>

        <!-- QR-Code -->
        <div style="text-align:center; margin:40px 0;">
          <h4>QR Code dernière offre</h4>
          <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($content) ?>"
            alt="QR Code dernière offre"
            style="border:1px solid #ccc;"
          >
        </div>

        <!-- Controls de tri -->
        <div class="d-flex justify-content-center" style="margin-bottom: 1rem;">
          <select id="sortOption" class="w-25 me-2">
            <option value="">-- Choisir un tri --</option>
            <option value="montant">Montant</option>
            <option value="date">Date</option>
          </select>
          <button class="btn-primary" onclick="sortTable()">Trier</button>
        </div>

      <!-- Tableau des offres -->
      <table style="width: 100%; border-collapse: collapse; color: #f0e9ff; margin-top: 1rem;">
        <thead>
          <tr>
            <th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Montant</th>
            <th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Date_offre</th>
            <th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Statut</th>
            <th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Catégorie</th>
            <th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Action</th>
          </tr>
        </thead>
        <tbody id="offresBody">
          <?php foreach($list as $o): ?>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #a18aff;"><?= htmlspecialchars($o['montant']) ?> DT</td>
              <td style="padding: 8px; border-bottom: 1px solid #a18aff;"><?= htmlspecialchars($o['date_offre']) ?></td>
              <td style="padding: 8px; border-bottom: 1px solid #a18aff;"><?= htmlspecialchars($o['statut']) ?></td>
              <td style="padding: 8px; border-bottom: 1px solid #a18aff;"><?= htmlspecialchars($o['libelle_categorie']) ?></td>
              <td style="padding: 8px; border-bottom: 1px solid #a18aff;">
                <a href="updateoffre.php?id_offre=<?= $o['id_offre'] ?>" class="boost-btn" style="padding: 6px 12px; border-radius: 6px; margin-right: 0.5rem;">
                  Edit
                </a>
                <a href="deleteoffre.php?id_offre=<?= $o['id_offre'] ?>" class="boost-btn" style="background-color: #e74c3c; color: #fff; padding: 6px 12px; border-radius: 6px;"
                   onclick="return confirm('Supprimer cette offre ?');">
                  Delete
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <a href="export_offres.php" class="boost-btn" style="display: inline-block; margin-top: 1rem;">
        Exporter en PDF
      </a>
      </div>
    </div>
  </div>

  <script>
    // Affiche / cache le tableau et génère le QR-Code
    function toggleOffres() {
      const tbl = document.getElementById('offresTable');
      tbl.style.display = tbl.style.display === 'none' ? 'block' : 'none';
      if (tbl.style.display === 'block') initQRCode();
    }

    // Filtre par catégorie
    function filtrerParCategorie() {
      const sel = document.getElementById('categoryFilter').value.toLowerCase();
      document.querySelectorAll('.video-card').forEach(v => {
        const cat = v.dataset.categorie.toLowerCase();
        v.style.display = (sel === 'all' || sel === cat) ? 'block' : 'none';
      });
    }

    // Recherche par titre
    function rechercherVideo() {
      const val = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('.video-card').forEach(v => {
        const title = v.querySelector('.video-info h5').innerText.toLowerCase();
        v.style.display = title.includes(val) ? 'block' : 'none';
      });
    }

    // Tri du tableau
    let montantAsc = true, dateAsc = true;
    function sortTable() {
      const opt = document.getElementById('sortOption').value;
      const tbody = document.getElementById('offresBody');
      const rows = Array.from(tbody.querySelectorAll('tr'));
      if (opt === 'montant') {
        rows.sort((a,b) => {
          const aV = parseFloat(a.cells[0].textContent),
                bV = parseFloat(b.cells[0].textContent);
          return montantAsc ? aV - bV : bV - aV;
        });
        montantAsc = !montantAsc;
      } else if (opt === 'date') {
        rows.sort((a,b) => {
          const aD = new Date(a.cells[1].textContent),
                bD = new Date(b.cells[1].textContent);
          return dateAsc ? aD - bD : bD - aD;
        });
        dateAsc = !dateAsc;
      }
      tbody.innerHTML = '';
      rows.forEach(r => tbody.appendChild(r));
    }
  </script>
</body>
</html>