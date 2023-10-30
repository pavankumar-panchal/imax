<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
//require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
 
try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    //$mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.mailgun.org';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'postmaster@demo.paycalci.com';                     // SMTP username
    $mail->Password   = '4744dc2c23b56baa1f40447ce7d5ce6c-75cd784d-a48a5e44';                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('saralgst@demo.paycalci.com', 'Relyon');
    $mail->addAddress('bhumipatel728@gmail.com', 'Bhumika');     // Add a recipient
   // $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('bhumika.p@relyonsoft.com', 'Information');
    //$mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                 // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->SMTPOptions = array(
'ssl' => array(
'verify_peer' => false,
'verify_peer_name' => false,
'allow_self_signed' => true
)
);
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>