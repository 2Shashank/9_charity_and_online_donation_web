<?php 
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$to = "nagarajam01011974@gmail.com";
$subject = "Mail form Donation web";

$headers = "From: " .$name. "\r\n" .
"CC: hamimkabir@hamimkabir.webhotingindia.racing";
$txt = "You recieved message form :" .$name ."\r\nEmail:" .$email ."\r\nMessage: " . $message;
$message = "Messaging from ..";
if($email != NULL){
    mail($to, $subject, $txt, $email);
}
header('Location: index.php');

?>