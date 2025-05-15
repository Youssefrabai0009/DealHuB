<?php
// Include the UserController (handles users-related functions)
require_once '../../controller/UserController.php';

// Include the User model (represents a user)
require_once '../../model/user.php';

// Include the config file (contains database connection)
require_once '../../config.php';

// Start the session to use session variables
session_start();

// Check if the user is not logged in OR not an admin, redirect them to login page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: ../frontoffice/login.html");
    exit;
}

// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total number of users
$totalUsers = count($users);

// Count how many users are 'admin'
$adminCount = count(array_filter($users, fn($user) => $user['role'] === 'admin'));

// Count how many users are 'entrepreneur'
$entrepreneurCount = count(array_filter($users, fn($user) => $user['role'] === 'entrepreneur'));

// Count how many users are 'investisseur' (investors)
$investorCount = count(array_filter($users, fn($user) => $user['role'] === 'investisseur'));
?>

<!-- HTML part starts here -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des Utilisateurs</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- TailwindCSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome (for icons) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-6 flex items-center space-x-2">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                <span class="text-2xl font-bold text-blue-600">Backoffice</span>
            </div>
            <nav class="flex-1 space-y-1 px-4 py-2">
                <a href="dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="gestionusers.php" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span>Gestion users</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tags w-6 text-center"></i>
                    <span>Gestion catégories</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-briefcase w-6 text-center"></i>
                    <span>Gestion offres</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-comments w-6 text-center"></i>
                    <span>Gestion speechs</span>
                </a>
            </nav>
            <div class="p-4 border-t">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=3B82F6&color=fff" alt="Admin" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">Admin</p>
                        <p class="text-xs text-gray-500">Administrateur</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Statistiques des Utilisateurs</h1>

            <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-lg">
                <canvas id="userStatsChart"></canvas>
            </div>

            <div class="mt-6">
                <a href="gestionusers.php" class="text-blue-600 hover:underline">⬅ Retour à la gestion des utilisateurs</a>
            </div>
        </main>
    </div>

    <!-- Chart Initialization -->
    <script>
        const ctx = document.getElementById('userStatsChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Entrepreneurs', 'Investisseurs', 'Admins'],
                datasets: [{
                    label: 'Répartition des utilisateurs',
                    data: [<?= $entrepreneurCount ?>, <?= $investorCount ?>, <?= $adminCount ?>],
                    backgroundColor: ['#34D399', '#FBBF24', '#EF4444'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
