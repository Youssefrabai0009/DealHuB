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

// Start building HTML for PDF
$html = '<h1 style="text-align:center;">Liste des utilisateurs</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="8" width="100%">';
$html .= '<thead><tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th></tr></thead><tbody>';

foreach ($users as $user) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($user['nom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['prenom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['email']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['role']) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Dompdf setup
$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("liste_utilisateurs.pdf", ["Attachment" => true]);
?>
