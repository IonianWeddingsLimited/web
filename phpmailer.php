<?php
require_once 'PHPMailer-master/class.phpmailer.php';
$mail = new PHPMailer();
// Now you only need to add the necessary stuff
 
// HTML body
 
$body = "</pre><div>";
$body .= "Hello Dimitrios";
$body .= "<i>Your</i> personal photograph to this message.";
$body .= "Sincerely,";
$body .= "phpmailer test message ";
$body .= "</div>" ;
 
// And the absolute required configurations for sending HTML with attachement
$mail->IsSMTP();
$mail->Host				=	"localhost";
$mail->From				=	"payments@ionianweddings.co.uk";
$mail->FromName			=	"Ionain Weddings Ltd";
$mail->AddReplyTo('payments@ionianweddings.co.uk', 'Ionain Weddings Ltd');
$mail->AddAddress("gordon@zestisbest.net", "PO Test");
$mail->Subject			=	"PO Test";

$mail->MsgHTML($body);
$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/img/cal.gif");

if(!$mail->Send()) {
	echo "There was an error sending the message";
	exit;
}
echo "Message was sent successfully";
?>