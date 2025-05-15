<?php
require_once '../../config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: ../../view/frontoffice/login.html");
    exit;
}

// Fetch users
$stmt = $pdo->query("SELECT nom, prenom, email, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Path to the logo image
$logoPath = realpath('../../assets/logoweb2.png');
if (!$logoPath || !file_exists($logoPath)) {
    die('Logo image not found!');
}
$logoBase64 = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoBase64;

// Start building HTML for PDF
$html = '
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
        }

        h1 {
            font-size: 24px;
            color: #0056b3;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <header>
        <img src="' . $logoSrc . '" alt="DealHub Logo" class="logo">
        <h1>DealHub - Liste des Utilisateurs</h1>
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

$html .= '
        </tbody>
    </table>
</body>
</html>';

// Setup Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true); // Important for image rendering

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the PDF
$dompdf->stream("liste_utilisateurs.pdf", ["Attachment" => true]);
