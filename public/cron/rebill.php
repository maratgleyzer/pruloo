<?php

require_once("_db.php");
//var_dump($_SERVER);exit;
$sql = "select ordr_id from mm_user_order where camp_id = 1234570 and rebill < 1 and ordr_id > 1235042";

if ($result = mysql_query($sql))
	while ($row = mysql_fetch_array($result))
		$filelines = file('http://www.proleroinc.com/order/rebill/oid/'.$row['ordr_id']);

?>