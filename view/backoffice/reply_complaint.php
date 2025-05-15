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
      
        <h1 class="text-3xl font-semibold mb-6">Répondre à la réclamation</h1>

        <!-- Show all errors if there are any -->
        <?php if (!empty($errors)) : ?>
            <div class="bg-red-200 text-red-800 p-2 mb-4">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <!-- Formulaire pour modifier ou ajouter une réponse -->
        <form method="POST" class="space-y-4">
            <div>
                <label class="text-[#2F1A4A]">Réponse</label>
                <textarea name="response_text" class="border p-2 w-full" rows="5" required><?= isset($reply) ? htmlspecialchars($reply['response_text']) : '' ?></textarea>
            </div>
            <button type="submit" class="btn-primary px-4 py-2 rounded"><?= isset($reply) ? 'Modifier' : 'Répondre' ?></button>
        </form>

    </main>
  </div>


</body>
</html>
