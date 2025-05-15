<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <!-- Font Awesome for icons -->
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
    .stat-card {
      transition: transform 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }

    .notification-dropdown {
      display: none; /* caché par défaut */
      position: absolute;
      right: 0;
      margin-top: 0.5rem;
      width: 16rem;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      z-index: 50;
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
        <a href="gestionusers.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
        <a href="dash.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-lg text-gray-700 hover:text-blue-500">
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
    </aside>

    <!-- Main content -->
    <main class="main-content flex-1 p-8" style="display: flex; gap: 20px;">
      <div style="flex: 2; overflow-y: auto;">
        <h1 class="text-3xl font-semibold mb-6 text-gray-900">Dashboard Dealhub</h1>

        <!-- Speeches Table -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Video URL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              require_once '../../config.php';
              require_once '../../model/Speechesmodel.php';


              $model = new SpeechesModel($pdo);
              $stmt = $pdo->prepare("SELECT * FROM speeches");
              $stmt->execute();
              $speeches = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($speeches as $speech) {
                  echo "<tr>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>{$speech['Titre']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>{$speech['video']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>{$speech['amount']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>{$speech['equity']}%</td>";
                  echo '<td class="px-6 py-4 whitespace-nowrap">
                      <button class="w-full flex items-center space-x-3 p-3 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100" onclick="deleteSpeech(' . $speech["ID_speech"] . ')">
                       <i class="fas fa-bullhorn"></i>
                           <span>Delete</span>
                      </button>
                        </td>';
                  echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" style="flex: 1;">
        <div class="chat-header flex items-center space-x-3 mb-4">
          <span class="text-3xl">💬</span>
          <h2 class="text-lg font-semibold">Chat</h2>
        </div>
        <div id="chat-messages" class="mb-4 overflow-y-auto max-h-[400px]"></div>
        <form id="chat-form" class="flex space-x-2">
          <input type="hidden" id="id_speech" name="ID_speech" value="<?php echo $speeches[0]['ID_speech'] ?? 0; ?>">
          <input type="text" id="chat-input" name="message" placeholder="Type your message..." disabled class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <button type="submit" disabled class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Send</button>
        </form>
      </div>
    </main>
  </div>

  <script>
    function deleteSpeech(speechId) {
        if (confirm('Are you sure you want to delete this speech?')) {
            fetch('../../controller/delete_speech.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + speechId
            })
            .then(response => {
                if (response.ok) {
                    location.reload(); // Reload the page to see the changes
                } else {
                    alert('Error deleting speech');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const idSpeech = document.getElementById('id_speech').value;

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '<',
            '>': '>',
            '\"': '"',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function fetchMessages() {
        fetch('../../controller/chat_controller.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched messages:', data);
                chatMessages.innerHTML = '';
                data.forEach(msg => {
                    const messageElement = document.createElement('div');
                    messageElement.style.marginBottom = '10px';

                    const messageText = document.createElement('span');
                    messageText.textContent = msg.message;

                    const editButton = document.createElement('button');
                    editButton.style.background = 'none';
                    editButton.style.border = 'none';
                    editButton.style.cursor = 'pointer';
                    editButton.style.marginLeft = '10px';

                    const editIcon = document.createElement('span');
                    editIcon.textContent = '✏️';
                    editIcon.style.fontSize = '16px';
                    editIcon.style.lineHeight = '16px';
                    editButton.appendChild(editIcon);

                    editButton.addEventListener('click', () => {
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.value = msg.message;
                        input.style.flexGrow = '1';
                        input.style.marginRight = '10px';

                        const saveButton = document.createElement('button');
                        saveButton.textContent = 'Save';
                        saveButton.style.marginRight = '5px';

                        const cancelButton = document.createElement('button');
                        cancelButton.textContent = 'Cancel';

                        const editContainer = document.createElement('div');
                        editContainer.style.display = 'flex';
                        editContainer.style.alignItems = 'center';
                        editContainer.style.marginBottom = '10px';

                        editContainer.appendChild(input);
                        editContainer.appendChild(saveButton);
                        editContainer.appendChild(cancelButton);

                        messageElement.innerHTML = '';
                        messageElement.appendChild(editContainer);

                        saveButton.addEventListener('click', () => {
                            const newMessage = input.value.trim();
                            if (newMessage.length === 0) {
                                alert('Message cannot be empty');
                                return;
                            }
                            fetch('../../controller/update_message.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ id: msg.ID_message, message: newMessage })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    fetchMessages();
                                } else {
                                    alert('Failed to update message: ' + (data.error || 'Unknown error'));
                                }
                            })
                            .catch(error => {
                                console.error('Error updating message:', error);
                                alert('Error updating message');
                            });
                        });

                        cancelButton.addEventListener('click', () => {
                            fetchMessages();
                        });
                    });

                    messageElement.appendChild(document.createTextNode('Pitch: ' + (msg.title || msg.ID_speech) + ': '));
                    messageElement.appendChild(messageText);
                    messageElement.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.style.background = 'none';
                    deleteButton.style.border = 'none';
                    deleteButton.style.cursor = 'pointer';
                    deleteButton.style.marginLeft = '10px';

                    const deleteIcon = document.createElement('span');
                    deleteIcon.textContent = '🗑️';
                    deleteIcon.style.fontSize = '16px';
                    deleteIcon.style.lineHeight = '16px';
                    deleteButton.appendChild(deleteIcon);

                    deleteButton.addEventListener('click', () => deleteMessage(msg.ID_message));

                    messageElement.appendChild(deleteButton);

                    const timestamp = document.createElement('div');
                    timestamp.style.color = '#ccc';
                    timestamp.style.fontSize = 'small';
                    timestamp.textContent = new Date(msg.sent_at).toLocaleString();

                    chatMessages.appendChild(messageElement);
                    chatMessages.appendChild(timestamp);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        fetch('/controller/chat_controller.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ID_speech: idSpeech, message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                fetchMessages();
            } else {
                alert('Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Error sending message');
        });
    });

    // Auto-refresh every 5 seconds
    setInterval(fetchMessages, 5000);
    // Initial fetch
    fetchMessages();

    function deleteMessage(messageId) {
        if (confirm('Are you sure you want to delete this message?')) {
            fetch('../../controller/delete_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + messageId
            })
            .then(response => {
                if (response.ok) {
                    fetchMessages();
                } else {
                    alert('Error deleting message');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
  </script>
</body>
</html>
