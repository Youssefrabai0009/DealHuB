<?php
// Inclusion des dépendances
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ComplaintsController.php';
require_once __DIR__ . '/../../Model/Complaint.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: ../frontoffice/login.html");
    exit;
}
$userEmail = $_SESSION['user']['email'] ?? 'admin@example.com';


// Initialisation des objets nécessaires
$complaintModel = new Complaint($pdo); 

$statsByTopic = $complaintModel->getComplaintsCountByTopic();
$statsByDate = $complaintModel->getComplaintsCountByDate();
$stats = $complaintModel->getStatistics();

?>



<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    .sidebar-link.active {
      background-color: #EFF6FF;
      color: #3B82F6;
      border-left: 4px solid #3B82F6;
    }
    .sidebar-link:hover {
      background-color: #F3F4F6;
    }

            .btn-primary {
            background-color: #846CA0;
            color: #F5F2F6;
        }
        .btn-primary:hover {
            background-color: #A093AF;
        }
        .btn-danger {
            background-color: #847C84;
            color: #F5F2F6;
        }
        .btn-danger:hover {
            background-color: #A093AF;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #847C84;
        }

        .input-field {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }

        .btn-filter {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: #ede9fe;
            color: #4c1d95;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-filter:hover {
            background-color: #c4b5fd;
            color: white;
        }

        .btn-filter.active {
            background-color: #7c3aed;
            color: white;
        }

        .closed-img-style {
            transform: rotate(-20deg);
            top: 5%;
            right: 0;
            width: 50%;
        }

        .waiting-img-style {
            transform: rotate(-30deg);
            top: 10%;
            right: 0;
            width: 50%;
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
        <a href="gestionusers.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
          <i class="fas fa-users w-6 text-center"></i>
          <span>Gestion users</span>
        </a>
        <a href="showcategorie.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
        <a href="complaints_statistics.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fa fa-bar-chart w-6 text-center"></i>
          <span>Statistiques Des Réclamations</span>
        </a>
      </nav>
      <div class="p-4 border-t">
        <div class="flex items-center space-x-3">
          <img src="https://ui-avatars.com/api/?name=Admin&background=3B82F6&color=fff" alt="Admin" class="w-10 h-10 rounded-full">
          <div>
          <p class="font-medium"><?= htmlspecialchars($userEmail) ?></p>
          <p class="text-xs text-gray-500">Connecté</p>

          <a href="logout.php" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-200 transition duration-200">
  <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
</a>
          </div>
        
        </div>
      </div>
    </aside>
    <!-- Main content -->
    <main class="main-content flex-1 p-8 overflow-auto">
      
        
        <h1 class="text-3xl font-semibold mb-6">Statistiques des réclamations</h1>

        <div class="p-6">
            <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2 text-purple-500">Waiting</th>
                    <th class="border px-4 py-2 text-red-500">Closed</th>
                </tr>
            </thead>

                <tbody>
                    <?php foreach ($stats as $row): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['date']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['complaints_per_day']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['open_complaints']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['closed_complaints']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="my-8">
                    <h2 class="text-xl font-bold mb-2">Répartition des sujets (Pie Chart)</h2>
                    <canvas id="topicChart" class="w-full max-w-xl h-64"></canvas>
                </div>

                <div class="my-8">
                    <h2 class="text-xl font-bold mb-2">Nombre de réclamations par jour (Bar Chart)</h2>
                    <canvas id="barChart" class="w-full max-w-xl h-64"></canvas>
                </div>

                <div class="my-8">
                    <h2 class="text-xl font-bold mb-2">Évolution quotidienne (Line Chart)</h2>
                    <canvas id="lineChart" class="w-full max-w-xl h-64"></canvas>
                </div>
            </div>
            


            
        </div>

    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  <script>
    const topicLabels = <?= json_encode(array_column($statsByTopic ?? [], 'topic')) ?>;
    const topicData = <?= json_encode(array_column($statsByTopic ?? [], 'count')) ?>;

    const dateLabels = <?= json_encode(array_column($statsByDate ?? [], 'date')) ?>;
    const dateData = <?= json_encode(array_column($statsByDate ?? [], 'count')) ?>;

    const backgroundColors = topicLabels.map(label => label === 'Undefined' ? '#000000' : getRandomColor());

    function getRandomColor() {
        const colors = ['#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa', '#f472b6', '#38bdf8'];
        return colors[Math.floor(Math.random() * colors.length)];
    }


    new Chart(document.getElementById('topicChart'), {
        type: 'pie',
        data: {
            labels: topicLabels,
            datasets: [{
                label: 'Réclamations par sujet',
                data: topicData,
                backgroundColor: backgroundColors
            }]
        }
    });


    // Bar chart (complaints per day)
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: dateLabels,
            datasets: [{
                label: 'Réclamations par jour',
                data: dateData,
                backgroundColor: '#60a5fa',
                borderColor: '#3b82f6',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Line chart (daily evolution)
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: dateLabels,
            datasets: [{
                label: 'Réclamations dans le temps',
                data: dateData,
                borderColor: '#34d399',
                backgroundColor: 'rgba(52, 211, 153, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>
