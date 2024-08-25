<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


function sendInvoiceEmail($to, $invoiceNumber, $pdfPath, $customMessage = '') {
    $mail = new PHPMailer(true);
    try {
        //Server-Einstellungen
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Empfänger
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Anhänge
        $mail->addAttachment($pdfPath);

        // Inhalt
        $mail->isHTML(true);
        $mail->Subject = "Ihre Rechnung $invoiceNumber";
        $mail->Body = "Sehr geehrter Kunde,<br><br>
                       anbei finden Sie Ihre Rechnung $invoiceNumber als PDF-Anhang.<br><br>
                       $customMessage<br><br>
                       Mit freundlichen Grüßen<br>
                       Ihr Rechnungstool-Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "E-Mail konnte nicht gesendet werden. Mailer Fehler: {$mail->ErrorInfo}";
    }

    function hasPermission($role_id, $module_id) {
        global $conn;
        $permission = new Permission($conn);
        return $permission->checkPermission($role_id, $module_id);
    }
    
}
