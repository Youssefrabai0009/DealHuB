<?php
// scan.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';    // si vous utilisez Composer et PHPMailer
// ou include('path/to/PHPMailer.php') si vous l’avez téléchargé manuellement

include __DIR__ . '/../../Controller/offrecontroller.php';

$offreCtrl = new offrecontroller();
// Récupère la dernière offre, à adapter selon votre modèle :
$lastOffer = $offreCtrl->getDerniereOffre();  // vous devrez ajouter cette méthode

if (!$lastOffer) {
    die("Aucune offre trouvée.");
}

// Préparation du mail
$mail = new PHPMailer(true);
try {
    // Configuration SMTP (à adapter à votre serveur)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ramysnoussi@gmail.com';
    $mail->Password   = 'rgcfisrevtotkzax';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Expéditeur & destinataire
    $mail->setFrom('ramysnoussi@gmail.com', 'DealHub');
    $mail->addAddress('rami.snoussi@esprit.tn', 'snoussi rami');

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = 'Votre derniere offre sur DealHub';
    $mail->Body    = "
      <h1>Derniere Offre</h1>
      <p><strong>Montant :</strong> {$lastOffer['montant']} DT</p>
      <p><strong>Date :</strong> {$lastOffer['date_offre']}</p>
      <p><strong>Statut :</strong> {$lastOffer['statut']}</p>
      <p><strong>Categorie :</strong> {$lastOffer['libelle_categorie']}</p>
    ";

    $mail->send();
    echo "<p style='text-align:center;margin-top:50px;'>E-mail envoyé avec succès !</p>";
} catch (Exception $e) {
    echo "Le message n’a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}";
}
