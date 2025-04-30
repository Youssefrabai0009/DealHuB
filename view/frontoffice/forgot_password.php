<?php
session_start();
require_once '../../config.php';
// Include PHPMailer
require '../../vendor/autoload.php'; // Adjust path if vendor is in a different directory
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        header("Location: forgot_password.html?error=Veuillez entrer votre email.");
        exit;
    }

    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a random 6-digit code
            $reset_code = sprintf("%06d", random_int(0, 999999));

            // Store the reset code and email in the session
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_code'] = $reset_code;
            $_SESSION['reset_code_expires'] = time() + (30 * 60); // Expires in 30 minutes

            // Send the email using PHPMailer
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                // SMTP Configuration (Replace with your provider's settings)
                // Example for Gmail
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'hamraouissamar@gmail.com'; // Replace with your Gmail address
                $mail->Password = 'xosxecybduvlwidj'; // Replace with your App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Alternative: SendGrid
                /*
                $mail->Host = 'smtp.sendgrid.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'apikey';
                $mail->Password = 'your_sendgrid_api_key'; // Replace with your SendGrid API key
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                */

                $mail->setFrom('no-reply@dealhub.com', 'DealHub');
                $mail->addAddress($email);
                $mail->Subject = 'Réinitialisation de votre mot de passe';
                $mail->Body = "Votre code de réinitialisation est : $reset_code\nCe code est valable pendant 30 minutes.";
                $mail->isHTML(false); // Plain text email

                $mail->send();
                header("Location: reset_password.html");
            } catch (Exception $e) {
                unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_code_expires']);
                header("Location: forgot_password.html?error=Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}");
            }
        } else {
            header("Location: forgot_password.html?error=Cet email n'existe pas.");
        }
    } catch (PDOException $e) {
        header("Location: forgot_password.html?error=Erreur lors du traitement. Veuillez réessayer.");
    }
}
?>