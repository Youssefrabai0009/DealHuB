<?php
require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../models/Speechesmodel.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mon Pitch</title>

    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

        /* Reset and base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(135deg, #1e1e2f, #2f1a4a);
            color: #e0d7f5;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(47, 26, 74, 0.85);
            backdrop-filter: blur(6px);
            z-index: -1;
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -2;
            filter: brightness(0.6) saturate(1.2);
            transition: filter 0.5s ease;
        }

        header,
        footer {
            background: linear-gradient(90deg, #3a1a6a, #5a3a9e);
            color: #e0d7f5;
            padding: 20px 0;
            position: relative;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 85%;
            margin: auto;
            max-width: 1200px;
        }

        .header-container span {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #f0e9ff;
            text-shadow: 0 0 8px #a18aff;
        }

        .header-container nav a {
            color: #dcd6f7;
            text-decoration: none;
            margin: 0 18px;
            font-weight: 500;
            font-size: 1rem;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .header-container nav a:hover {
            background: #a18aff;
            color: #2f1a4a;
            box-shadow: 0 0 8px #a18aff;
        }

        .main-content {
            display: flex;
            width: 85%;
            margin: 30px auto 50px;
            justify-content: center;
            align-items: flex-start;
            max-width: 1200px;
            gap: 30px;
        }

        .pitch-container {
            width: 65%;
            text-align: left;
            position: relative;
        }

        .pitch-container h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }

        .pitch-video {
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(161, 138, 255, 0.5);
            transition: transform 0.3s ease;
            background: #2f1a4a;
        }

        .pitch-video:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 30px rgba(161, 138, 255, 0.8);
        }

        iframe {
            border: none;
            border-radius: 16px;
        }

        .widget {
            background: rgba(132, 108, 160, 0.85);
            padding: 18px 20px;
            margin-top: 12px;
            color: #f0e9ff;
            border-radius: 16px;
            font-weight: 500;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(132, 108, 160, 0.6);
            user-select: none;
        }

        .boost-btn {
            padding: 14px 28px;
            background: linear-gradient(135deg, #6a4a9e, #a18aff);
            color: #2f1a4a;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.4s ease, color 0.4s ease, box-shadow 0.4s ease;
            width: 160px;
            margin-right: 12px;
            box-shadow: 0 4px 12px rgba(161, 138, 255, 0.6);
            user-select: none;
        }

        .boost-btn:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            color: #2f1a4a;
            box-shadow: 0 6px 20px rgba(195, 183, 255, 0.9);
        }

        .button-container {
            display: inline-flex;
            justify-content: flex-start;
            margin-top: 25px;
        }

        .add-speech-btn {
            position: absolute;
            right: 0;
            top: 0;
            padding: 12px 26px;
            background: linear-gradient(135deg, #3a1a6a, #5a3a9e);
            color: #f0e9ff;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(90, 58, 158, 0.7);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        .add-speech-btn:hover {
            background: linear-gradient(135deg, #5a3a9e, #7a5aff);
            box-shadow: 0 6px 20px rgba(122, 90, 255, 0.9);
        }
    </style>

    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <!-- JavaScript Functions -->
    <script>
        // Delete speech function
        function deleteSpeech(speechId) {
            if (confirm('Are you sure you want to delete this speech?')) {
                fetch('/entrepreneurship/controllers/delete_speech.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + speechId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert('Error deleting speech: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting speech');
                });
            }
        }

        // Generate contract PDF
        async function generateContractPdf(equity, amount) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            function loadImage(url) {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.crossOrigin = "Anonymous";
                    img.onload = () => resolve(img);
                    img.onerror = reject;
                    img.src = url;
                });
            }

            // Load background image
            const bgUrl = '/entrepreneurship/views/images/pdfbg.jpg';
            const bgImg = await loadImage(bgUrl);
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            doc.addImage(bgImg, 'JPEG', 0, 0, pageWidth, pageHeight);

            // Load logo image
            const logoUrl = '/entrepreneurship/views/images/logoimage11.png';
            const logoImg = await loadImage(logoUrl);
            const logoProps = doc.getImageProperties(logoImg);
            const logoWidth = 50;
            const logoHeight = (logoProps.height * logoWidth) / logoProps.width;
            doc.addImage(logoImg, 'PNG', 80, 10, logoWidth, logoHeight);

            // Set white text color
            doc.setTextColor(255, 255, 255);

            // Title
            doc.setFontSize(26);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(0, 0, 139); // Dark blue color for title
            doc.text(" Investment Contract", 105, 40, null, null, "center");

            // Draw a dark blue underline below the title
            doc.setDrawColor(0, 0, 139);
            doc.setLineWidth(1.5);
            doc.line(40, 45, 170, 45);

            // Contract content styling
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(255, 255, 255);
            // Split the first paragraph into two lines for better fit
            doc.text("This Contract is entered into by and between the Investor and the Entrepreneur", 15, 50);

            // Section 1 header
            doc.setFontSize(16);
            doc.setFont("helvetica", "bolditalic");
            doc.setTextColor(0, 0, 139);
            doc.text("1. Investment Terms", 15, 85);

            // Investment terms details with increased line spacing
            doc.setFontSize(14);
            doc.setFont("helvetica", "normal");
            doc.setTextColor(255, 255, 255);
            doc.text(`Equity Offered: ${equity}%`, 20, 95);
            doc.text(`Amount Sought: €${amount}`, 20, 110);

            // Paragraph below terms with increased line spacing and split into multiple lines
            doc.setFontSize(12);
            doc.setFont("helvetica", "italic");
            const para1 = `The Entrepreneur agrees to transfer ${equity}% of the company’s equity in exchange for a financial investment of twelve euros (€${amount}) from the Investor.`;
            const para2 = "This agreement signifies both parties’ mutual commitment to the outlined terms.";
            const splitPara1 = doc.splitTextToSize(para1, 180);
            const splitPara2 = doc.splitTextToSize(para2, 180);
            doc.text(splitPara1, 15, 120);
            doc.text(splitPara2, 15, 130);

            // Section 2 header
            doc.setFontSize(16);
            doc.setFont("helvetica", "bolditalic");
            doc.setTextColor(0, 0, 139);
            doc.text("2. Agreement", 15, 145);

            // Agreement paragraph with increased line spacing and split into multiple lines
            doc.setFontSize(12);
            doc.setFont("helvetica", "normal");
            doc.setTextColor(255, 255, 255);
            const agreement1 = "By signing this contract, both parties confirm that they understand and agree to the terms stated above.";
            const agreement2 = "This contract becomes effective upon the signatures of both the Investor and the Entrepreneur.";
            const splitAgreement1 = doc.splitTextToSize(agreement1, 180);
            const splitAgreement2 = doc.splitTextToSize(agreement2, 180);
            doc.text(splitAgreement1, 15, 155);
            doc.text(splitAgreement2, 15, 165);

            // Signatures section
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(0, 0, 139);
            doc.text("Signatures", 15, 175);

            // Signature lines
            doc.setDrawColor(0, 0, 139);
            doc.setLineWidth(0.8);
            doc.line(15, 185, 90, 185); // Investor line
            doc.line(120, 185, 195, 185); // Entrepreneur line

            // Signature labels
            doc.setFontSize(12);
            doc.setFont("helvetica", "normal");
            doc.setTextColor(255, 255, 255);
            doc.text("Investor Signature", 15, 195);
            doc.text("Entrepreneur Signature", 120, 195);

            // Prompt Save As dialog
            doc.save("Investment_Contract.pdf");
        }

        function contractButtonClicked(equity, amount) {
            generateContractPdf(equity, amount).catch(console.error);
        }
    </script>
</head>

<body>
    <video autoplay muted loop playsinline id="bg-video">
        <source src="C:\xampp\htdocs\entrepreneurship\views\video\backgroundloop.mp4" type="video/mp4" />
        Votre navigateur ne supporte pas la vidéo HTML5.
    </video>

    <header>
        <div class="header-container container-custom">
            <span>DealHub</span>
            <nav>
                <a href="#">Home</a>
                <a href="#">Profile</a>
                <a href="#">Sign out</a>
                <a href="/entrepreneurship/views/dash.php">Backoffice</a>
            </nav>
        </div>
    </header>

    <div class="main-content">
        <div class="pitch-container">
            <h1>My speech</h1>
            <button class="add-speech-btn" onclick="window.location.href='/entrepreneurship/views/add_speech.php'">Add Speech</button>

            <?php
            $entrepreneur_id = 2; // Temporary value
            $speeches = (new SpeechesModel($pdo))->showMySpeeches($entrepreneur_id);
            foreach ($speeches as $speech) {
                // Extract video ID from the YouTube URL
                parse_str(parse_url($speech['video'], PHP_URL_QUERY), $video_params);
                $video_id = $video_params['v'] ?? '';

                $boosted = !empty($speech['boosted']) && $speech['boosted'] == 1;

                echo "<div class='pitch-video' data-speech-id='{$speech['ID_speech']}'>";
                echo "<iframe width='100%' height='315' src='https://www.youtube.com/embed/{$video_id}' frameborder='0' allowfullscreen></iframe>";
                echo "<div class='widget'>
                        💰 Equity offerte : {$speech['equity']}% | 💵 Montant recherché : {$speech['amount']}€
                      </div>";
                echo "<div class='button-container'>";
                echo "<button class='boost-btn' onclick='deleteSpeech({$speech['ID_speech']})'>Delete Speech</button>";
                echo "<a href='/entrepreneurship/views/update_speech.php?id={$speech['ID_speech']}' class='boost-btn'>Update Speech</a>";
                echo "<button class='boost-btn' onclick='contractButtonClicked({$speech['equity']}, {$speech['amount']})'>Contract</button>";

                if ($boosted) {
                    echo "<button class='boost-btn' style='background-color: green; cursor: default;' disabled>Boosted</button>";
                } else {
                    echo "<button class='boost-btn' onclick='boostSpeech({$speech['ID_speech']})'>Boost</button>";
                }

                echo "</div>";

                // Add offers container for this speech
                echo "<div class='widget offers-container' id='offers-{$speech['ID_speech']}' style='margin-top: 20px;'>";
                echo "<h3>Offers</h3>";
                echo "<div class='offers-content'>Loading offers...</div>";
                echo "</div>";
                echo "<div class='widget stats-container' id='stats-{$speech['ID_speech']}' style='margin-top: 20px;'>";
                echo "<h3>Stats</h3>";
                echo "<div class='stats-content'>Loading stats...</div>";
                echo "</div>";

                echo "</div>";
            }
            ?>
        </div>

        <script>
            // Boost speech function
            function boostSpeech(speechId) {
                window.location.href = '/entrepreneurship/views/boost_speech.php?id=' + speechId;
            }

            // Show success or cancel messages
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('boost_success')) {
                alert('Payment successful! Your speech has been boosted.');
                // Remove query params from URL
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if (urlParams.has('boost_cancel')) {
                alert('Payment cancelled. Your speech was not boosted.');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        </script>

        <div class="chat-container widget" style="width: 30%; margin-left: 20px; display: flex; flex-direction: column; height: 500px;">
            <h2>Chat</h2>
            <div id="chat-messages" style="flex-grow: 1; overflow-y: auto; background: rgba(47, 26, 74, 0.8); padding: 10px; border-radius: 10px; color: #F5F2F6; font-size: 14px;"></div>
            <form id="chat-form" style="margin-top: 10px; display: flex;">
                <input type="hidden" id="id_speech" name="ID_speech" value="<?php echo $speeches[0]['ID_speech'] ?? 0; ?>" />
                <input type="text" id="chat-input" name="message" placeholder="Type your message..." style="flex-grow: 1; padding: 8px; border-radius: 5px; border: none; font-size: 14px;" required />
                <button type="submit" style="padding: 8px 15px; margin-left: 10px; background-color: #2F1A4A; color: #F5F2F6; border: none; border-radius: 5px; cursor: pointer;">Send</button>
            </form>
        </div>

        <script>
            // Chat functionality
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
                            messageElement.innerHTML = '<strong>Pitch: ' + escapeHtml(msg.title || msg.ID_speech) + '</strong>: ' + escapeHtml(msg.message) + '<br><small style="color:#ccc;">' + new Date(msg.sent_at).toLocaleString() + '</small>';
                            chatMessages.appendChild(messageElement);
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
        </script>

        <script>
            // Embed offers data from PHP
            const offersData = <?php
                $stmt = $pdo->query("SELECT speechnumber, amount, equity, investor_name FROM offer");
                $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($offers);
            ?>;

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

            function renderOffers() {
                const pitchVideos = document.querySelectorAll('.pitch-video');
                pitchVideos.forEach(pitch => {
                    const speechId = pitch.getAttribute('data-speech-id');
                    const offersContainer = document.getElementById('offers-' + speechId);
                    if (!offersContainer) return;

                    // Add sorting controls if not already added
                    if (!offersContainer.querySelector('.sort-control')) {
                        const sortControlDiv = document.createElement('div');
                        sortControlDiv.className = 'sort-control';
                        sortControlDiv.style.marginBottom = '10px';
                        sortControlDiv.innerHTML = `
                            <button id="sort-equity-${speechId}" style="padding: 6px 12px; margin-right: 10px; border-radius: 6px; border: none; background: #6a4a9e; color: #f0e9ff; cursor: pointer;">Sort by Equity</button>
                            <button id="sort-amount-${speechId}" style="padding: 6px 12px; border-radius: 6px; border: none; background: #6a4a9e; color: #f0e9ff; cursor: pointer;">Sort by Amount</button>
                        `;
                        offersContainer.insertBefore(sortControlDiv, offersContainer.firstChild);

                        // Add event listeners for sorting buttons
                        const sortEquityBtn = sortControlDiv.querySelector(`#sort-equity-${speechId}`);
                        const sortAmountBtn = sortControlDiv.querySelector(`#sort-amount-${speechId}`);

                        sortEquityBtn.addEventListener('click', () => {
                            renderOffersSorted(speechId, 'equity');
                        });

                        sortAmountBtn.addEventListener('click', () => {
                            renderOffersSorted(speechId, 'amount');
                        });
                    }

                    // Initial render without sorting
                    renderOffersSorted(speechId, null);
                });
            }

            function renderOffersSorted(speechId, sortBy) {
                const offersContainer = document.getElementById('offers-' + speechId);
                if (!offersContainer) return;
                const offersContent = offersContainer.querySelector('.offers-content');

                let filteredOffers = offersData.filter(offer => offer.speechnumber == speechId);

                if (filteredOffers.length === 0) {
                    offersContent.innerHTML = '<p>No offers yet.</p>';
                    return;
                }

                if (sortBy === 'equity') {
                    filteredOffers.sort((a, b) => a.equity - b.equity);
                } else if (sortBy === 'amount') {
                    filteredOffers.sort((a, b) => a.amount - b.amount);
                }

                let tableHtml = '<table style="width: 100%; border-collapse: collapse; color: #f0e9ff;">';
                tableHtml += '<thead><tr><th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Investor</th><th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Amount (€)</th><th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Equity (%)</th><th style="border-bottom: 1px solid #a18aff; padding: 8px; text-align: left;">Action</th></tr></thead><tbody>';

                filteredOffers.forEach(offer => {
                    tableHtml += '<tr>';
                    tableHtml += '<td style="padding: 8px; border-bottom: 1px solid #a18aff;">' + escapeHtml(offer.investor_name) + '</td>';
                    tableHtml += '<td style="padding: 8px; border-bottom: 1px solid #a18aff;">' + escapeHtml(offer.amount.toString()) + '</td>';
                    tableHtml += '<td style="padding: 8px; border-bottom: 1px solid #a18aff;">' + escapeHtml(offer.equity.toString()) + '</td>';
                    // Add button to send message via Twilio
                    tableHtml += '<td style="padding: 8px; border-bottom: 1px solid #a18aff;"><button class="boost-btn" onclick="sendMessageToInvestor(\'' + encodeURIComponent(offer.investor_name) + '\')">Send Message</button></td>';
                    tableHtml += '</tr>';
                });

                tableHtml += '</tbody></table>';

                offersContent.innerHTML = tableHtml;
            }

            // Function to send message to investor using Twilio via backend API
            function sendMessageToInvestor(investorName) {
                const message = prompt("Enter the message to send to " + decodeURIComponent(investorName) + ":");
                if (!message) return;

                fetch('/entrepreneurship/controllers/send_twilio_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ investor_name: decodeURIComponent(investorName), message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Message sent successfully to ' + decodeURIComponent(investorName));
                    } else {
                        alert('Failed to send message: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Error sending message');
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                renderOffers();
                renderStats();
            });

            // Render stats charts for each speech
            function renderStats() {
                const pitchVideos = document.querySelectorAll('.pitch-video');
                pitchVideos.forEach(pitch => {
                    const speechId = pitch.getAttribute('data-speech-id');
                    const statsContainer = document.getElementById('stats-' + speechId);
                    if (!statsContainer) return;
                    const statsContent = statsContainer.querySelector('.stats-content');

                    const filteredOffers = offersData.filter(offer => offer.speechnumber == speechId);

                    if (filteredOffers.length === 0) {
                        statsContent.innerHTML = '<p>No stats available.</p>';
                        return;
                    }

                    // Clear previous content
                    statsContent.innerHTML = '<canvas id="chart-' + speechId + '" style="max-width: 100%; height: 300px;"></canvas>';

                    const ctx = document.getElementById('chart-' + speechId).getContext('2d');

                    // Prepare data for Chart.js
                    const labels = filteredOffers.map(offer => offer.investor_name);
                    const equityData = filteredOffers.map(offer => offer.equity);
                    const amountData = filteredOffers.map(offer => offer.amount);

                    // Destroy previous chart instance if exists to avoid duplication
                    if (statsContent.chartInstance) {
                        statsContent.chartInstance.destroy();
                    }

                    statsContent.chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Equity (%)',
                                    data: equityData,
                                    backgroundColor: 'rgba(128, 0, 128, 0.7)', // purple
                                    yAxisID: 'yEquity',
                                },
                                {
                                    label: 'Amount (€)',
                                    data: amountData,
                                    backgroundColor: 'rgba(0, 0, 139, 0.7)', // dark blue
                                    yAxisID: 'yAmount',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yEquity: {
                                    type: 'linear',
                                    position: 'left',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Equity (%)'
                                    },
                                    ticks: {
                                        color: '#a18aff'
                                    }
                                },
                                yAmount: {
                                    type: 'linear',
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                    title: {
                                        display: true,
                                        text: 'Amount (€)'
                                    },
                                    ticks: {
                                        color: '#a18aff'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#f0e9ff'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#f0e9ff'
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                });
            }
        </script>
    </div>

    <footer>
        <p>[Mentions légales] | [Contact] | [Autres liens]</p>
    </footer>
</body>
</html>
