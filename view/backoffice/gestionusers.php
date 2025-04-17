<?php
// Include necessary files
require_once '../../controller/UserController.php';
require_once '../../model/user.php'; // Assuming you have a User model for handling user data
require_once '../../config.php'; // Make sure to include the config file for DB connection

// Create an instance of the UserController
$controller = new UserController($pdo);

// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users"); // Assuming 'user' is your table name
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users as associative array
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des utilisateurs</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6 text-2xl font-bold text-blue-600">Backoffice</div>
      <nav class="space-y-2 px-6">
        <a href="dashboard.html" class="block text-gray-700 hover:text-blue-500">Dashboard</a>
        <a href="gestionusers.php" class="block text-blue-600 font-semibold">Gestion users</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion categories</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion offres</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion speechs</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-semibold mb-6">Liste des utilisateurs</h1>

      <table class="table-auto w-full border border-gray-300 bg-white shadow-md rounded-lg">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Nom</th>
            <th class="px-4 py-2 border">Prénom</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Rôle</th>
            
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
          <tr class="hover:bg-gray-100">
            <td class="px-4 py-2 border"><?= htmlspecialchars($user['id']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($user['nom']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($user['prenom']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($user['role']) ?></td>
      
              <!-- You can add Edit and Delete actions here -->

            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>

</html>
