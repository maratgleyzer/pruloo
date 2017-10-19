<?php

$oid = (isset($_POST['o']) && ($_POST['o'] > 0) ? $_POST['o'] : false);
$total = (isset($_POST['t']) && ($_POST['t'] > 0) ? $_POST['t'] : false);
$email = (isset($_POST['e']) && (strlen($_POST['e']) > 0) ? $_POST['e'] : false);
$first = (isset($_POST['f']) && (strlen($_POST['f']) > 0) ? $_POST['f'] : false);
$last = (isset($_POST['l']) && (strlen($_POST['l']) > 0) ? $_POST['l'] : false);
$username = (isset($_POST['u']) && (strlen($_POST['u']) > 0) ? $_POST['u'] : false);
$password = (isset($_POST['p']) && (strlen($_POST['p']) > 0) ? $_POST['p'] : false);

if ($oid == false || $email == false || $first == false ||
	$last == false || $username == false || $password == false) {
	echo "failed"; exit;
}

$mail_body =
"

Dear $first $last,

Congratulations and thank you for signing up for your instant access to EZ Profit System. Your Order id number is: $oid

We look forward to working with you and helping you develop your online marketing techniques through our membership site.

Below, you should find your login username and password:

Username: $username
Password: $password

Follow this link to the login page and log into your membership:
http://ezdollarclub.com/

================================================================

The total of your trial order is: \$$total

Call us at (800) 609-8384 if you have any questions! For more details on your trial go to: http://ezdollarclub.com/p/terms.html

Sincerely,
EZ Profit System

";

$subject = "$first, login and order confirmation.."; //subject
$header = "From: Support EZDollar Club <support@ezdollarclub.com>\r\n"; //optional headerfields

echo mail($email, $subject, $mail_body, $header);
exit;

?>