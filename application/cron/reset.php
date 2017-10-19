<?php

require_once("_db.php");

$sql = "update mm_user_gateway set purchases = 0";
$result = mysql_query($sql);

?>