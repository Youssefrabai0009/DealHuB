<?php
// Include necessary files
require_once '../../controller/UserController.php';
require_once '../../model/user.php';
require_once '../../config.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../frontoffice/login.html");
    exit;
}
$userEmail = $_SESSION['user']['email'] ?? 'admin@example.com';
// Create an instance of the UserController
$controller = new UserController($pdo);

// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

//pagination
// Number of results per page
$resultsPerPage = 6;

// Get the current page number from the URL (default to page 1)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $resultsPerPage;

// Fetch users with LIMIT and OFFSET for pagination
$stmt = $pdo->prepare("SELECT * FROM users LIMIT :offset, :resultsPerPage");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':resultsPerPage', $resultsPerPage, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of users for pagination
$totalUsersStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalUsersStmt->fetchColumn();
$totalPages = ceil($totalUsers / $resultsPerPage);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des utilisateurs</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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
        <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
          <i class="fas fa-tachometer-alt w-6 text-center"></i>
          <span>Dashboard</span>
        </a>
        <a href="gestionusers.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg">
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
        <a href="complaints_list_back.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
    <main class="flex-1 p-8 overflow-auto">

          <!-- notification part -->
    <?php if (isset($_SESSION['success'])): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Succès ! </strong>
    <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
    <span onclick="this.parentElement.style.display='none';" class="absolute top-0 bottom-0 right-0 px-4 py-3">
      <svg class="fill-current h-6 w-6 text-green-500" role="button" viewBox="0 0 20 20"><title>Fermer</title><path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z"/></svg>
    </span>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

    <!-- recherch ajout export -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h1>
        
    <!-- recherch -->
        <div class="flex space-x-4">
  <div class="relative">
    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    <input type="text" placeholder="Rechercher un utilisateur..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
  </div>

   <!-- Add User Button -->
<button onclick="openModal()" type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
  <i class="fas fa-user-plus"></i>
  <span>Ajouter un utilisateur</span>
</button>
   
    <!-- export -->
  <a href="export_users.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
    <i class="fas fa-file-pdf"></i>
    <span>Exporter PDF</span>
  </a>

</div>

      </div>
         <!-- table users -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
            <button onclick="sortTable(1)" class="bg-blue-500 text-white px-4 py-2 rounded">Sort by Nom</button>
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prénom</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
        <?php foreach ($users as $user): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($user['id']) ?></td>
          <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($user['nom']) ?></td>
          <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($user['prenom']) ?></td>
          <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></td>
          <td class="px-6 py-4">
            <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
              <?= htmlspecialchars($user['role']) ?>
            </span>
          </td>
          <td class="px-6 py-4 text-sm text-gray-500">
            <div class="flex space-x-2">
              <a href="modifier_user.php?id=<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                <i class="fas fa-edit"></i>
              </a>
              <a href="supprimer_user.php?id=<?= $user['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                <i class="fas fa-trash-alt"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <!-- Pagination -->
      <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="flex space-x-2">
          <!-- Previous Page Link -->
          <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 text-blue-600 hover:text-blue-900">Précédent</a>
          <?php endif; ?>

          <!-- Page Numbers -->
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-4 py-2 text-gray-700 hover:bg-blue-100 <?= $i == $currentPage ? 'bg-blue-500 text-white' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>

          <!-- Next Page Link -->
          <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 text-blue-600 hover:text-blue-900">Suivant</a>
          <?php endif; ?>
        </div>

        <p class="text-sm text-gray-700">
          Affichage de <span class="font-medium"><?= $offset + 1 ?></span> à <span class="font-medium"><?= min($offset + $resultsPerPage, $totalUsers) ?></span> sur <span class="font-medium"><?= $totalUsers ?></span> utilisateurs
        </p>
      </div>

    </table>
        </div>
       
      </div>
       
    <!-- ajout -->
    <div id="ajoutUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
  <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-semibold mb-4">Ajouter un utilisateur</h2>
   
    <form id="ajoutUserForm" action="ajout_user.php" method="POST" class="flex flex-col space-y-2">
  <div>
    <input type="text" name="nom" id="nom" placeholder="Nom" class="border rounded px-2 py-1 w-full">
    <small id="nomError" class="text-red-500 text-sm"></small>
  </div>

  <div>
    <input type="text" name="prenom" id="prenom" placeholder="Prénom" class="border rounded px-2 py-1 w-full">
    <small id="prenomError" class="text-red-500 text-sm"></small>
  </div>

  <div>
    <input type="text" name="email" id="email" placeholder="Email" class="border rounded px-2 py-1 w-full">
    <small id="emailError" class="text-red-500 text-sm"></small>
  </div>

  <div>
    <input type="text" name="password" id="password" placeholder="Mot de passe" class="border rounded px-2 py-1 w-full">
    <small id="passwordError" class="text-red-500 text-sm"></small>
  </div>

  <div>
    <select name="role" id="role" class="border rounded px-2 py-1 w-full">
      <option value="">Sélectionnez un rôle</option>
      <option value="entrepreneur">Entrepreneur</option>
      <option value="investisseur">Investisseur</option>
    </select>
    <small id="roleError" class="text-red-500 text-sm"></small>
  </div>

  <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
    <i class="fas fa-user-plus"></i>
    <span>Ajouter un utilisateur</span>
  </button>
</form>
  </div>
</div>

<script>
document.getElementById('ajoutUserForm').addEventListener('submit', function(event) {
  let isValid = true;

  // Get fields
  const nom = document.getElementById('nom').value.trim();
  const prenom = document.getElementById('prenom').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const role = document.getElementById('role').value;

  // Get error elements
  const nomError = document.getElementById('nomError');
  const prenomError = document.getElementById('prenomError');
  const emailError = document.getElementById('emailError');
  const passwordError = document.getElementById('passwordError');
  const roleError = document.getElementById('roleError');

  // Clear previous errors
  nomError.textContent = '';
  prenomError.textContent = '';
  emailError.textContent = '';
  passwordError.textContent = '';
  roleError.textContent = '';

  // Validation
  if (nom === '') {
    nomError.textContent = 'Le nom est requis.';
    isValid = false;
  }
  if (prenom === '') {
    prenomError.textContent = 'Le prénom est requis.';
    isValid = false;
  }
  if (email === '') {
    emailError.textContent = 'L\'email est requis.';
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    emailError.textContent = 'Email invalide.';
    isValid = false;
  }
  if (password === '') {
    passwordError.textContent = 'Le mot de passe est requis.';
    isValid = false;
  }
  if (role === '') {
    roleError.textContent = 'Veuillez sélectionner un rôle.';
    isValid = false;
  }

  if (!isValid) {
    event.preventDefault(); // prevent form submission if any field is invalid
  }
});
</script>

     <!-- statistique -->
      <a href="stats.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
  <i class="fas fa-chart-pie w-6 text-center"></i>
  <span>Statistiques</span>
</a>
<!-- Modal Form for Adding User -->
    </main>
  </div>

  <script>
    // Search functionality
    document.querySelector('input[type="text"]').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

//sort
  function sortTable(columnIndex) {
    var table = document.querySelector("table");
    var rows = Array.from(table.rows).slice(1); // Skip header row
    var sortedRows = rows.sort(function(a, b) {
      var cellA = a.cells[columnIndex].textContent.trim().toLowerCase();
      var cellB = b.cells[columnIndex].textContent.trim().toLowerCase();

      if (cellA < cellB) {
        return -1;
      }
      if (cellA > cellB) {
        return 1;
      }
      return 0;
    });

    // Rebuild the table with sorted rows
    table.tBodies[0].innerHTML = "";
    sortedRows.forEach(function(row) {
      table.tBodies[0].appendChild(row);
    });
  }

  </script>

  <!-- Chatbot -->
<!-- Chatbot -->
<div class="chatbox" id="chatbox">
  <div class="chatbox-header">
    <span>Chatbot</span>
    <button onclick="closeChat()">X</button>
  </div>
  <div class="chatbox-body" id="chatboxBody">
    <div class="message bot"><span class="time">[Bot]</span> Hello! How can I assist you today?</div>
  </div>
  <div class="chatbox-footer">
    <input type="text" id="userMessage" placeholder="Type a message..." onkeydown="if(event.key==='Enter') sendMessage()">
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

<!-- Chatbot toggle button -->
<button onclick="toggleChat()" class="fixed bottom-5 right-5 bg-green-500 text-white p-3 rounded-full z-50">
  💬
</button>

<style>
  .chatbox {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 320px;
    height: 450px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    z-index: 999;
  }

  .chatbox-header {
    background-color: #10b981;
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .chatbox-body {
    padding: 10px;
    flex-grow: 1;
    overflow-y: auto;
    background: #f9f9f9;
  }

  .chatbox-footer {
    display: flex;
    padding: 10px;
    background-color: #f1f1f1;
    border-radius: 0 0 10px 10px;
    gap: 10px;
  }

  .chatbox-footer input {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
  }

  .chatbox-footer button {
    padding: 10px 15px;
    background-color: #10b981;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
  }

  .message {
    margin: 8px 0;
    padding: 8px 12px;
    border-radius: 10px;
    max-width: 85%;
    word-wrap: break-word;
    position: relative;
    font-size: 14px;
  }

  .message.user {
    background-color: #e1f5fe;
    align-self: flex-end;
    text-align: right;
  }

  .message.bot {
    background-color: #e8f5e9;
    align-self: flex-start;
    text-align: left;
  }

  .time {
    font-size: 11px;
    color: #555;
    display: block;
    margin-bottom: 3px;
  }

  @media (max-width: 500px) {
    .chatbox {
      width: 95%;
      right: 2.5%;
    }
  }
</style>

<script>
  function toggleChat() {
    const chatbox = document.getElementById("chatbox");
    chatbox.style.display = (chatbox.style.display === "flex") ? "none" : "flex";
  }

  function closeChat() {
    document.getElementById("chatbox").style.display = "none";
  }

  function getTime() {
    const now = new Date();
    return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  function sendMessage() {
    const input = document.getElementById("userMessage");
    const message = input.value.trim();
    const chatboxBody = document.getElementById("chatboxBody");

    if (message === "") return;

    // Show user message
    chatboxBody.innerHTML += `<div class="message user"><span class="time">[You - ${getTime()}]</span>${message}</div>`;
    input.value = "";
    chatboxBody.scrollTop = chatboxBody.scrollHeight;

    // Simulated bot response
    setTimeout(() => {
      let msg = message.toLowerCase();
      let response = "I'm not sure how to answer that. Can you rephrase it?";

      if (msg.includes("hello") || msg.includes("hi")) {
        response = "Hello 👋! How can I help you with the site?";
      } else if (msg.includes("how are you")) {
        response = "I'm a chatbot — always ready to help! 😊";
      } else if (msg.includes("i want you to help me")) {
        response = "yes fr I'm a chatbot always ready to help! 😊";
      } else if (msg.includes("what is dealhub") || msg.includes("about dealhub")) {
        response = "DealHub is a platform that connects entrepreneurs with investors using video pitches and real-time analytics.";
      } else if (msg.includes("add user") || msg.includes("create user")) {
        response = "To add a user, click the '+' button next to the search bar. A form will appear to enter user info.";
      } else if (msg.includes("delete user") || msg.includes("remove user")) {
        response = "To delete a user, click the trash 🗑️ icon next to their row in the table.";
      } else if (msg.includes("edit user") || msg.includes("edit profile")) {
        response = "To edit a user, click the pencil ✏️ icon. You can update name, email, or role.";
      } else if (msg.includes("roles") || msg.includes("what roles")) {
        response = "There are two roles: 'entrepreneur' and 'investor'. Admins manage them from this dashboard.";
      } else if (msg.includes("statistics") || msg.includes("stats")) {
        response = "User statistics are shown on the dashboard homepage under 'Statistiques'.";
      } else if (msg.includes("export pdf") || msg.includes("pdf")) {
        response = "to export users pdf click on export pdf button in the right top of gestion users page.";
      } else if (msg.includes("logout") || msg.includes("sign out")) {
        response = "To log out, click your profile icon and select 'Logout'.";
      }else if (msg.includes("thanks") || msg.includes("sign out")) {
        response = "your welcome 😊";
      }

      chatboxBody.innerHTML += `<div class="message bot"><span class="time">[Bot - ${getTime()}]</span>${response}</div>`;
      chatboxBody.scrollTop = chatboxBody.scrollHeight;
    }, 800);
  }
</script>


<script>
  function openModal() {
    document.getElementById("ajoutUserModal").classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("ajoutUserModal").classList.add("hidden");
  }
</script>

</body>
</html>
