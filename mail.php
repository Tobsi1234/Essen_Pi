<?php
require('phpmailer/PHPMailerAutoload.php');

$email = $_GET['email'];
$username = $_GET['username'];
$link = 'http://wir-haben-hunger.ddns.net/index.php?link='.$_GET['passwort'].'&email='.$email;

/*
$email = "tobias.steinbrueck@web.de";
$username = "Tobias";
$link = "www.google.de";*/


$mail = new PHPMailer;
$mail->isSMTP();

$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;

$mail->Username = "wir.haben.hunger.dhbw@gmail.com";
$mail->Password = "_1a2s3d_";
$mail->setFrom('wir.haben.hunger.dhbw@gmail.com', 'wir-haben-hunger');
$mail->addReplyTo('wir.haben.hunger.dhbw@gmail.com', 'wir-haben-hunger');

$mail->addAddress($email, '');

$mail->Subject = 'Account aktivieren';
$mail->Body = 'Hallo ' . $username . '!<br><br>Bitte klick folgenden Link um die Registrierung abzuschlie&szligen: ' .
$link .' . <br><br> Viel Spa&szlig, <br> Dein wir-haben-hunger Team';
$mail->AltBody = 'Fehler';

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: index.php?verifizieren");
}
?>