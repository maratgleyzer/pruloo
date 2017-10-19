<?php

$post =
"type=sale".
"&username="
.$gateway['gate_user'].
"&password="
.$gateway['gate_pass'].
"&ccnumber="
.$order_data['card_number'].
"&ccexp="
.$order_data['expires_mm'].$order_data['expires_yy'].
"&checkname=".
"&checkaba=".
"&checkaccount=".
"&account_holder_type=".
"&account_type=".
"&amount="
.$order_data['total_sale'].
"&cvv="
.$cvv_code.
"&payment=creditcard".
"&processor_id="
.$gateway['gate_plan'].
"&product_sku_#=&orderid="
.$order_data['ordr_id'].
"&ip_address="
.$order_data['ip_address'].
"&shipping="
.$order_data['shipper_sale'].
"&firstname="
.$order_data['bill_first'].
"&lastname="
.$order_data['bill_last'].
"&address1="
.$order_data['bill_address'].
"&city="
.$order_data['bill_city'].
"&state="
.$order_data['bill_state'].
"&zip="
.$order_data['bill_zip'].
"&country="
.$order_data['bill_country'].
"&phone="
.$order_data['bill_phone'].
"&email="
.$order_data['bill_email'].
"&shipping_firstname="
.(isset($order_data['ship_first']) ? $order_data['ship_first'] : "").
"&shipping_lastname="
.(isset($order_data['ship_last']) ? $order_data['ship_last'] : "").
"&shipping_address1="
.(isset($order_data['ship_address']) ? $order_data['ship_address'] : "").
"&shipping_city="
.(isset($order_data['ship_city']) ? $order_data['ship_city'] : "").
"&shipping_state="
.(isset($order_data['ship_state']) ? $order_data['ship_state'] : "").
"&shipping_zip="
.(isset($order_data['ship_zip']) ? $order_data['ship_zip'] : "").
"&shipping_country="
.(isset($order_data['ship_country']) ? $order_data['ship_country'] : "").
"&shipping_email="
.(isset($order_data['ship_email']) ? $order_data['ship_email'] : "");

if ($response = $this->Curl($gateway['gate_link'], $post, $header)) {
	include('gateway/'.$gateway['gate_id'].'/parser.inc');
}

else { $response = "ERROR"; }

?>