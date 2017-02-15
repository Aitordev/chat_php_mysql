<?php
require 'PHPMailerAutoload.php';
$location = $_SERVER['HTTP_REFERER'];

$from = ""; // Fill

$subject = "New Chat";

$emailbody = "";
if(isset($_POST['type']) && $_POST['type'] === "adminAdvise"){
    $emailbody .= "A Chat was iniciated, please check the link below. \n";
    $email = $from;
    $emailbody .= "User: " .  $_POST['user'] . "\n";
}
else{
    $subject = "Invitation to Chat";
    $emailbody .= "Invitation to Chat, click on the link below. \n";
    $email = $_POST['email'];
}
$emailbody .= "Link: " .  $_POST['link'] . "\n";

$to = $email;

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;
$mail->isSMTP();
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->Host = gethostbyname('mail.???.com'); // Fill
$mail->SMTPSecure = 'tls';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->Username = "??@??.com"; // Fill
$mail->Password = '???';  // Fill
$mail->setFrom($from);
$array = explode(',', $to);
foreach ($array as $val){
  $mail->addAddress($val);
}
$mail->Subject = $subject;
$mail->Body    = $emailbody;

echo '<script language="javascript">';
if ($to === null || $to === ""){
  echo 'alert("Nobody to send"); ';
}
elseif (!$mail->send()) {
    echo 'alert("Mailer Error"); ';
}
else{
    echo 'alert("Message sent!"); ';
}
echo "location.href='$location'; ";
echo '</script>';
exit;
?>
