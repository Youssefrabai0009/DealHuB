<?php
// Inclusion des contrôleurs
include __DIR__.'/../../Controller/categoriecontroller.php';
include __DIR__.'/../../Controller/offrecontroller.php';

// Initialisation des objets
$catCtrl   = new catcontroller();
$offreCtrl = new offrecontroller();

// Récupération des catégories pour le tableau
$list = $catCtrl->listcategories();

// 2) Statistiques : montant total investi par catégorie
$statTot = $offreCtrl->getTotalInvestiParCategorie();
$labels_tot   = $statTot['labels'] ?? [];
$data_tot     = $statTot['data']   ?? [];
$labels_tot_json = json_encode($labels_tot);
$data_tot_json   = json_encode($data_tot);
?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .sidebar-link.active {
      background-color: #EFF6FF;
      color: #3B82F6;
      border-left: 4px solid #3B82F6;
    }
    .sidebar-link:hover {
      background-color: #F3F4F6;
    }
    .stat-card {
      transition: transform 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }

    .notification-dropdown {
      display: none; /* caché par défaut */
      position: absolute;
      right: 0;
      margin-top: 0.5rem;
      width: 16rem;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      z-index: 50;
    }
  </style>
</head>
<body class="bg-gray-50 h-full">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-6 flex items-center space-x-2">
        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
        <span class="text-2xl font-bold text-blue-600">Backoffice</span>
      </div>
      <nav class="flex-1 space-y-1 px-4 py-2">
        <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-tachometer-alt w-6 text-center"></i>
          <span>Dashboard</span>
        </a>
        <a href="gestionusers.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-users w-6 text-center"></i>
          <span>Gestion users</span>
        </a>
        <a href="showcategorie.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-tags w-6 text-center"></i>
          <span>Gestion categories</span>
        </a>
        <a href="offres.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-briefcase w-6 text-center"></i>
          <span>Gestion offres</span>
        </a>
        <a href="dash.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-comments w-6 text-center"></i>
          <span>Gestion speechs</span>
        </a>
        <a href="complaints_list_back.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fa fa-exclamation-circle w-6 text-center"></i>
          <span>Gestion réclamations</span>
        </a>
        <a href="complaints_statistics.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fa fa-bar-chart w-6 text-center"></i>
          <span>Statistiques Des Réclamations</span>
        </a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="main-content flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-semibold mb-6 text-gray-900">Dashboard Investisseur</h1>

      <!-- Section Catégories -->
      <div class="bg-white p-6 rounded-xl shadow mb-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Gestion des Catégories</h2>
          <a href="addcategorie.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</a>
        </div>
        <div class="overflow-auto">
          <table class="w-full text-left border-t border-gray-200">
            <thead>
              <tr class="text-sm text-gray-500">
                <th class="py-2 px-4">Actions</th>
                <th class="py-2 px-4">ID Catégorie</th>
                <th class="py-2 px-4">Nom Catégorie</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <?php foreach($list as $categorie): ?>
                <tr class="border-t hover:bg-gray-50">
                  <td class="py-2 px-4 space-x-2">
                    <a href="updatecategorie.php?id_categorie=<?= $categorie['id_categorie']; ?>" class="text-black hover:text-gray-700" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="deletecategorie.php?id_categorie=<?= $categorie['id_categorie']; ?>" class="text-black hover:text-gray-700" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </td>
                  <td class="py-2 px-4"><?= $categorie['id_categorie']; ?></td>
                  <td class="py-2 px-4"><?= $categorie['libelle_categorie']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 2. Montant total investi par catégorie -->
      <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-xl font-semibold mb-4">Montant total investi par catégorie</h2>
        <canvas id="investChart" width="400" height="200"></canvas>
      </div>

      <script>
        // Graphique #2 : total investi
        const ctx2 = document.getElementById('investChart').getContext('2d');
        new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: <?= $labels_tot_json ?>,
            datasets: [{
              label: 'Total investi (DT)',
              data: <?= $data_tot_json ?>,
              backgroundColor: 'rgba(132,108,160,0.6)',
              borderColor: 'rgba(132,108,160,1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
          }
        });
      </script>
    </main>
  </div>
</body>
</html>
