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
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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

    <!-- Import Chart.js library (for graphs) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Import TailwindCSS (for nice styling) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-8">
    <!-- Body with gray background, centered content -->

    <!-- Main title -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Statistiques des Utilisateurs</h1>

    <!-- Container for the graph -->
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
        <!-- The graph will be drawn inside this canvas -->
        <canvas id="userStatsChart"></canvas>
    </div>

    <!-- Link to go back to dashboard -->
    <div class="mt-6">
        <a href="gestionusers.php" class="text-blue-600 hover:underline">⬅ Retour au dashboard</a>
    </div>

    <!-- JavaScript part to create the graph -->
    <script>
        // Get the canvas where we will draw the chart
        const ctx = document.getElementById('userStatsChart').getContext('2d');

        // Create a new Chart
        new Chart(ctx, {
            type: 'doughnut', // Type of chart (doughnut)
            data: {
                labels: ['Entrepreneurs', 'Investisseurs', 'Admins'], // Labels for each role
                datasets: [{
                    label: 'Répartition des utilisateurs', // Title for dataset
                    data: [<?= $entrepreneurCount ?>, <?= $investorCount ?>, <?= $adminCount ?>], // Data from PHP counts
                    backgroundColor: [
                        '#34D399', // Color for Entrepreneurs (green)
                        '#FBBF24', // Color for Investors (yellow)
                        '#EF4444'  // Color for Admins (red)
                    ],
                    borderWidth: 1 // Border width around each part
                }]
            },
            options: {
                responsive: true, // Make the graph adjust on different screens
                plugins: {
                    legend: {
                        position: 'bottom' // Move the legend to the bottom
                    }
                }
            }
        });
    </script>

</body>
</html>
