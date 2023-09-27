<?php 
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
 
// Include library files 
require '../PHPMailer/Exception.php'; 
require '../PHPMailer/PHPMailer.php'; 
require '../PHPMailer/SMTP.php';

// Create an instance; Pass `true` to enable exceptions 
$mail = new PHPMailer;
 
// Set email format to HTML 
$mail->isHTML(true); 
// Server settings 
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
$mail->isSMTP();                            // Set mailer to use SMTP 
$mail->Host = 'email-smtp.us-east-1.amazonaws.com';           // Specify main and backup SMTP servers 
$mail->SMTPAuth = true;                     // Enable SMTP authentication 
$mail->Username = 'AKIASFIP23VQBD5M3XM6';       // SMTP username 
$mail->Password = 'BFfVGDrPVsuyKJ5xOosbvxQoPhH017J6e2LymTWLbXDC';         // SMTP password 
$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 465;                  // TCP port to connect to 
 
// Sender info 
$mail->setFrom('noreplyimax@sppcloud.com', 'Relyon'); 
$mail->addReplyTo('bhumika.p@relyonsoft.com', 'Info'); 

//Add attachment
$mail->addAttachment($attachment);
if($imagepath!="")
    $mail->AddEmbeddedImage($imagepath, $cid,$altimgname);
if($rupeeimage!='')
    $mail->AddEmbeddedImage($rupeeimage, '1234567892','relyon-rupee-small.jpg');

// Add a recipient 
foreach ($emailids as $emailid) {
    //echo $emailid['email']; exit;
    $mail->addAddress($emailid['email']); 
}

foreach ($ccemailids as $ccemailid) {
    $mail->addCC($ccemailid['ccemail']);
}

foreach($bccemailids as $bccemailid)
{
    $mail->addBCC($bccemailid['bccemail']); 
}

$message = $msg;
$mail->MsgHTML($message);
// Mail subject 
$mail->Subject = $subject;
$mail->send();
 
// Mail body content 
//$bodyContent = '<h1>Welcome!</h1>'; 
//$bodyContent .= '<p>This HTML email is sent from the localhost server using PHP by <b>CodexWorld</b></p>'; 
//$mail->Body    = $bodyContent; 
 
//Send email 
// if(!$mail->send()) { 
//     echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
// } else { 
//     echo 'Message has been sent.'; 
// }
