<?

	require_once('_.php'); 
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Make Money Online!</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
window.open(theURL,winName,features);
}
//-->
</script> 
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
	<div id="thanks">
	<h1>Thank You!</h1>
	<h2>Check Your Email For Your Login Instructions!</h2>
<?/*
	<h2>Resend to a different email account</h2>
	<h3>You have an opportunity to send to a different email address if the one<br/>you provided was no the one to which you wish to have the details sent.</h3>
	<form method="get">
  	<input type="hidden" name="e_mail" value="<?php echo $_GET['e_mail']; ?>"/>
  	<input type="text" name="e_mail2" value="<?php echo $_GET['e_mail']; ?>"/>
  	<input type="submit" value="submit"/>
	</form>
	*/
error_reporting(0);
include_once '../../../base/action/getPixels.php'; ?>
    </div>
    <div id="footer">
    <a href="#" onClick="MM_openBrWindow('terms.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Terms and Conditions</a> | <a href="#" onClick="MM_openBrWindow('privacy.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Privacy Policy</a> | <a href="#" onClick="MM_openBrWindow('contact.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Contact us</a>

    </div>
</body>
</html>

<?php include_once '../upsell.php'; ?>
