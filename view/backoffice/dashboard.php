<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: ../frontoffice/login.html");
    exit;
}

$userEmail = $_SESSION['user']['email'] ?? 'admin@example.com';

// Gestion des notifications
$notifications = $_SESSION['notifications'] ?? [];
// Nettoyer les notifications après affichage (flash message)
unset($_SESSION['notifications']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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

<body class="bg-gray-50">
  <div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-6 flex items-center space-x-2">
        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
        <span class="text-2xl font-bold text-blue-600">Backoffice</span>
      </div>
      <nav class="flex-1 space-y-1 px-4 py-2">
        <a href="dashboard.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg">
          <i class="fas fa-tachometer-alt w-6 text-center"></i>
          <span>Dashboard</span>
        </a>
        <a href="gestionusers.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
        <a href="complaints_statistics.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
    <main class="flex-1 p-8 overflow-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Dashboard Dealhub</h1>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
      <!-- Notification Bell -->
      <div class="relative">
            <button id="notificationButton" class="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 relative">
              <i class="fas fa-bell"></i>
              <?php if (count($notifications) > 0): ?>
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-400"></span>
              <?php endif; ?>
            </button>
            <div id="notificationDropdown" class="notification-dropdown">
              <div class="p-4 border-b font-semibold">Notifications</div>
              <?php if (count($notifications) > 0): ?>
                <?php foreach ($notifications as $note): ?>
                  <div class="p-4 hover:bg-gray-100 border-b last:border-0 text-sm">
                    <?= htmlspecialchars($note) ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="p-4 text-gray-400 text-center">Aucune notification</div>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </div>
      <script>
document.addEventListener('DOMContentLoaded', function() {
  const notificationButton = document.getElementById('notificationButton');
  const notificationDropdown = document.getElementById('notificationDropdown');

  notificationButton.addEventListener('click', function() {
    notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', function(event) {
    if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
      notificationDropdown.style.display = 'none';
    }
  });
});
</script>


      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm font-medium text-gray-500">Utilisateurs</p>
              <p class="text-2xl font-bold mt-1">1,248</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-users"></i>
            </div>
          </div>
          <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> 12% depuis hier</p>
        </div>

        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm font-medium text-gray-500">Investisseurs</p>
              <p class="text-2xl font-bold mt-1">856</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
          </div>
          <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> 8% depuis hier</p>
        </div>

        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm font-medium text-gray-500">Entrepreneurs</p>
              <p class="text-2xl font-bold mt-1">392</p>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-lightbulb"></i>
            </div>
          </div>
          <p class="text-xs text-red-500 mt-2"><i class="fas fa-arrow-down"></i> 2% depuis hier</p>
        </div>

        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm font-medium text-gray-500">Offres</p>
              <p class="text-2xl font-bold mt-1">324</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-briefcase"></i>
            </div>
          </div>
          <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> 15% depuis hier</p>
        </div>
      </div>

      <!-- Recent Activity and Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Utilisateurs récents</h2>
            <a href="gestionusers.php" class="text-sm text-blue-500 hover:underline">Voir tout</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">ayoubbb hamraoui</td>
                  <td class="px-6 py-4 whitespace-nowrap">ayoubb@gmail.com</td>
                  <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">investisseur</span></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-11-15</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">test hamraoui</td>
                  <td class="px-6 py-4 whitespace-nowrap">samarr@gmail.com</td>
                  <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">investisseur</span></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-11-14</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">sahar hamraoui</td>
                  <td class="px-6 py-4 whitespace-nowrap">sammar@gmail.com</td>
                  <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">investisseur</span></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-11-12</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
          <div class="space-y-3">
            <button class="w-full flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
              <i class="fas fa-user-plus"></i>
              <span>Ajouter un utilisateur</span>
            </button>
            <button class="w-full flex items-center space-x-3 p-3 rounded-lg bg-green-50 text-green-600 hover:bg-green-100">
              <i class="fas fa-tag"></i>
              <span>Créer une catégorie</span>
            </button>
            <button class="w-full flex items-center space-x-3 p-3 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100">
              <i class="fas fa-bullhorn"></i>
              <span>Publier une offre</span>
            </button>
            <button class="w-full flex items-center space-x-3 p-3 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100">
              <i class="fas fa-chart-pie"></i>
              <span>Générer un rapport</span>
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>

