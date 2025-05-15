<?php
// export_offres.php
session_start();

// 1) Inclure Dompdf
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

// 2) Récupérer les offres
include __DIR__ . '/../../Controller/offrecontroller.php';
$ctrl = new offrecontroller();
$list = $ctrl->listoffers($_SESSION['user']['id']);

// 3) Construire le HTML du PDF (avec logo & styles)
$logoPath = 'C:\xampp\htdocs\wissal\dealhub\controller\logo.png'; // Mets ton image ici et adapte le chemin
$logoBase64 = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoBase64; // Vérifier que le logo est à cet emplacement
$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      color: #2F1A4A;
      margin: 0;
      padding: 0;
    }
    .header {
      background: #2F1A4A;
      color: #F5F2F6;
      padding: 20px;
      text-align: center;
    }
    .header img {
      height: 50px;
      vertical-align: middle;
    }
    .header h1 {
      display: inline-block;
      margin: 0 0 0 10px;
      font-size: 24px;
      vertical-align: middle;
    }
    .content {
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #A093AF;
      padding: 8px;
      text-align: center;
      font-size: 12px;
    }
    th {
      background: #846CA0;
      color: #FFF;
    }
    tr:nth-child(even) {
      background: #F5F2F6;
    }
    .footer {
      text-align: center;
      font-size: 10px;
      color: #847C84;
      margin-top: 30px;
    }
    .table-header {
      text-align: center;
      font-weight: bold;
    }
    .table-data {
      text-align: center;
    }
    .header img {
      margin-right: 15px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>DealHub — Historique des Offres</h1>
  </div>
  <div class="content">
    <table>
      <thead>
        <tr>
          <th class="table-header">Montant</th>
          <th class="table-header">Date</th>
          <th class="table-header">Statut</th>
          <th class="table-header">Catégorie</th>
        </tr>
      </thead>
      <tbody>';
foreach ($list as $o) {
    $html .= '
      <tr>
        <td class="table-data">'.htmlspecialchars($o['montant']).'</td>
        <td class="table-data">'.htmlspecialchars($o['date_offre']).'</td>
        <td class="table-data">'.htmlspecialchars($o['statut']).'</td>
        <td class="table-data">'.htmlspecialchars($o['libelle_categorie']).'</td>
      </tr>';
}
$html .= '
    </tbody>
    </table>
  </div>
  <div class="footer">Généré le ' . date('d/m/Y H:i') . ' — © ' . date('Y') . ' DealHub</div>
</body>
</html>';

// 4) Générer et stream le PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="historique_offres.pdf"');
echo $dompdf->output();
$dompdf->stream('historique_offres.pdf', ['Attachment' => 1]);
?>
