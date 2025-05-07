<?php
require_once '../config/data_base.php';
require_once '../models/Speechesmodel.php';

$speech_id = $_GET['id'] ?? null;

if ($speech_id) {
    $model = new SpeechesModel($pdo);
    $speech = $model->getSpeechById($speech_id); // Fetch the speech details for the given ID
    if ($speech === false || $speech === null) {
        // Speech not found, handle error gracefully
        echo "Speech not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $video = $_POST['video'];
    $amount = $_POST['amount'];
    $equity = $_POST['equity'];

    try {
        $stmt = $pdo->prepare("UPDATE speeches SET Titre = :title, video = :video, amount = :amount, equity = :equity WHERE ID_speech = :id");
        $stmt->execute([
            'title' => $title,
            'video' => $video,
            'amount' => $amount,
            'equity' => $equity,
            'id' => $speech_id
        ]);
        header("Location: indexpublic.php"); // Redirect after updating
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Speech</title>
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

        /* ✅ FULLSCREEN VIDEO */
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

        header, footer {
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

        .main-content {
            width: 80%;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px 25px;
            background: rgba(132, 108, 160, 0.85);
            border-radius: 16px;
            color: #f0e9ff;
            z-index: 10;
            position: relative;
            box-shadow: 0 8px 25px rgba(132, 108, 160, 0.7);
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
            width: 100%;
            margin-top: 15px;
        }

        .add-speech-btn:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            box-shadow: 0 8px 30px rgba(195, 183, 255, 0.9);
            color: #2f1a4a;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 2.8rem;
            font-weight: 700;
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }
    </style>
    <script>
        function updateSpeech(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(event.target);
            fetch('update_speech.php?id=<?php echo $speech_id; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'indexpublic.php'; // Redirect on success
                } else {
                    alert('Error updating speech');
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

    <div class="main-content">
        <h1>Update Speech</h1>
        <form onsubmit="updateSpeech(event)">
            <input type="text" name="title" value="<?php echo $speech['Titre']; ?>" required placeholder="Title">
            <input type="text" name="video" value="<?php echo $speech['video']; ?>" required placeholder="Video URL">
            <input type="number" name="amount" value="<?php echo $speech['amount']; ?>" required placeholder="Amount">
            <input type="number" name="equity" value="<?php echo $speech['equity']; ?>" required placeholder="Equity">
            <button type="submit" class="add-speech-btn">Update Speech</button>
        </form>
    </div>
</body>
</html>
