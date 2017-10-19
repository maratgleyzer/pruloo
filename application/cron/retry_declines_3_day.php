<?php

require_once("_db.php");
//var_dump($_SERVER);exit;
$sql = "select ordr_id from mm_user_order where sale_date = '".date("Y-m-d", strtotime("-3 day", strtotime(date("Y-m-d"))))."' and order_status = 'DECLINE' and rebill > 0";

if ($result = mysql_query($sql))
	while ($row = mysql_fetch_array($result)) {
		$filelines = file('http://www.proleroinc.com/order/rebill/oid/'.$row['ordr_id'].'/retry/1');
		if (eregi("DECLINE",$filelines[0]))
			$filelines = file('http://www.proleroinc.com/order/cancel/oid/'.$row['ordr_id']);
	}

?>