<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../frontoffice/login.html");
    exit;
}

require_once '../../config.php';
require_once '../../controller/UserController.php';

$controller = new UserController($pdo);

// Get user ID from URL
$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$userId) {
    $_SESSION['error'] = "ID utilisateur manquant";
    header("Location: gestionusers.php");
    exit;
}

// Fetch user data
$user = $controller->getUserById($userId);

if (!$user) {
    $_SESSION['error'] = "Utilisateur non trouvé";
    header("Location: gestionusers.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => trim($_POST['nom']),
        'prenom' => trim($_POST['prenom']),
        'email' => trim($_POST['email']),
        'role' => trim($_POST['role'])
    ];
    
    // Validate input
    $errors = [];
    foreach ($data as $key => $value) {
        if (empty($value)) {
            $errors[] = "Le champ " . ucfirst($key) . " est requis";
        }
    }

    if (empty($errors)) {
        if ($controller->updateUser($userId, $data)) {
            $_SESSION['success'] = "Utilisateur mis à jour avec succès";
            header("Location: gestionusers.php");
            exit;
        } else {
            $errors[] = "Erreur lors de la mise à jour";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .role-admin {
            background-color: #DBEAFE;
            color: #1D4ED8;
        }
        .role-investisseur {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .role-entrepreneur {
            background-color: #EDE9FE;
            color: #5B21B6;
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
                <a href="dashboard.html" class="flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:bg-gray-100">
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
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Modifier Utilisateur</h1>
                    <a href="gestionusers.php" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom
                        </label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               id="nom" name="nom" type="text" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prenom">
                            Prénom
                        </label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               id="prenom" name="prenom" type="text" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               id="email" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                            Rôle
                        </label>
                        <select class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                id="role" name="role" required>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="investisseur" <?= $user['role'] === 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
                            <option value="entrepreneur" <?= $user['role'] === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>