<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Dealhub</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6 text-2xl font-bold text-blue-600">Backoffice</div>
      <nav class="space-y-2 px-6">
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion users</a>
        <a href="categorie.html" class="block text-gray-700 hover:text-blue-500">Gestion categories</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion offres</a>
        <a href="#" class="block text-gray-700 hover:text-blue-500">Gestion speechs</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-auto" style="display: flex; gap: 20px;">
      <div style="flex: 2;">
        <h1 class="text-3xl font-semibold mb-6">Dashboard Dealhub</h1>

        <!-- Speeches Table -->
        <table class="min-w-full bg-white border border-gray-300">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b">Title</th>
              <th class="py-2 px-4 border-b">Video URL</th>
              <th class="py-2 px-4 border-b">Amount</th>
              <th class="py-2 px-4 border-b">Equity</th>
              <th class="py-2 px-4 border-b">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once '../config/data_base.php';
            require_once '../models/Speechesmodel.php';

            $model = new SpeechesModel($pdo);
            $stmt = $pdo->prepare("SELECT * FROM speeches");
            $stmt->execute();
            $speeches = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($speeches as $speech) {
                echo "<tr>";
                echo "<td class='py-2 px-4 border-b'>{$speech['Titre']}</td>";
                echo "<td class='py-2 px-4 border-b'>{$speech['video']}</td>";
                echo "<td class='py-2 px-4 border-b'>{$speech['amount']}</td>";
                echo "<td class='py-2 px-4 border-b'>{$speech['equity']}%</td>";
                echo "<td class='py-2 px-4 border-b'>
                        <button class='text-red-500' onclick='deleteSpeech({$speech['ID_speech']})'>Delete</button>
                      </td>";
                echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="chat-container widget" style="flex: 1; display: flex; flex-direction: column; height: 600px; background: white; padding: 15px; border-radius: 10px; color: #000000; font-size: 14px;">
        <h2><img src="../views/images/chat.png" alt="Chat" style="width: 24px; height: 24px;"></h2>
        <div id="chat-messages" style="flex-grow: 1; overflow-y: auto; background: #E0E7FF; padding: 10px; border-radius: 10px; color: #0000FF; font-size: 14px;"></div>
        <form id="chat-form" style="margin-top: 10px; display: flex;">
          <input type="hidden" id="id_speech" name="ID_speech" value="<?php echo $speeches[0]['ID_speech'] ?? 0; ?>">
          <input type="text" id="chat-input" name="message" placeholder="Type your message..." style="flex-grow: 1; padding: 8px; border-radius: 5px; border: none; font-size: 14px;" disabled>
          <button type="submit" style="padding: 8px 15px; margin-left: 10px; background-color: #2F1A4A; color: #F5F2F6; border: none; border-radius: 5px; cursor: not-allowed;" disabled>Send</button>
        </form>
      </div>
    </main>
  </div>

  <script>
    function deleteSpeech(speechId) {
        if (confirm('Are you sure you want to delete this speech?')) {
            fetch('delete_speech.php', {
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
        fetch('/entrepreneurship/controllers/chat_controller.php')
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

                    const editIcon = document.createElement('img');
                    editIcon.src = '../views/images/edit.png';
                    editIcon.alt = 'Edit';
                    editIcon.style.width = '16px';
                    editIcon.style.height = '16px';
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
                            fetch('../controllers/update_message.php', {
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

                    const deleteIcon = document.createElement('img');
                    deleteIcon.src = '../views/images/delll.png';
                    deleteIcon.alt = 'Delete';
                    deleteIcon.style.width = '16px';
                    deleteIcon.style.height = '16px';
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

        fetch('/entrepreneurship/controllers/chat_controller.php', {
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
            fetch('../controllers/delete_message.php', {
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