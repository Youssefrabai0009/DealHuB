<?php
require_once '../../config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

// Check if user is logged in and admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../view/frontoffice/login.html");
    exit;
}

// Fetch users
$stmt = $pdo->query("SELECT nom, prenom, email, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Path to the logo image
$logoPath = realpath('../../assets/logoweb.png');

// Check if the image exists
if ($logoPath === false) {
    die('Logo image not found!');
}

// Start building HTML for PDF
$html = '
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
    max-width: 150px;  /* Adjust the width as needed */
    height: auto;      /* Maintain aspect ratio */
}
        h1 {
            font-size: 24px;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
<img src="C:/xampp/htdocs/template/assets/logoweb.png" alt="DealHub Logo" class="logo">
        <h1>DealHub - Liste des utilisateurs</h1>
    </header>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>';

foreach ($users as $user) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($user['nom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['prenom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['email']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['role']) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>
    </table>
</body>
</html>';

// Dompdf setup
$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream the PDF (set Attachment to true for download)
$dompdf->stream("liste_utilisateurs.pdf", ["Attachment" => true]);
?>
