<?php
$dbh=mysql_connect ("mysql.customrealms.com", "kramer", "blah1010") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("santos");
session_start();
session_register();
?>