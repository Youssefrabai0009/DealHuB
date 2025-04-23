<?php
include __DIR__.'/../../Controller/offrecontroller.php';
include __DIR__ .'/../../Controller/categoriecontroller.php';
$offer = new offrecontroller();
$cat = new catcontroller();
$offer->deleteOffer($_GET['id_offre']);
$list = $offer->listoffers();
$categories = $cat->listcategories();




?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6 text-2xl font-bold text-blue-600">Backoffice</div>
      <nav class="space-y-2 px-6">
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion users</a>
        <a href="showcategorie.php" class="block text-gray-700 hover:text-blue-500">Gestion categories</a>
        <a href="offres.php" class="block text-gray-700 hover:text-blue-500">Gestion offres</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion speechs</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-semibold mb-6">Dashboard Dealhub</h1>

      

      <!-- Historique -->
      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4">Historique des offres</h2>
        <div class="overflow-auto">
        <table class="table table-bordered table-hover text-center">
  <thead class="table-dark">
    <tr>
      <th>ID_offre</th>
      <th>Montant</th>
      <th>Date_offre</th>
      <th>Statut</th>
      <th>ID_categorie</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($list as $offre): ?>
      <tr>
        <td><?= htmlspecialchars($offre['id_offre']) ?></td>
        <td><?= htmlspecialchars($offre['montant']) ?> DT</td>
        <td><?= htmlspecialchars($offre['date_offre']) ?></td>
        <td><?= htmlspecialchars($offre['statut']) ?></td>
        <td><?= htmlspecialchars($offre['id_categorie']) ?></td>
        <td>
          <a href="deleteoffre.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
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
    </main>
  </div>
</body>
</html>
