<?php

$raw = $response;
//echo "CUREPAY".$response; exit;
$key_value_pairs = explode("&",$response);
foreach ($key_value_pairs as $key_value_pair) {
	$key2value = explode("=",$key_value_pair);
	$key_value_array[$key2value[0]] = $key2value[1];
}

switch ($key_value_array['code']) {
	case '0':
	$order_data['order_status'] = "DECLINE";
	$order_data['decline_reason'] = "Transaction has been declined at processor level.".
	($key_value_array['sub_message'] ? " ".$key_value_array['sub_message'] : "");
	break;
	case '1':
	$order_data['order_status'] = "SALE";
	break;
	case '2':
	$order_data['order_status'] = "PENDING";
	$order_data['decline_reason'] = "Transaction is currently being processed.".
	($key_value_array['sub_message'] ? " ".$key_value_array['sub_message'] : "");
	break;
	case '3':
	$order_data['order_status'] = "ERROR";
	$order_data['decline_reason'] = "Transaction has been flagged with the internal gateway error system.".
	($key_value_array['sub_message'] ? " ".$key_value_array['sub_message'] : "");
	break;
	case '4':
	$order_data['order_status'] = "RETRY";
	$order_data['decline_reason'] = "Retry: Unable to receive response from processor.".
	($key_value_array['sub_message'] ? " ".$key_value_array['sub_message'] : "");
	break;
}
//echo $order_data['order_status']; exit;
if (eregi("REFUND",$process)) $order_data['void_id'] = $key_value_array['transaction_id'];
else $order_data['transaction'] = $key_value_array['transaction_id'];

$response = $order_data['order_status'];

?>
