<?php

require_once('class.phpmailer.php');
require_once('PHPMailerAutoload.php');
$mail = new PHPMailer(true);

$mail->IsSMTP(); // telling the class to use SMTP

  try {
  $mail->Host       = "smtp.gmail.com"; 
  $mail->SMTPDebug  = 2;
  $mail->SMTPAuth   = true;
  $mail->Port       = 587;
  $mail->SMTPSecure = 'tls';
  $mail->Username   = "dimpal.phppoets@gmail.com";
  $mail->Password   = "hello@123";  
  $mail->AddAddress('dimpal.phppoets@gmail.com', 'Dimpal');
  $mail->SetFrom('dimpal.phppoets@gmail.com', 'Dimpal');
  
  $mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; 
  $mail->MsgHTML("<h1>Haiiiiii</h1>");

  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage();
} catch (Exception $e) {
  echo $e->getMessage(); 
} 
?>


