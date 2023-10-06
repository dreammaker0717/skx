<?php
//phpinfo();
  /*$to_email = "rashidtahir05@gmail.com";
  $subject = "Test mail";
  $body = "Hi,\n This is test email send by PHP Script";
  $headers = "From: info@gmail.com";

  if ( mail($to_email, $subject, $body, $headers)) {
     echo("Email successfully sent to $to_email...");
  } else {
     echo("Email sending failed...");
  }*/

  //imap_mail ("rashidtahir05@gmail.com","Test", "Hello");

    /*    $to_address = "rashidtahir05@gmail.com";
        $from_address = "abc@gmail.com";
        $subject = "Test_subject";

        //Sending a mail
        $res =  imap_mail($to_address, $from_address, $subject);
        echo $res;
        if($res){
           print("Mail sent successfully");
        }else{
           print("Error Occurred");
        }
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "auth.smtp.1and1.co.uk"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
$mail->Port = 587; // TLS only
$mail->SMTPSecure = 'tls'; // ssl is depracated
$mail->SMTPAuth = true;
$mail->Username = 'send@service-x.uk';
$mail->Password = '123Donkey!';
$mail->setFrom('info@service-x.uk','ServiceX');
$emailTo = 'rashidtahir05@gmail.com';
$emailToName = 'Rashid Ahmed';
$mail->addAddress($emailTo, $emailToName);
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->msgHTML("test body with attachment"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
$mail->AltBody = 'HTML messaging not supported';
$mail->addAttachment('test.php'); //Attach an image file

if(!$mail->send()){
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
}


 ?>
