<?php
include __DIR__.'/../../Controller/categoriecontroller.php';

$catcontroller = new catcontroller();
$list = $catcontroller->listcategories();

$error = '';
$categorieData = null;

// Traitement du formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['id_categorie'])) {
    $id_categorie = $_GET['id_categorie'];

    if (!empty($_POST['libelle_categorie'])) {
        $categorieObj = new categorie($id_categorie, $_POST['libelle_categorie']);
        $catcontroller->updateCategorie($categorieObj, $id_categorie);
        header("Location: showcategorie.php");
        exit();
    } else {
        $error = "Le nom de la catégorie est obligatoire.";
    }
}

// Récupération des données à modifier
if (isset($_GET['id_categorie'])) {
    $categorieData = $catcontroller->showcategorie($_GET['id_categorie']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modifier une Catégorie</title>
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
          <a href="#modifier-form" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</a>
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
              <?php foreach($list as $cat): ?>
                <tr class="border-t hover:bg-gray-50">
                  <td class="py-2 px-4 space-x-2">
                    <a href="updatecategorie.php?id_categorie=<?= $cat['id_categorie'] ?>" title="Modifier" class="text-black hover:text-gray-700">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="deletecategorie.php?id_categorie=<?= $cat['id_categorie'] ?>" title="Supprimer" class="text-black hover:text-gray-700" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </td>
                  <td class="py-2 px-4"><?= $cat['id_categorie'] ?></td>
                  <td class="py-2 px-4"><?= $cat['libelle_categorie'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Formulaire de modification -->
      <section id="modifier-form" class="form-container bg-white p-6 rounded-xl shadow w-full max-w-md mx-auto">
        <h2 class="text-xl font-semibold text-center text-blue-700 mb-4">Modifier une Catégorie</h2>

        <?php if ($error): ?>
          <p class="text-red-500 text-sm text-center mb-2"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($categorieData): ?>
          <form method="POST" action="">
            <label for="libelle_categorie" class="block font-medium mb-1">Nom de la Catégorie</label>
            <input type="text" id="libelle_categorie" name="libelle_categorie"
                   class="w-full p-2 border border-gray-300 rounded"
                   value="<?= htmlspecialchars($categorieData['libelle_categorie']) ?>">

            <button type="submit" class="w-full mt-4 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
              Mettre à jour
            </button>
          </form>
        <?php else: ?>
          <p class="text-center text-red-600">Catégorie introuvable.</p>
        <?php endif; ?>

        <a href="showcategorie.php" class="block text-center mt-4 text-blue-600 hover:underline">← Retour au Dashboard</a>
      </section>
    </main>
  </div>

  <script src="controle.js"></script>
</body>
</html>
