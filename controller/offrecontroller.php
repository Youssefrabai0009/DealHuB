<?php
require_once __DIR__.'/../config.php';
include __DIR__.'/../model/offre.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/autoload.php';
class offrecontroller
{
    public function listoffers($userId){
        $sql =  "SELECT o.*, c.libelle_categorie 
            FROM offres o
            JOIN categories c ON o.id_categorie = c.id_categorie
            WHERE o.user_id = :userId";;
        global $pdo;
        $query = $pdo->prepare($sql);

    try {
        $query->execute(['userId' => $userId]);
        return $query->fetchAll();
    } catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    }

    public function addOffer($offer)
{
    $sql = "INSERT INTO offres (user_id, montant, date_offre, statut, id_categorie) 
            VALUES (:user_id, :montant, :date_offre, :statut, :id_categorie)";
        global $pdo;
        $query = $pdo->prepare($sql);

    try {
        $query->execute([
            'user_id' => $offer->getIduser(), // <-- Assure-toi que cette méthode existe dans la classe Offre
            'montant' => $offer->getMontant(),
            'date_offre' => $offer->getDateOffre()->format('Y-m-d'),
            'statut' => $offer->getStatut(),
            'id_categorie' => $offer->getIdCategorie()
        ]);
    } catch(Exception $e) {
        die('Error :' . $e->getMessage());
    }
}

    public function deleteOffer($id_offre)
    {
        $sql ="DELETE FROM offres WHERE id_offre = :id_offre";
        global $pdo;
        $query = $pdo->prepare($sql);
    }

    public function showoffer($id_offre)
    {
        $sql = "SELECT * FROM offres WHERE id_offre = :id_offre";
        global $pdo;
        $query = $pdo->prepare($sql);
        try {
            $query->execute(['id_offre' => $id_offre]); 
            $offer = $query->fetch();
            return $offer; 
        } catch(Exception $e) {
            die('Error :' . $e->getMessage());
        }
    }

    public function updateoffer($offer, $id)
{
    $sql = "UPDATE offres SET user_id = :user_id, montant = :montant, date_offre = :date_offre, 
            statut = :statut, id_categorie = :id_categorie WHERE id_offre = :id_offre";
    
    global $pdo;
    $query = $pdo->prepare($sql);

    try {
        $query->execute([
            'user_id' => $offer->getIduser(), // <-- Assure-toi que cette méthode existe aussi
            'montant' => $offer->getMontant(),
            'date_offre' => $offer->getDateOffre()->format('Y-m-d'),
            'statut' => $offer->getStatut(),
            'id_categorie' => $offer->getIdCategorie(),
            'id_offre' => $id
        ]);
    } catch(Exception $e) {
        die('Error :' . $e->getMessage());
    }
}
public function getStatistiquesParCategorie()
{
    // Exécute une requête SQL pour récupérer les statistiques par catégorie
    $query = "SELECT categories.libelle_categorie, COUNT(offres.id_offre) AS nombre_offres
              FROM offres
              JOIN categories ON offres.id_categorie = categories.id_categorie
              GROUP BY categories.libelle_categorie";
    
    // Exécution de la requête
    global $pdo;
    $result =$pdo->query($query);
    
    // Tableau pour stocker les résultats
    $labels = [];
    $data = [];
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['libelle_categorie'];
        $data[] = $row['nombre_offres'];
    }
    
    return ['labels' => $labels, 'data' => $data];
}
public function getTotalInvestiParCategorie()
{
    global $pdo;
    $sql = "
      SELECT 
        c.libelle_categorie, 
        SUM(o.montant) AS total_investi
      FROM offres o
      JOIN categories c ON o.id_categorie = c.id_categorie
      GROUP BY c.libelle_categorie
    ";
    $stmt = $pdo->query($sql);
    $labels = [];
    $data   = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['libelle_categorie'];
        $data[]   = (float)$row['total_investi'];
    }
    return ['labels' => $labels, 'data' => $data];
}
function sendOfferAcceptedEmail($investisseurName, $investisseurEmail, $offerDetails) {
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'ramysnoussi@gmail.com';  
        $mail->Password = 'rgcfisrevtotkzax';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('ramysnoussi@gmail.com', 'DealHub');
        $mail->addAddress($investisseurEmail, $investisseurName);
        $mail->addEmbeddedImage('C:\xampp\htdocs\wissal\dealhub\controller\logo2.png', 'dealhub_logo');
        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre offre a ete acceptee!';
        $mail->Body    = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
          <meta charset='UTF-8'>
          <title>Votre offre a été acceptee</title>
        </head>
        <body style=\"margin:0;padding:0;font-family:Arial,sans-serif;\">
          <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f5f5f5;padding:20px 0;'>
            <tr>
              <td align='center'>
                <table role='presentation' width='600' cellpadding='0' cellspacing='0' style='background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
                  <!-- En-tête -->
                  <tr>
                    <td style='background-color:#FFFFFF;padding:20px;text-align:center;'>
                      <img src='cid:dealhub_logo' alt='DealHub' width='150' style='display:block;margin:0 auto;'>
                    </td>
                  </tr>
                  <!-- Corps du message -->
                  <tr>
                    <td style='padding:30px;color:#333;'>
                      <h1 style='font-size:20px;margin:0 0 15px;color:#2F1A4A;text-align:center;'>Felicitations, votre offre a ete acceptee !</h1>
                      <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                        Bonjour <strong>{$investisseurName}</strong>,
                      </p>
                      <p style='font-size:14px;line-height:1.6;margin:0 0 15px;'>
                        Votre offre a ete examinee et <span style='color:#28a745;font-weight:bold;'>acceptee</span>.  
                        Retrouvez ci-dessous le récapitulatif de votre proposition :
                      </p>
                      <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:20px;'>
                        <tr>
                          <td style='padding:8px 0;font-weight:bold;width:150px;'>Montant :</td>
                          <td style='padding:8px 0;'>{$offerDetails['montant']} DT</td>
                        </tr>
                        <tr>
                          <td style='padding:8px 0;font-weight:bold;'>Date :</td>
                          <td style='padding:8px 0;'>{$offerDetails['date_offre']}</td>
                        </tr>
                        <tr>
                          <td style='padding:8px 0;font-weight:bold;'>Statut :</td>
                          <td style='padding:8px 0;color:#28a745;font-weight:bold;'>Acceptee</td>
                        </tr>
                      </table>
                      <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                        Merci de votre confiance et a tres bientot sur DealHub !
                      </p>
                      <p style='font-size:12px;color:#777;text-align:center;margin:40px 0 0;'>
                        © " . date('Y') . " DealHub  Tous droits reserves
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        ";

        // Envoi de l'email
        $mail->send();
    } catch (Exception $e) {
        echo "Message non envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
function sendOfferEmail($investisseurName, $investisseurEmail, $offerDetails) {
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'ramysnoussi@gmail.com';  
        $mail->Password = 'rgcfisrevtotkzax';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('ramysnoussi@gmail.com', 'DealHub');
        $mail->addAddress($investisseurEmail, $investisseurName);
        $mail->addEmbeddedImage('C:\xampp\htdocs\wissal\dealhub\controller\logo2.png', 'dealhub_logo'); // 'dealhub_logo' is the Content-ID (CID)

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre offre a ete envoyee!';
        $mail->Body    = "
<!DOCTYPE html>
<html lang='fr'>
<head>
  <meta charset='UTF-8'>
  <title>Votre offre a bien ete envoyee</title>
</head>
<body style=\"margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f5f5f5;\">
  <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f5f5f5;padding:20px 0;'>
    <tr>
      <td align='center'>
        <table role='presentation' width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
          <!-- Header -->
           <tr>
            <td style='background-color:#ffffff;padding:20px;text-align:center;'>
              <img src='cid:dealhub_logo' alt='DealHub' width='150' style='display:block;margin:0 auto;'>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style='padding:30px;color:#333;'>
              <h1 style='font-size:20px;margin:0 0 15px;color:#2F1A4A;text-align:center;'>
                Votre offre a bien ete envoyee
              </h1>

              <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                Bonjour <strong>{$investisseurName}</strong>,
              </p>

              <p style='font-size:14px;line-height:1.6;margin:0 0 15px;'>
                Nous avons bien recu votre offre. Celle-ci est actuellement en attente de validation ; vous en serez informe des qu elle aura ete examinee.
              </p>

              <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:20px;'>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;width:150px;'>Montant :</td>
                  <td style='padding:8px 0;'>{$offerDetails['montant']} DT</td>
                </tr>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;'>Date :</td>
                  <td style='padding:8px 0;'>{$offerDetails['date_offre']}</td>
                </tr>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;'>Statut :</td>
                  <td style='padding:8px 0;color:#ffc107;font-weight:bold;'>En attente</td>
                </tr>
              </table>

              <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                Merci de votre confiance !<br>
                Lequipe <strong>DealHub</strong>
              </p>

              <p style='font-size:12px;color:#777;text-align:center;margin:40px 0 0;'>
                © " . date('Y') . " DealHub  Tous droits reserves
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
";

        // Envoi de l'email
        $mail->send();
    } catch (Exception $e) {
        echo "Message non envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
function sendOfferrefuseEmail($investisseurName, $investisseurEmail, $offerDetails) {
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'ramysnoussi@gmail.com';  
        $mail->Password = 'rgcfisrevtotkzax';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('ramysnoussi@gmail.com', 'DealHub');
        $mail->addAddress($investisseurEmail, $investisseurName);
        $mail->addEmbeddedImage('C:\xampp\htdocs\wissal\dealhub\controller\logo2.png', 'dealhub_logo'); // 'dealhub_logo' is the Content-ID (CID)

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre offre a ete refusee!';
        $mail->Body    = "
<!DOCTYPE html>
<html lang='fr'>
<head>
  <meta charset='UTF-8'>
  <title>Votre offre a ete refusee</title>
</head>
<body style=\"margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f5f5f5;\">
  <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f5f5f5;padding:20px 0;'>
    <tr>
      <td align='center'>
        <table role='presentation' width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
          <!-- Header -->
          <tr>
            <td style='background-color:#FFFFFF;padding:20px;text-align:center;'>
              <img src='cid:dealhub_logo' alt='DealHub' width='150' style='display:block;margin:0 auto;'>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style='padding:30px;color:#333;'>
              <h1 style='font-size:20px;margin:0 0 15px;color:#2F1A4A;text-align:center;'>
                Votre offre a malheureusement ete refusee
              </h1>

              <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                Bonjour <strong>{$investisseurName}</strong>,
              </p>

              <p style='font-size:14px;line-height:1.6;margin:0 0 15px;'>
                Nous sommes au regret de vous informer que votre offre n a pas ete acceptee.
              </p>

              <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:20px;'>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;width:150px;'>Montant :</td>
                  <td style='padding:8px 0;'>{$offerDetails['montant']} DT</td>
                </tr>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;'>Date :</td>
                  <td style='padding:8px 0;'>{$offerDetails['date_offre']}</td>
                </tr>
                <tr>
                  <td style='padding:8px 0;font-weight:bold;'>Statut :</td>
                  <td style='padding:8px 0;color:#dc3545;font-weight:bold;'>Refusee</td>
                </tr>
              </table>

              <p style='font-size:14px;line-height:1.6;margin:0 0 20px;'>
                Nous vous remercions pour votre interet et restons a votre disposition pour toute question.<br>
                Lequipe <strong>DealHub</strong>
              </p>

              <p style='font-size:12px;color:#777;text-align:center;margin:40px 0 0;'>
                © " . date('Y') . " DealHub  Tous droits reserves
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
";

        // Envoi de l'email
        $mail->send();
    } catch (Exception $e) {
        echo "Message non envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
public function getDerniereOffre()
{
    $sql = "SELECT o.*, c.libelle_categorie
            FROM offres o
            JOIN categories c ON o.id_categorie = c.id_categorie
            ORDER BY o.date_offre DESC
            LIMIT 1";
    global $pdo;
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function listoffres(): array {
    $sql = 'SELECT o.*, c.libelle_categorie
FROM offres o
JOIN categories c ON o.id_categorie = c.id_categorie
ORDER BY o.id_offre DESC
LIMIT 1;
';
    global $pdo;
    try {
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);   // <– renvoie un tableau
    } catch(Exception $e) {
        die('Error :' . $e->getMessage());
    }
}


}
