<?php

function send_mail($from,$name,$message,$subject,$to)
{
$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: ". $from . "\r\n";
//$headers .= "CC: info@enssurem.in\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$messageFormate = '<html><body>';
$messageFormate .= '<img src="https://spacingo.com/public/images/spacingo_logo.PNG" alt="Spacingo Logo" />';
$messageFormate .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$messageFormate .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" .$name . "</td></tr>";
$messageFormate .= "<tr><td><strong>Email:</strong> </td><td>" .$to . "</td></tr>";
$messageFormate .= "<tr><td><strong>Information:</strong> </td><td>" . $message . "</td></tr>";
$messageFormate .= "<tr><td>
 </td><td></td></tr>";
$messageFormate .= "</table>";
$messageFormate .= "<br>Spacingo Customer Care<br>+91 8762431943<br>care@spacingo.com";
$messageFormate .= "</body></html>";
if (mail($to, $subject, $messageFormate, $headers)) {
              //echo 'Your message has been sent.';
} else {
              //echo 'There was a problem sending the email.';
}

}


?>