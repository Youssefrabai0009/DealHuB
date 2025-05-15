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

// Récupération des filtres GET
$filters = [
    'status' => $_GET['status'] ?? '',
    'title' => $_GET['title'] ?? '',
    'date_filter' => $_GET['date_filter'] ?? '',
    'sort_order' => $_GET['sort_order'] ?? 'desc'
];

// Récupération des réclamations filtrées
$complaints = $complaintModel->getFilteredComplaints($filters);
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
        <a href="complaints_list_back.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
    <main class="main-content flex-1 p-8 overflow-auto">
      
        
        <h1 class="text-3xl font-semibold mb-6">Dashboard des réclamations</h1>

        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <input type="hidden" name="action" value="backoffice">

            <input type="text" name="title" class="px-2" placeholder="Filtrer par titre" value="<?= htmlspecialchars($filters['title'] ?? '') ?>" class="input">
            
            <select name="status" class="input">
                <option value="">Tous les statuts</option>
                <option value="open" <?= ($filters['status'] ?? '') === 'open' ? 'selected' : '' ?>>Ouvert</option>
                <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Fermé</option>
            </select>

            <select name="date_filter" class="input">
                <option value="">Toutes les dates</option>
                <option value="today" <?= ($filters['date_filter'] ?? '') === 'today' ? 'selected' : '' ?>>Aujourd’hui</option>
                <option value="last_week" <?= ($filters['date_filter'] ?? '') === 'last_week' ? 'selected' : '' ?>>Semaine dernière</option>
                <option value="last_month" <?= ($filters['date_filter'] ?? '') === 'last_month' ? 'selected' : '' ?>>Mois dernier</option>
            </select>

            <select name="sort_order" class="input">
                <option value="desc" <?= ($filters['sort_order'] ?? '') === 'desc' ? 'selected' : '' ?>>Date décroissante</option>
                <option value="asc" <?= ($filters['sort_order'] ?? '') === 'asc' ? 'selected' : '' ?>>Date croissante</option>
            </select>

            <!-- Boutons Appliquer et Réinitialiser -->
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary p-3">Appliquer les filtres</button>
                <a href="complaints_list_back.php" class="btn btn-secondary p-3 bg-gray-200 border border-gray-400">Réinitialiser</a>
            </div>
        </form>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($complaints as $complaint): ?>
                <div class="card p-6 rounded shadow w-full relative">
                    <h2 class="text-xl font-bold mb-2 text-[#2F1A4A]"><?= htmlspecialchars($complaint['title']) ?></h2>
                    <p class="mb-2 text-[#847C84]"><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>
                    <p class="text-sm text-[#A093AF]">Topic: <b> <?= htmlspecialchars($complaint['topic']) ?> </b> </p>
                    <?php if($complaint['status'] == 'Closed') : ?>
                        <img src="../../assets/closed.png" alt="closed complaint" class="absolute closed-img-style">
                    <?php else : ?>
                        <img src="../../assets/waiting.png" alt="waiting complaint" class="absolute waiting-img-style">
                    <?php endif; ?>

                    <p class="text-sm text-[#A093AF]"><?= htmlspecialchars($complaint['created_at']) ?></p>
                    <!-- Affichage des réponses -->
                    <?php 
                        $responses = $complaintModel->getResponsesByComplaintId($complaint['id']);
                        foreach ($responses as $response):
                    ?>
                        <div class="response p-2 mt-4 bg-gray-100 rounded">
                            <p><?= nl2br(htmlspecialchars($response['response_text'])) ?></p>
                            <p class="text-sm text-gray-500"><?= $response['created_at'] ?></p>

                            <!-- Buttons for modify and delete reply -->
                            <div class="flex justify-between items-center mt-2">
                                <a href="/wissal/DealHuB/controller/ComplaintsController.php?action=modify_reply&id=<?= $response['id'] ?>" class="btn-primary px-3 py-1 rounded">Modifier</a>
                                <a href="/wissal/DealHuB/controller/ComplaintsController.php?action=delete_reply&id=<?= $response['id'] ?>" class="btn-danger px-3 py-1 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Bouton Répondre -->
                    <a href="/wissal/DealHuB/controller/ComplaintsController.php?action=reply&id=<?= $complaint['id'] ?>" class="btn-primary px-3 py-1 rounded">Répondre</a>
                </div>
            <?php endforeach; ?>
        </div>

    </main>
  </div>


</body>
</html>
