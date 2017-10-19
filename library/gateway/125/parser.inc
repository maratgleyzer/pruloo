<?php

$raw = $response;
$this->raw = $raw;
//echo "NMI".$response; exit;
$key_value_pairs = explode("&",$response);
foreach ($key_value_pairs as $key_value_pair) {
	$key2value = explode("=",$key_value_pair);
	$key_value_array[$key2value[0]] = $key2value[1];
}

$messages =
array(
200 => 'Transaction was Declined by Processor',
201 => 'Do Not Honor',
202 => 'Insufficient Funds',
203 => 'Over Limit',
204 => 'Transaction Not Allowed',
220 => 'Incorrect Payment Data',
221 => 'No Such Card Issuer',
222 => 'No Card Number on file with Issuer',
223 => 'Expired Card',
224 => 'Invalid Expiration Date',
225 => 'Invalid Card Security Code',
240 => 'Call Issuer For Further Information',
250 => 'Pick Up Card',
251 => 'Lost Card',
252 => 'Stolen Card',
253 => 'Fraudulant Card',
260 => 'Declined with further Instructions Available:',
261 => 'Declined - Stop All Recurring Payments',
262 => 'Declined - Stop this Recurring Program',
263 => 'Declined - Update Cardholder Data Available',
264 => 'Declined - Retry in a few Days',
300 => 'Transaction was Rejected by Gateway',
400 => 'Transaction Error Returned By Processor',
410 => 'Invalid Merchant Configuration',
411 => 'Merchant Account is Inactive',
420 => 'Communication Error',
421 => 'Communication Error with Issuer',
430 => 'Duplicate Transaction at Processor',
440 => 'Processor Format Error',
441 => 'Invalid Transaction Information',
460 => 'Processor Feature Not Available',
461 => 'Unsupported Card type'
);

switch ($key_value_array['response']) {
	case '1':
	$order_data['order_status'] = "SALE";
	break;
	case '2':
	$order_data['order_status'] = "DECLINE";
	$order_data['decline_reason'] = $messages[$key_value_array['response_code']].
	($key_value_array['response_code'] == 260 ? " ".$key_value_array['reponsetext'] : "");
	break;
	case '3':
	$order_data['order_status'] = "ERROR";
	$order_data['decline_reason'] = $messages[$key_value_array['response_code']].
	($key_value_array['response_code'] == 260 ? " ".$key_value_array['reponsetext'] : "");
	break;
}

if (eregi("REFUND",$process)) {
	$order_data['void_id'] = $key_value_array['transactionid'];
}
else {
	$order_data['transaction'] = $key_value_array['transactionid'];
	$order_data['auth_number'] = $key_value_array['authcode'];
}

$response = $order_data['order_status'];

?>