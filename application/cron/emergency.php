<?php

require_once("_db.php");
//var_dump($_SERVER);exit;
/*
$sql = "select ordr_id, bill_email from mm_user_order where camp_id = 1234570";

if ($result = mysql_query($sql))
	while ($row = mysql_fetch_array($result)) {
		$sql = "update mm_user_order set username = \"$row[bill_email]\", password = \"".base64_encode(substr($row['bill_email'],0,strpos($row['bill_email'],'@')))."\" where ordr_id = $row[ordr_id]";
		$update = mysql_query($sql);		
	}
*/
$sql = "select ordr_id from mm_user_order where camp_id = 1234570 and sale_date = '".date('Y-m-d')."' and rebill > 0 and order_status = 'SALE'";
	
$i = 0;
if ($result = mysql_query($sql))
	while ($row = mysql_fetch_array($result)) {
		$filelines = file('http://www.proleroinc.com/order/resend/oid/'.$row['ordr_id']);
		echo ++$i."\n";
	}

?>