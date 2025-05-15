<?php
// offres.php
session_start();
// Inclusion des contrôleurs
include __DIR__ . '/../../Controller/offrecontroller.php';
include __DIR__ . '/../../Controller/categoriecontroller.php';

// Instanciation
$offreCtrl = new offrecontroller();
$catCtrl   = new catcontroller();

// 1) Récupérer la liste des offres (avec libelle_categorie via JOIN dans listoffers)
$listOffres = $offreCtrl->listoffers($_SESSION['user']['id']);

// 2) Statistiques : nombre d'offres par catégorie
$statNb      = $offreCtrl->getStatistiquesParCategorie();
$labels      = $statNb['labels'] ?? [];
$data        = $statNb['data']   ?? [];
$labels_json = json_encode($labels);
$data_json   = json_encode($data);
?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Offres</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <!-- Font Awesome for icons -->
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
            <i class="fas fa-users w-6 text-center"></i>
            <span>Dashboard</span>
          </a>
          <a href="users.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
            <i class="fas fa-users w-6 text-center"></i>
            <span>Gestion users</span>
          </a>
          <a href="showcategorie.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
            <i class="fas fa-tags w-6 text-center"></i>
            <span>Gestion categories</span>
          </a>
          <a href="offres.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
        <h1 class="text-3xl font-semibold mb-6 text-gray-900">Dashboard des Offres</h1>

        <!-- Tableau des Offres -->
        <div class="bg-white p-6 rounded-xl shadow mb-10">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Historique des Offres</h2>
          </div>
          <div class="overflow-auto">
            <table class="w-full text-left border-t border-gray-200">
              <thead>
                <tr class="text-sm text-gray-500 uppercase bg-gray-100">
                  <th class="py-2 px-4">Montant</th>
                  <th class="py-2 px-4">Date Offre</th>
                  <th class="py-2 px-4">Statut</th>
                  <th class="py-2 px-4">Catégorie</th>
                  <th class="py-2 px-4 text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="text-sm text-gray-700">
                <?php foreach($listOffres as $offre): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="py-2 px-4"><?= htmlspecialchars($offre['montant']) ?> DT</td>
                    <td class="py-2 px-4"><?= htmlspecialchars($offre['date_offre']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($offre['statut']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($offre['libelle_categorie']) ?></td>
                    <td class="py-2 px-4 text-center space-x-2">
                      <a href="deleteoffres.php?id_offre=<?= $offre['id_offre'] ?>"
                         class="text-red-600 hover:text-red-800" title="Supprimer"
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

        <!-- Graphique : Nombre d'offres par catégorie -->
        <div class="bg-white p-6 rounded-xl shadow">
          <h2 class="text-xl font-semibold mb-4">Nombre d'offres par catégorie</h2>
          <canvas id="offreCountChart" width="400" height="200"></canvas>
        </div>
      </main>
    </div>

    <script>
      const ctx = document.getElementById('offreCountChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?= $labels_json ?>,
          datasets: [{
            label: "Nombre d'offres",
            data: <?= $data_json ?>,
            backgroundColor: 'rgba(54,162,235,0.6)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: { precision: 0 }
            }
          }
        }
      });
    </script>
  </body>
</html>
