<?php
require_once '../../config.php';
require_once '../../model/Speechesmodel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $title = $_POST['title'];
    $video = $_POST['video'];
    $amount = $_POST['amount'];
    $equity = $_POST['equity'];
    $entrepreneur_id = $_SESSION['user']['id']; // Use logged-in user ID from session

    try {
        $stmt = $pdo->prepare("INSERT INTO speeches (Titre, video, amount, equity, entrepreneur_id) VALUES (:title, :video, :amount, :equity, :entrepreneur_id)");
        $stmt->execute([
            'title' => $title,
            'video' => $video,
            'amount' => $amount,
            'equity' => $equity,
            'entrepreneur_id' => $entrepreneur_id
        ]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit; // Prevent further output
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Speech</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

        /* Reset and base */
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            overflow-x: hidden;
            color: #e0d7f5;
            background: linear-gradient(135deg, #1e1e2f, #2f1a4a);
            min-height: 100vh;
            text-align: center;
            position: relative;
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

        /* ✅ FULLSCREEN BACKGROUND VIDEO */
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

        header {
            background: linear-gradient(90deg, #3a1a6a, #5a3a9e);
            color: #e0d7f5;
            padding: 20px 0;
            position: relative;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
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

        .main-form {
            width: 80%;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px 25px;
            background: rgba(132, 108, 160, 0.85);
            border-radius: 16px;
            position: relative;
            z-index: 10;
            box-shadow: 0 8px 25px rgba(132, 108, 160, 0.7);
            color: #f0e9ff;
            font-weight: 500;
            font-size: 1.1rem;
        }

        input {
            padding: 12px 15px;
            margin: 15px 0;
            width: 100%;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            background: #2f1a4a;
            color: #f0e9ff;
            box-shadow: inset 0 0 8px rgba(161, 138, 255, 0.5);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            outline: none;
            background: #3a2a6a;
            box-shadow: 0 0 12px #a18aff;
            color: #fff;
        }

        .add-speech-btn {
            padding: 14px 28px;
            background: linear-gradient(135deg, #6a4a9e, #a18aff);
            color: #2f1a4a;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(161, 138, 255, 0.7);
            transition: background 0.4s ease, box-shadow 0.4s ease;
            user-select: none;
            margin-top: 15px;
            width: 100%;
        }

        .add-speech-btn:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            box-shadow: 0 8px 30px rgba(195, 183, 255, 0.9);
            color: #2f1a4a;
        }

        h1 {
            margin-top: 30px;
            z-index: 10;
            position: relative;
            font-size: 2.8rem;
            font-weight: 700;
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }
    </style>
    <script>
        function validateForm(event) {
            const title = document.querySelector('input[name="title"]').value;
            const videoUrl = document.querySelector('input[name="video"]').value;
            const amount = parseFloat(document.querySelector('input[name="amount"]').value);
            const equity = parseFloat(document.querySelector('input[name="equity"]').value);

            if (title.length <= 4) {
                alert('Title must be more than 4 characters long.');
                event.preventDefault();
                return false;
            }

            const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/;
            if (!youtubeRegex.test(videoUrl)) {
                alert('Please enter a valid YouTube video URL.');
                event.preventDefault();
                return false;
            }

            if (isNaN(amount) || amount <= 0) {
                alert('Amount must be a positive number.');
                event.preventDefault();
                return false;
            }

            if (isNaN(equity) || equity <= 0.1 || equity >= 100) {
                alert('Equity must be a number between 0.1 and 100.');
                event.preventDefault();
                return false;
            }

            return true;
        }

        function submitForm(event) {
            event.preventDefault();
            if (!validateForm(event)) return;

            const formData = new FormData(event.target);
            fetch('add_speech.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'indexpublic.php';
                } else {
                    alert('Error adding speech: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <video autoplay loop muted>
        <source src="video/backgroundloop.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <header>
        <div class="header-container">
            <span>DealHub</span>
            <nav>
                <a href="#">Home</a>
                <a href="#">Profile</a>
                <a href="#">Sign out</a>
                <a href="dash.php">Backoffice</a>
            </nav>
        </div>
    </header>

    <h1>Add New Speech</h1>
    <form class="main-form" method="POST" action="add_speech.php" onsubmit="submitForm(event);">
        <input type="text" name="title" required placeholder="Title">
        <input type="text" name="video" required placeholder="Video URL">
        <input type="number" name="amount" required placeholder="Amount">
        <input type="number" name="equity" required placeholder="Equity">
        <button type="submit" class="add-speech-btn">Add Speech</button>
    </form>
</body>
</html>