<?php
include __DIR__.'/../../Controller/categoriecontroller.php';
$error = '';
$catcontroller = new catcontroller();
$categorie = new catcontroller();
$list = $categorie->listcategories();
if (isset($_POST['libelle_categorie'])) {
    $libelle_categorie = $_POST['libelle_categorie'];

    if (!empty($libelle_categorie)) {
        // Crée une nouvelle catégorie uniquement avec le libellé
        $newCategorie = new categorie(null, $libelle_categorie); // Laisse null ou supprime le 1er paramètre si possible
        $catcontroller->addCategorie($newCategorie);
        header("Location: showcategorie.php");
    } else {
        $error = "Le libellé est obligatoire.";
    }
}

if ($error) {
    echo "<p style='color:red;'>$error</p>";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter une Catégorie</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans">
  <div class="flex h-screen">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6 text-2xl font-bold text-blue-600">Backoffice</div>
      <nav class="space-y-2 px-6">
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion users</a>
        <a href="showcategorie.php" class="block text-blue-700 font-semibold">Gestion catégories</a>
        <a href="offres.php" class="block text-gray-700 hover:text-blue-500">Gestion offres</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion speechs</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-semibold mb-6">Dashboard Investisseur</h1>

      <!-- Tableau des catégories -->
      <section class="bg-white p-6 rounded-xl shadow mb-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Gestion des Catégories</h2>
          <a href="#ajouter-form" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</a>
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
              <?php foreach($list as $categorie): ?>
                <tr class="border-t hover:bg-gray-50">
                <td class="py-2 px-4 space-x-2">
  <a href="updatecategorie.php?id_categorie=<?= $categorie['id_categorie'] ?>" title="Modifier" class="text-black hover:text-gray-700">
    <i class="fas fa-edit"></i>
  </a>
  <a href="deletecategorie.php?id_categorie=<?= $categorie['id_categorie'] ?>" title="Supprimer" class="text-black hover:text-gray-700" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
    <i class="fas fa-trash-alt"></i>
  </a>
</td>
                  <td class="py-2 px-4"><?= $categorie['id_categorie'] ?></td>
                  <td class="py-2 px-4"><?= $categorie['libelle_categorie'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Formulaire d'ajout -->
      <section id="ajouter-form" class="form-container bg-white p-6 rounded-xl shadow w-full max-w-md mx-auto">
        <h2 class="text-xl font-semibold text-center text-blue-700 mb-4">Ajouter une Catégorie</h2>
        <?php if ($error): ?>
          <p class="text-red-500 text-sm text-center mb-2"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
          <label for="libelle_categorie" class="block font-medium mb-1">Nom de la Catégorie</label>
          <input type="text" id="libelle_categorie" name="libelle_categorie" class="w-full border border-gray-300 rounded px-3 py-2 mb-4" required>
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Ajouter</button>
        </form>
        <a href="showcategorie.php" class="block text-center mt-4 text-blue-600 hover:underline">← Retour au Dashboard</a>
      </section>
    </main>
  </div>

  <script src="controle.js"></script>
</body>
</html>