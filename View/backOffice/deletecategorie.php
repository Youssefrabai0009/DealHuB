<?php
include __DIR__.'/../../Controller/categoriecontroller.php';
$categorie = new catcontroller();
$list = $categorie->listcategories();
$categorie->deletecategorie($_GET['id_categorie']);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6 text-2xl font-bold text-blue-600">Backoffice</div>
      <nav class="space-y-2 px-6">
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion users</a>
        <a href="showcategorie.php" class="block text-blue-700 hover:text-blue-500">Gestion categories</a>
        <a href="offres.php" class="block text-gray-700 hover:text-blue-500">Gestion offres</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion speechs</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-semibold mb-6">Dashboard Investisseur</h1>

      <!-- Section Catégories -->
      <div class="bg-white p-6 rounded-xl shadow mb-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Gestion des Catégories</h2>
          <div class="space-x-2">
            <a href="addcategorie.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</a>
          </div>
        </div>
        <div class="overflow-auto">
          <table class="w-full text-left border-t border-gray-200">
            <thead>
              <tr class="text-sm text-gray-500">
                <th class="py-2 px-4">Actions</th>
                <th class="py-2 px-4">ID Catégorie</th>
                <th class="py-2 px-4">Nom Catégorie</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <?php
              foreach($list as $categorie){
              ?>
                <tr class="border-t">
                  <!-- Colonne Actions avec boutons Modifier et Supprimer -->
                  <td class="py-2 px-4">
                    <a href="updatecategorie.php?id_categorie=<?php echo $categorie['id_categorie']; ?>" class="text-primary me-2" title="Modifier">
                    <i class="fas fa-edit"></i>
                    </a>
                    <a href="deletecategorie.php?id_categorie=<?php echo $categorie['id_categorie']; ?>" class="text-danger" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                    <i class="fas fa-trash-alt"></i>
                  </td>
                  <td class="py-2 px-4"><?php echo $categorie['id_categorie']; ?></td>
                  <td class="py-2 px-4"><?php echo $categorie['libelle_categorie']; ?></td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
  <script src="controledel.js"></script>
</body>
</html>