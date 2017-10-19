<?php

$post =
"account="
.$gateway['gate_acct'].
"&account_username="
.$gateway['gate_user'].
"&account_password="
.$gateway['gate_pass'].
"&plan="
.$gateway['gate_plan'].
"&type=purchase".
"&amount="
.$order_data['total_sale'].
"&first_name="
.$order_data['bill_first'].
"&last_name="
.$order_data['bill_last'].
"&street_address_1="
.$order_data['bill_address'].
"&street_address_2=".
"&city="
.$order_data['bill_city'].
"&state="
.$order_data['bill_state'].
"&zip="
.$order_data['bill_zip'].
"&country="
.$order_data['bill_country'].
"&phone_number="
.$order_data['bill_phone'].
"&email_address="
.$order_data['bill_email'].
"&ip_address="
.$order_data['ip_address'].
"&card_number="
.$order_data['card_number'].
"&card_expiry_month="
.$order_data['expires_mm'].
"&card_expiry_year="
."20".$order_data['expires_yy'].
"&card_cvv2="
.$cvv_code;

if ($response = $this->Curl($gateway['gate_link'], $post, $header)) {
	include('gateway/'.$gateway['gate_id'].'/parser.inc');
}

else { $response = "ERROR"; }

?>