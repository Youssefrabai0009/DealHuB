<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']['id'])) {
    header('Location: /wissal/DealHuB/view/frontoffice/login.php');
    exit();
}
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../model/Speechesmodel.php';
// $speeches variable is expected to be passed from the controller
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

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
            color: black;
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

        .card {
            max-width: 600px;
            width: 100%;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(128, 90, 213, 0.2);
            background-color: #f5f3ff;
            border: 1px solid #d8b4fe;
        }

        .btn-primary {
            background-color: #846CA0;
            color: #F5F2F6;
        }
        .btn-primary:hover {
            background-color: #A093AF;
        }
        .btn-danger {
            background-color: #847C84;
            color: #F5F2F6;
        }
        .btn-danger:hover {
            background-color: #A093AF;
        }

        video.background-video {
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

        .container-center {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 900px;
            padding: 30px 40px;
            border-radius: 16px;
            color: #f0e9ff;
            text-align: left;
        }


        .complaint-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #6d28d9;
            margin-bottom: 0.5rem;
        }

        .complaint-content {
            color: #4c1d95;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .separator {
            border: none;
            border-top: 1px solid #ddd6fe;
            margin: 1.5rem 0;
        }

        .response {
            padding: 1rem;
            margin-top: 1rem;
            background-color: #ede9fe;
            border: 1px solid #c4b5fd;
            border-radius: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .response:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(139, 92, 246, 0.3);
        }

        .response p {
            color: #4c1d95;
            line-height: 1.6;
        }

        .response .date {
            font-size: 0.75rem;
            color: #7c3aed;
            margin-top: 0.5rem;
            text-align: right;
            font-style: italic;
        }

        .no-reply {
            color:rgb(228, 39, 39);
            font-style: italic;
            text-align: center;
            margin-top: 1rem;
            font-weight: bold;
        }

    </style>

    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

</head>

<body>
    <video class="background-video" autoplay loop muted>
        <source src="../../assets/background.mp4" type="video/mp4" />
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>

    <header>
        <div class="header-container container-custom">
            <span>DealHub</span>
            <nav>
                <a href="/wissal/DealHuB/view/frontoffice/accuil.php">Home</a>
                <a href="/wissal/DealHuB/view/frontoffice/entrepreneur.php">Profile</a>
                <a href="/wissal/DealHuB/view/backoffice/dash.php">dashboard</a> 
                <a href="/wissal/dealhub/view/frontoffice/login.html">sign out</a>
            </nav>
        </div>
    </header>

    <div class="main-content">
        <div class="pitch-container">
            <h1>Replies</h1>


            <div class="container-center">
                <div class="card">
                    
                    <!-- Titre de la réclamation -->
                    <h2 class="complaint-title"><?= htmlspecialchars($complaint['title']) ?></h2>
                    
                    <!-- Contenu de la réclamation -->
                    <p class="complaint-content"><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>

                    <hr class="separator" />

                    <!-- Affichage des réponses -->
                    <?php if (empty($replies)): ?>
                        <p class="no-reply">There are still no replies.</p>
                    <?php else: ?>
                        <?php foreach ($replies as $response): ?>
                            <div class="response">
                                <p><?= nl2br(htmlspecialchars($response['response_text'])) ?></p>
                                <p class="date"><?= $response['created_at'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>


        </div>
    </div>

    <footer>
        <p>[Mentions légales] | [Contact] | [Autres liens]</p>
    </footer>
</body>
</html>


