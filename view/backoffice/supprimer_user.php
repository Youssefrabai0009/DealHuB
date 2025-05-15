<?php
require_once '../../config.php';
require_once '../../model/user.php';
require_once '../../controller/UserController.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: ../frontoffice/login.html");
    exit;
}

$controller = new UserController($pdo);

// Get user ID from URL
$userId = $_GET['id'] ?? null;

if (!$userId) {
    header("Location: gestionusers.php?error=ID utilisateur manquant");
    exit;
}

// Check if user exists
$user = $controller->getUserById($userId);

if (!$user) {
    header("Location: gestionusers.php?error=Utilisateur non trouvé");
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $controller->deleteUser($userId);
    
    if ($success) {
        header("Location: gestionusers.php?success=Utilisateur supprimé avec succès");
        exit;
    } else {
        header("Location: gestionusers.php?error=Erreur lors de la suppression de l'utilisateur");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <span>Gestion categories</span>
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
        <!-- Main content -->
        <main class="flex-1 p-8 overflow-auto">
            <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Supprimer Utilisateur</h1>
                    <a href="gestionusers.php" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p class="font-bold">Attention!</p>
                    </div>
                    <p class="mt-2">Vous êtes sur le point de supprimer définitivement cet utilisateur. Cette action est irréversible.</p>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="font-bold text-lg mb-2">Informations de l'utilisateur</h2>
                    <p><span class="font-semibold">Nom:</span> <?= htmlspecialchars($user['nom']) ?></p>
                    <p><span class="font-semibold">Prénom:</span> <?= htmlspecialchars($user['prenom']) ?></p>
                    <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
                    <p><span class="font-semibold">Rôle:</span> <?= htmlspecialchars($user['role']) ?></p>
                </div>

                <form method="POST">
                    <div class="flex justify-end space-x-4">
                        <a href="gestionusers.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-trash-alt mr-2"></i> Confirmer la suppression
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>