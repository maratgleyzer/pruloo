<?php

class OrderController extends Zend_Controller_Action
{

    public function indexAction()
    {

    	ini_set('error_reporting','~E_NOTICE');

    	$post = $this->_request->getPost();

    	$this->CheckRequiredData($post);
    	$this->SetActionIds($post);

    	$order = $this->ParseOrderData($post);

		if ($this->ordr_id > 0) {

			if (($order['affi_id'] != 12474) && ($order['affi_id'] != 12496)) {
				$this->CheckBillingData($post);
				$order = $this->NormalizeBillingData($order);
			}

			$storedOrder = $this->GetOrderForBilling();

			if ($this->camp_id != $storedOrder['camp_id']) {
				$alternateCampaign = $this->GetAlternateCampaign();
				foreach ($alternateCampaign as $key => $value)
					$storedOrder[$key] = $value;
			}

			if (($this->offr_id > 0) && ($this->offr_id != $storedOrder['offr_id'])) {
				$alternateOffer = $this->GetAlternateOffer();
				foreach ($alternateOffer as $key => $value)
					$storedOrder[$key] = $value;
			}

			if ($this->ship_id > 0) {

				$this->CheckShippingData($post);

				if (isset($order['same_as_billing']) && ($order['same_as_billing'] == 0)) {
					$gd = $this->GetGoogleData($order['ship_address'], $order['ship_zip'], $this->country);
					$order['ship_country'] = $gd['country'];
					$order['ship_state'] = $gd['state'];
					$order['ship_city'] = $gd['city'];
				}

				$order = $this->NormalizeShippingData($order);
				$order = $this->FormatShippingPhone($order);

			}

			
			$this->GetCardSecurityCode($post);
			$order = $this->GetOrderTotals($order, $storedOrder);

			foreach ($order as $key => $value)
				$storedOrder[$key] = $order[$key];

			$order = $storedOrder;
//		var_dump($order);exit;	

			$this->order_data = $order;
			$order = $this->BillGateway('default',false);
			$response = $order['order_status'];

			if (eregi("ERROR",$response)) {
				echo $response; exit; 
			}

			if (eregi("(SALE|RETRY|PENDING)",$response))
				$this->MailLoginReceipt($order);

			$this->SaveSaleData($order);

			if (($storedOrder['impulse'] > 0) && ($order['affi_id'] != 12474) && ($order['affi_id'] != 12496) && eregi("SALE",$response)) {
				$impulse = $this->SendLeadToImpulse($order, 2);
				$impulse = $this->SendSaleToImpulse($order);
			}

			if (($storedOrder['upsl_id'] > 0) && ($order['affi_id'] != 12474) && ($order['affi_id'] != 12496) && eregi("SALE",$response))
				$response = "UPSELL";

			if (($storedOrder['bump_id'] > 0) && eregi("(SALE|RETRY|PENDING)",$response)) {
				$this->offr_id = $storedOrder['bump_id'];
				$this->BumpItUp($order, -$storedOrder);
			}
				
			echo $response;
			exit;

		}

    	if ($this->ordr_id == 0) {

    		$this->CheckCountryOfOrigin($post);
			$campaign = $this->CheckIfCampaignExists();

			$this->CheckContactData($post);

			$gd = $this->GetGoogleData($order['bill_address'], $order['bill_zip'], $this->country);

			$order['bill_country'] = $gd['country'];
			$order['bill_state'] = $gd['state'];
			$order['bill_city'] = $gd['city'];

			$order = $this->NormalizeContactData($order);
			$order = $this->FormatContactPhone($order);

			if (!isset($order['affi_id'])) {
				$order['affi_id'] = $this->GetAffiliateId();
    			$order['subs_id'] = $this->GetSubAffiliateId();
			}

			$order['ordr_id'] = $this->SaveLeadData($order);

			if ($campaign['impulse'] > 0) {
				$order['product_name'] = $campaign['product_name']; 
				$order['impulse_campaign_id'] = $campaign['impulse_campaign_id'];
				if (!eregi("(SALE|RETRY|PENDING)",$order['order_status']) || ($order['recur_term'] == 0) || ($order['product_size'] > 0))
					$impulse = $this->SendLeadToImpulse($order, 1);
			}

			echo $order['ordr_id'];
			exit;

		}

    }
    	
    	
    	
    

    
    
    private function BumpItUp($order, $storedOrder) {

		if (($this->offr_id > 0) && ($this->offr_id != $storedOrder['offr_id'])) {
			$alternateOffer = $this->GetAlternateOffer();
			foreach ($alternateOffer as $key => $value)
				$storedOrder[$key] = $value;
		}

		$order = $this->GetOrderTotals($order, $storedOrder);

		foreach ($order as $key => $value)
			$storedOrder[$key] = $order[$key];

		$order = $storedOrder;
//		var_dump($order);exit;	

		if ($this->SaveSaleData($order))
			return true;
   	
    }
    
    
    


    
    
    
    
    public function apiAction() {

    	ini_set('error_reporting','~E_NOTICE');

    	$post = $this->_request->getPost();

    	$this->CheckRequiredData($post);
    	$this->SetActionIds($post);

    	$order = $this->ParseOrderData($post);
    
		$campaign = $this->CheckIfCampaignExists();

		$this->CheckContactData($post);

		if (!$order['bill_city']) {

			$this->CheckCountryOfOrigin($post);
		
			$gd = $this->GetGoogleData($order['bill_address'], $order['bill_zip'], $this->country);

			$order['bill_country'] = $gd['country'];
			$order['bill_state'] = $gd['state'];
			$order['bill_city'] = $gd['city'];
			
		}

		$order = $this->NormalizeContactData($order);
		$order = $this->FormatContactPhone($order);

		if (!isset($order['affi_id'])) {
			$order['affi_id'] = $this->GetAffiliateId();
    		$order['subs_id'] = $this->GetSubAffiliateId();
		}

		$order['ordr_id'] = $this->SaveLeadData($order);
		$this->ordr_id = $order['ordr_id'];
		
		if ($campaign['impulse'] > 0) {
			$order['product_name'] = $campaign['product_name']; 
			$order['impulse_campaign_id'] = $campaign['impulse_campaign_id'];
			if (!eregi("SALE",$order['order_status']) || ($order['recur_term'] == 0) || ($order['product_size'] > 0))
				$impulse = $this->SendLeadToImpulse($order, 1);
		}
    	
		if (($this->ordr_id > 0) && (strlen($order['card_number']) > 14)) {
		
			$this->CheckBillingData($post);
			$order = $this->NormalizeBillingData($order);

			if ($this->ship_id > 0) {

				$this->CheckShippingData($post);

				if (isset($order['same_as_billing']) && ($order['same_as_billing'] == 0)) {
					$gd = $this->GetGoogleData($order['ship_address'], $order['ship_zip'], $this->country);
					$order['ship_country'] = $gd['country'];
					$order['ship_state'] = $gd['state'];
					$order['ship_city'] = $gd['city'];
				}

				$order = $this->NormalizeShippingData($order);
				$order = $this->FormatShippingPhone($order);

			}

			$this->GetCardSecurityCode($post);
			$order = $this->GetOrderTotals($order, $order);
//		var_dump($order);exit;	

			$this->order_data = $order;
			$order = $this->BillGateway('default',false);
			$response = $order['order_status'];

			if (eregi("ERROR",$response)) {
				echo $response; exit; 
			}

			if (eregi("(SALE|RETRY|PENDING)",$response))
				$this->MailLoginReceipt($order);

			$this->SaveSaleData($order);

			if (($campaign['impulse'] > 0) && ($order['affi_id'] != 12474) && eregi("SALE",$response)) {
				$impulse = $this->SendLeadToImpulse($order, 2);
				$impulse = $this->SendSaleToImpulse($order);
			}

			if (($campaign['bump_id'] > 0) && eregi("(SALE|RETRY|PENDING)",$response)) {
				$this->offr_id = $storedOrder['bump_id'];
				$this->BumpItUp($order, $storedOrder);
			}
				
			echo $response;
			exit;
			
		}
    	
    }
    
    
    
    
    
    
    
    
    
    public function refundAction() {
    	
    	ini_set('error_reporting','~E_NOTICE');

    	if (!($order_data = $this->GetOrder())) {
    		echo "An error has occured. Please try your request again.";
    		exit;
    	}

   		$order_data['void_date'] = date("Y-m-d");
		$order_data['void_time'] = date("H:i:s");

		$this->order_data = $order_data;
		$order_data = $this->BillGateway('refund',false);
		$response = $order_data['order_status'];
		//echo $this->raw; exit;
		if (eregi("(SALE|PENDING|RETRY)",$response)) {
			$response = "Your request to REFUND $order_data[total_sale] to credit card #XXXXXXXXXXXX".substr($order_data['card_number'],-4)." has been APPROVED.";
		}
		elseif (eregi("ERROR",$response)) {
			echo "A payment gateway error has occured and the refund request cannot be processed at this time.";
			exit;
		}
		else {
			echo "A payment gateway error has occured and the refund request cannot be processed at this time.";
			exit;
		}

		$this->refund = true;
		$this->cancelAction();
		$order_data['order_status'] = "REFUND";

		$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    	
		$order_where[] = "ordr_id = ".$order_data['ordr_id'];
		$order_where[] = "camp_id = ".$order_data['camp_id'];
		$order_where[] = "user_id = ".$order_data['user_id'];

		try {
    	$db->beginTransaction();
		$db->update('mm_user_order', $order_data, $order_where);
		$db->commit(); $db->closeConnection();
		$response .= " Please see Order ID #$order_data[ordr_id] for details.";
		} catch ( Exception $e ) {
		$db->rollback(); $db->closeConnection();
		$response .= " An error has occured, and a record of the transaction could not be saved.";
		}
		
		echo $response;
		exit;
    }
    
    

    
    
    
    
    
    public function rebillAction() {

    	ini_set('error_reporting','~E_NOTICE');

    	if (!($order_data = $this->GetOrder()))
    		exit;

    	$this->camp_id = $order_data['camp_id'];
    	$this->user_id = $order_data['user_id'];
    	$this->offr_id = $order_data['offr_id'];

    	if ($this->offr_id > 0) {
    		if (!($order_item_data = $this->GetAlternateOffer()))
    			exit;
    	}
   	
    	else if (!($order_item_data = $this->GetOrderItem()))
    		exit;
//var_dump($order_item_data);exit;

		if ($this->_request->getParam('retry') > 0)
			$order_data['retry'] = '1';
    		
    	$order_data['rebill'] = "1";
		$order_data['sale_date'] = date("Y-m-d");
		$order_data['sale_time'] = date("H:i:s");
		unset($order_data['order_status']);
		
		$order_data['product_sale'] =
		($order_item_data['offer_cost'] > $order_item_data['product_cost']
		? $order_item_data['offer_cost'] : $order_item_data['product_cost'])
		*$order_data['quantity'];

		if ($order_item_data['recur_term'] > 0)
			$order_data['rebill_date'] = date("Y-m-d", strtotime("+".$order_item_data['recur_term']." day", strtotime(date("Y-m-d"))));

		$order_data['total_sale'] = $order_data['product_sale']+$order_data['shipper_sale'];

		$this->order_data = $order_data;
		$order_data = $this->BillGateway('rebill',false);
		$response = $order_data['order_status'];

		if (eregi("ERROR",$response)) exit; 

    	$order_where[] = "ordr_id = ".$order_data['ordr_id'];
		$order_where[] = "camp_id = ".$order_data['camp_id'];
		$order_where[] = "user_id = ".$order_data['user_id'];
		
		$this->ordr_id = $order_data['ordr_id'];
    	unset($order_data['ordr_id']);

    	$db = $this->getInvokeArg('bootstrap')->getResource('db');

		try {
    	$db->beginTransaction();
		if ($this->_request->getParam('retry') > 0) {
			if (eregi("(SALE|PENDING|RETRY)",$response)) {
				$order_data['disabe'] = "0";
				$order_data['expired'] = "0";
				$db->update('mm_user_order', $order_data, $order_where);
			}
		}
		else {
			$db->insert('mm_user_order', $order_data);
			$oid = $db->lastInsertId('mm_user_order', 'ordr_id');
			$db->query("update mm_user_order set expired = 1 where ordr_id = $this->ordr_id");
		}
		$db->commit(); $db->closeConnection();
		} catch ( Exception $e ) {
		$db->rollback(); $db->closeConnection();
		}

		echo $response;
		exit;

    }
    
    
    
    
    
    
    
    
    public function retryAction() {
    	
    	ini_set('error_reporting','~E_NOTICE');

    	if (!($order_data = $this->GetOrder()))
    		exit;

    	$this->camp_id = $order_data['camp_id'];
    	$this->user_id = $order_data['user_id'];
    	$this->offr_id = $order_data['offr_id'];
    
    	if ($this->offr_id > 0) {
    		if (!($order_item_data = $this->GetAlternateOffer()))
    			exit;
    	}
    	
    	else if (!($order_item_data = $this->GetOrderItem()))
    		exit;

    	$order_data['retry'] = '1';
    	$order_data['sale_date'] = date("Y-m-d");
		$order_data['sale_time'] = date("H:i:s");
		unset($order_data['order_status']);
		
		$order_data['plan_id'] = 0;
		$this->ordr_id = $order_data['ordr_id'];
		
		$this->order_data = $order_data;
		$order_data = $this->BillGateway('default',false);
		$response = $order_data['order_status'];

		if (eregi("(SALE|PENDING|RETRY)",$response)) {
			if ($order_item_data['recur_term'] > 0)
				$order_data['rebill_date'] = date("Y-m-d", strtotime("+".$order_item_data['recur_term']." day", strtotime(date("Y-m-d"))));
			// only mail logins if its a sale
			// with rebilling and a "0" product size
			if (($order_data_item['recur_term'] > 0) && ($order_data_item['product_size'] == 0))
				$mailsent = $this->MailLoginReceipt($order_data);
			$response = "Your request to RETRY $order_data[total_sale] on credit card #XXXXXXXXXXXX".substr($order_data['card_number'],-4)." has been APPROVED.";
		}
		elseif (eregi("ERROR",$response)) {
			echo "A payment gateway error has occured and the retry request cannot be processed at this time.";
			exit;
		}
		else {
			echo "The credit card has again been declined on a different payment gateway.";
		}

		$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    	
		$order_where[] = "ordr_id = ".$order_data['ordr_id'];
		$order_where[] = "user_id = ".$order_data['user_id'];
		
		unset($order_data['ordr_id']);
				
		try {
    		$db->beginTransaction();
			$db->update('mm_user_order', $order_data, $order_where);
			$db->commit(); $db->closeConnection();
			echo " Please see Order ID #$this->ordr_id for details.";
		} catch ( Exception $e ) {
			$db->rollback(); $db->closeConnection();
			echo " An error has occured, and a record of the transaction could not be saved.";
		}

		exit;

    }
    
    
    
    
    
    
    
    
    
    
    public function cancelAction() {
    	
    	$oid = $this->_request->getParam('oid');
    	
    	$sql = "select camp_id, bill_email from mm_user_order where ordr_id = $oid limit 1";

    	$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    		
		try {
		$result = $db->fetchAll($sql);
		} catch ( Exception $e ) {
		$db->closeConnection();
		echo "An error has occured, and the Order cannot be CANCELLED at this time.";
		exit;
		}

		$camp_id = $result[0]['camp_id'];
		$bill_email = $result[0]['bill_email'];

		try {
    	$db->beginTransaction();
		$data = array('disable' => '1');
		$where[] = "camp_id = $camp_id";
		$where[] = "bill_email = \"$bill_email\"";
		$db->update('mm_user_order', $data, $where);
		$db->commit(); $db->closeConnection();
		echo "Order ID $oid has been CANCELLED and will no longer rebill or be able to log into the system.";
		if (!isset($this->refund)) exit;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		echo "An error has occured, and the Order cannot be CANCELLED at this time.";
		if (!isset($this->refund)) exit;
		}
	}
		   
    
    
    
    

	
	
	
	
    
    public function activateAction() {
    	
    	$oid = $this->_request->getParam('oid');
    	
    	$sql = "select camp_id, bill_email from mm_user_order where ordr_id = $oid limit 1";

    	$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    		
		try {
		$result = $db->fetchAll($sql);
		} catch ( Exception $e ) {
		$db->closeConnection();
		echo "An error has occured, and the Order cannot be ACTIVATED at this time.";
		exit;
		}

		$camp_id = $result[0]['camp_id'];
		$bill_email = $result[0]['bill_email'];
				
		try {
    	$db->beginTransaction();
		$data = array('disable' => '0');
		$where[] = "camp_id = $camp_id";
		$where[] = "bill_email = \"$bill_email\"";
		$db->update('mm_user_order', $data, $where);
		$db->commit();
		$db->closeConnection();
		echo "Order ID $oid has been successfully REACTIVATED, and will rebill on (insert data here).";
		exit;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		echo "An error has occured, and the Order cannot be ACTIVATED at this time.";
		exit;
		}
	}
		   
    
    
	
	
    
    
    
    
    
    private function GetOrder() {
   	
    	if ($ordr_id = $this->_request->getParam('oid')) {
    		$sql =
"
select o.* from mm_user_order `o` where o.ordr_id = $ordr_id limit 1
";

    		$b = $this->getInvokeArg('bootstrap');
    		$db = $b->getResource('db');
    		
			try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			return $result[0];
			} catch ( Exception $e ) {
			$db->closeConnection();
			return false;
			}
    	}
    	else { return false; }
    }
    
    
    
    
    
    private function GetOrderItem() {
   	
    	if ($ordr_id = $this->_request->getParam('oid')) {
    		$sql =
"
select c.*, off.*, p.*
from mm_user_order `o`, mm_user_campaign `c`, mm_user_offer `off`, mm_user_product `p`
where o.ordr_id = $ordr_id
  and o.camp_id = c.camp_id
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
limit 1
";

    		$b = $this->getInvokeArg('bootstrap');
    		$db = $b->getResource('db');
    		
			try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			return $result[0];
			} catch ( Exception $e ) {
			$db->closeConnection();
			return false;
			}
    	}
    	else { return false; }
    }
    
    
    
    
    
    
    
    
    
    
    
    public function BillGateway($process,$retry_declines) {

    	$order_data = $this->order_data;
    	$cvv_code = $this->cvv_code;
	
 		if (!($ordr_id = $order_data['ordr_id'])) {
    		$order_data['order_status'] = "ERROR";
    		return $order_data;
 		}

    	if (!($sale = $order_data['total_sale'])) {	
    		$order_data['order_status'] = "ERROR";
    		return $order_data;
    	}

    	$excluded_gates = array();
    	$plan_id = $order_data['plan_id'];

    	$error_retry = false;
    	$decline_retry = false;
    	$retry_retry = false;

    	$stop = false;
    	$get_new_gateway = true;

    	while ($stop == false) {
    	if ($get_new_gateway == true) {
    		$get_new_gateway == false;

	    $sql =
"
select
o.ordr_id,
o.user_id,
g.gate_name,
g.gate_link,
g.test_link,
g.test_acct,
g.test_user,
g.test_pass,
g.test_card,
g.test_plan,
g.test_type,
g.debug,
ug.weight,
ug.gate_id,
ug.plan_id,
ug.gate_acct,
ug.gate_user,
ug.gate_pass,
ug.gate_plan,
ug.threshold,
ug.purchases
from mm_user_order `o`,
	 mm_user_gateway `ug`,
	 mm_gateway `g`,
	 mm_user `u`
where o.ordr_id = $ordr_id
  and o.user_id = u.user_id
  and u.user_id = ug.user_id
  and ug.gate_id = g.gate_id
".(count($excluded_gates) > 0 ? "and ug.gate_id NOT in (".implode(", ",$excluded_gates).")" : "")."
".($plan_id > 0 ? "and ug.plan_id = o.plan_id" : "")."
  and (ug.threshold > ug.purchases + $sale or ug.threshold = 0)
  and ug.disable = 0
  and g.disable = 0
  and u.disable = 0
order by ug.weight desc, ug.purchases asc
limit 1
";
    	
   		$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    		
    	try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		if (count($result) == 0) {
			if ($plan_id == 0) {
	    		$order_data['order_status'] = "ERROR";
    			return $order_data;
			}
			$get_new_gateway = true;
			$plan_id = 0; continue;
		} $gateway = $result[0];
		} catch ( Exception $e ) {
		$order_data['order_status'] = "ERROR";
		return $order_data;
		}

		if ($gateway['debug'] > 0) {
			$gateway['gate_link'] = $gateway['test_link'];
			$gateway['gate_acct'] = $gateway['test_acct'];
			$gateway['gate_user'] = $gateway['test_user'];
			$gateway['gate_pass'] = $gateway['test_pass'];
			$gateway['gate_plan'] = $gateway['test_plan'];
			$order_data['card_number'] = $gateway['test_card'];
		}

		$order_data['plan_id'] = $gateway['plan_id'];
		
    	}

    	unset($response);
    	
		include('gateway/'.$gateway['gate_id'].'/'.$process.'.inc');

		switch ($response) {

			case 'ERROR':
				if ($error_retry == true) {

					if (eregi("(DEFAULT|REBILL|RETRY)",$process))
						$order_data['order_status'] = "DECLINE";

					$stop = true;
					
//					if (eregi("REFUND",$process)) {
//						$order_data['order_status'] = "ERROR";
//						return $order_data;
//					}

//					try {
//						$db = $this->getInvokeArg('bootstrap')->getResource('db');
//						$db->beginTransaction();
//						$db->query("update mm_user_gateway set disable = 1 where plan_id = $gateway[plan_id]");
//						$db->commit();
//						$db->closeConnection();
//						$this->SendErrorNotice($gateway, $order_data, $raw);
//					} catch ( Exception $e ) {
//						$db->rollback();
//						$db->closeConnection();
//						$order_data['order_status'] = "ERROR";
//						return $order_data;
//					}
					
//					$this->SendErrorNotice($gateway, $order_data, $raw);
				}
				else {
//					$excluded_gates[] = $gateway['gate_id'];
					$order_data['retry'] = 1;
					$get_new_gateway == true;
					$error_retry = true;
					$plan_id = 0;
				}
				break;

			case 'DECLINE':
				if ($retry_declines == true) {
					if ($decline_retry == true) {
						$stop = true;
					}
					else {
						$order_data['retry'] = 1;
//						$excluded_gates[] = $gateway['gate_id'];
						$get_new_gateway == true;
						$decline_retry = true;
						$plan_id = 0;
					}
				}
				else {
					$stop = true;
				}
				break;

			case 'RETRY':
				if ($retry_declines == true) {
					if ($retry_retry == true) {
						$stop = true;
					}
					else {
						$order_data['retry'] = 1;
//						$excluded_gates[] = $gateway['gate_id'];
						$get_new_gateway == true;
						$retry_retry = true;
						$plan_id = 0;
					}
				}
				else {
					$stop = true;
				}
				break;

			default:
				if (eregi("REFUND",$process)) {
					return $order_data;
					break;
				}

				try {
					$db = $this->getInvokeArg('bootstrap')->getResource('db');
					$db->beginTransaction();
					$db->query("update mm_user_gateway set purchases = purchases + ".$order_data['total_sale']." where plan_id = ".$gateway['plan_id']." and user_id = ".$order_data['user_id']);
					$db->commit();
					$db->closeConnection();
					$stop = true;
				} catch ( Exception $e ) {
					$db->rollback();
					$db->closeConnection();
					$order_data['order_status'] = "ERROR";
					$stop = true;
				}
				break;
		}
    	}
    	
    	return $order_data;
	}    	

	
	
	
	
	
	
	
	
	
	
	private function Curl($url, $post, $header) {

		$ch = curl_init ();

		curl_setopt ($ch, CURLOPT_URL, $url);
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt ($ch, CURLOPT_POST, 0);
    	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);

    	if ($header != "") {
    		curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
	    }

	    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

	    ob_start ();
	    $result = curl_exec ($ch);
	    ob_end_clean ();

	    curl_close ($ch);

	    return $result;

	}
	
	
	
	
	
	
	
	
	private function SendLeadToImpulse($o=array(),$sale) {
		
		$post =
		
"step=first&product_name="
.$o['product_name'].
"&fields_fname="
.$o['bill_first'].
"&fields_lname="
.$o['bill_last'].
"&fields_address1="
.$o['bill_address'].
"&fields_city="
.$o['bill_city'].
"&fields_state="
.$o['bill_state'].
"&fields_zip="
.$o['bill_zip'].
"&fields_phone="
.$o['bill_phone_2'].$o['bill_phone_3'].$o['bill_phone_4'].
"&fields_email="
.$o['bill_email'].
"&campaign_id=".
$o['impulse_campaign_id'].
"&client_id=d8b0914a4207f28787fbc792bf1823c6&offer_id="
.$o['camp_id'].
"&lead_id="
.$o['ordr_id'].
"&ip="
.$_SERVER['REMOTE_ADDR'].
($sale == 1 ? "&delay=20" : "&reject=1");

		$response = $this->Curl('http://63.135.225.115/partials/mgr.php', $post, array());	

		//mail('marat@maratgleyzer.com','impulse partial',($response ? $post."\n\n\n\n".$response : $post."\n\n\n\n"."NOTHING"));

		return $response;
		
	}
	
	
	
	
	
	
	
	
	private function SendSaleToImpulse($o=array()) {
		
		$post =
		
"SourceLeadReferenceId="
.$o['ordr_id'].
"&FirstName="
.$o['bill_first'].
"&MiddleName=&LastName="
.$o['bill_last'].
"&InternationalDialCode=1&PhoneNumber="
.$o['bill_phone_2'].$o['bill_phone_3'].$o['bill_phone_4'].
"&Email="
.$o['bill_email'].
"&Address="
.$o['bill_address'].
"&Address2=&City="
.$o['bill_city'].
"&State="
.$o['bill_state'].
"&Zip="
.$o['bill_zip'].
"&CountryCode=&BillingName="
.$o['bill_first']." ".$o['bill_last'].
"&BillingAddress="
.$o['bill_address'].
"&BillingAddress2=&BillingCity="
.$o['bill_city'].
"&BillingState="
.$o['bill_state'].
"&BillingZip="
.$o['bill_zip'].
"&BillingCountryCode="
.$o['bill_country'].
"&CCNumber="
.$o['card_number'].
"&CCType="
.$o['card_type'].
"&CCExpMonth="
.$o['expires_mm'].
"&CCExpYear="
.$o['expires_yy'].
"&CCSecurityCode="
.$this->cvv_code.
"&SourceProductId="
.$o['impulse_product_id'].
"&SourceProductSoldOn="
.date('Y-m-d')." ".date('H:i:s').
"&SourceProductQuantitySold="
.$o['quantity'];
	
		$response = $this->Curl('https://www.impulse123.com/C60CF423-F7BF-4838-B204-9C8600FEFE7C/Lead/Insert', $post, array());
			
		//mail('marat@maratgleyzer.com','impulse lead data',($response ? $post."\n\n\n\n".$response : $post."\n\n\n\n"."NOTHING"));
		
		return $response;
		
	}
	
	
	
	
	
	
	
	
	
	
	public function resendAction() {
		
		$oid = $this->_request->getParam('oid');
		$pid = $this->_request->getParam('pid');
		$user = $this->_request->getParam('user');

		if ($oid > 0) {
		
			$sql = "select ordr_id, camp_id, offr_id, username, password, bill_email, bill_first, bill_last, total_sale from mm_user_order where ordr_id = $oid limit 1";
		    	
    		$db =  $this->getInvokeArg('bootstrap')->getResource('db');

			try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			$this->MailLoginReceipt($result[0]);
			echo "Login information for Order ID $oid has been resent.";
			} catch ( Exception $e ) {
			$db->closeConnection();
			echo "Unable to resend login information at this time.";
			}

			exit;
			
		}
		
		if (($pid > 0) && (strlen($user) > 0)) {
			
			$sql =
"
select
o.ordr_id,
o.camp_id,
o.offr_id,
o.username,
o.password,
o.bill_email,
o.bill_first,
o.bill_last,
o.total_sale
from mm_user_order `o`, mm_user_campaign `c`, mm_user_offer `of`, mm_user_product `p`
where o.bill_email = \"$user\"
  and o.camp_id = c.camp_id
  and (c.offr_id = of.offr_id
    or o.offr_id = of.offr_id)
  and of.prod_id = p.prod_id
  and p.prod_id = $pid
  and p.disable = 0
  and of.disable = 0
  and o.disable = 0
limit 1
";
			
			$db =  $this->getInvokeArg('bootstrap')->getResource('db');

			try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			$this->MailLoginReceipt($result[0]);
			echo "SUCCESS";
			} catch ( Exception $e ) {
			$db->closeConnection(); echo $sql;
			echo "INVALID";
			}

			exit;
			
		}
		
	}
	
	
	

	
	
	
	
	public function fraudAction() {
		
		$oid = $this->_request->getParam('oid');

   		$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');

		try {
    	$db->beginTransaction();
		$data = array('fraud' => '1');
		$where[] = "ordr_id = $oid";
		$db->update('mm_user_order', $data, $where);
		$db->commit(); $db->closeConnection();
		echo "Order ID #$oid has been marked as FRAUD.";
		} catch ( Exception $e ) {
		$db->rollback(); $db->closeConnection();
		echo "An error occured and Order ID #$oid could not be changed.";
		}

		exit;
		
	}
	
	
	
	
	
	
    private function GetGoogleData($a, $z, $c)
    {

    	$google_query = urlencode("$a, $z, $c");
						 
    	if (!($json = file("http://maps.google.com/maps/geo?q=".
    	$google_query."&output=json&oe=utf8&sensor=false&key=ABQIAAAApoW4fhlOOfY3UrTnHKIHYhRtLVm_nKQYUr2aYA-UE1zh88EgMhQhDeh_lB0bPAwbJnrds60CsKwuKA")))
    	{
			return false;
    	}

		if (!($google_data = json_decode(implode("",$json), true)))
   		{
   			return false;
   		}
//print_r($google_data);exit;
   		if (!is_array($google_data))
		{
			return false;
		}
			    		
		if ($google_data['Status']['code'] != 200)
		{
			return false;
   		}

   		//mail('marat@maratgleyzer.com',"google data, $sa, $sz",print_r($google_data,true));
   		
   		switch ($google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode']) {

   			case 'GB': $gd = $this->ParseGoogleData_GB($google_data); break;
   			case 'UK': $gd = $this->ParseGoogleData_UK($google_data); break;
   			case 'CA': $gd = $this->ParseGoogleData_CA($google_data); break;
   			default: $gd = $this->ParseGoogleData_US($google_data); break;
   			
   		}
   		
   		if (is_array($gd))
   			return $gd;
   		
   		return false;
    	
    }
    
	    
    
    
    private function ParseGoogleData_CA($google_data) {
	   		
    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode']))
    		$gd['country'] = $google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];			

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address'])) {
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Address']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'CA';
		}
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];			
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];			

		if (isset($google_data['Placemark'][0]['Point']['coordinates'][0]))
			$gd['longitude'] = $google_data['Placemark'][0]['Point']['coordinates'][0];
		
		if (isset($google_data['Placemark'][0]['Point']['coordinates'][1]))
			$gd['latitude'] = $google_data['Placemark'][0]['Point']['coordinates'][1];

		if (!is_array($gd))
   			return false;

		return $gd;
    	    	
    }
	
    
    
    
    
    
    private function ParseGoogleData_US($google_data) {

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode']))
    		$gd['country'] = $google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];			

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address'])) {
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Address']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'CA';
		}
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];			
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];			

		if (isset($google_data['Placemark'][0]['Point']['coordinates'][0]))
			$gd['longitude'] = $google_data['Placemark'][0]['Point']['coordinates'][0];
		
		if (isset($google_data['Placemark'][0]['Point']['coordinates'][1]))
			$gd['latitude'] = $google_data['Placemark'][0]['Point']['coordinates'][1];

		if (!is_array($gd))
   			return false;

		return $gd;
    	
    }
	
    
    
    
    
    
    private function ParseGoogleData_UK($google_data) {

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode']))
    		$gd['country'] = $google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];

//    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
//			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];			

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address'])) {
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Address']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'CA';
		}
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];			
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];			

		if (isset($google_data['Placemark'][0]['Point']['coordinates'][0]))
			$gd['longitude'] = $google_data['Placemark'][0]['Point']['coordinates'][0];
		
		if (isset($google_data['Placemark'][0]['Point']['coordinates'][1]))
			$gd['latitude'] = $google_data['Placemark'][0]['Point']['coordinates'][1];

		if (!is_array($gd))
   			return false;

		return $gd;
    	

    }
	
    
    
    
    
    
    private function ParseGoogleData_GB($google_data) {

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode']))
    		$gd['country'] = $google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];

//    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
//			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];			

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address'])) {
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Address']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'CA';
		}
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName']))
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];			
			
   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];

		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber']))
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];			

		if (isset($google_data['Placemark'][0]['Point']['coordinates'][0]))
			$gd['longitude'] = $google_data['Placemark'][0]['Point']['coordinates'][0];
		
		if (isset($google_data['Placemark'][0]['Point']['coordinates'][1]))
			$gd['latitude'] = $google_data['Placemark'][0]['Point']['coordinates'][1];

		if (!is_array($gd))
   			return false;

		return $gd;

    }
	
    
    
    
    
	
	
	
	
	private function MailLoginReceipt($o) {

		if ($o['camp_id'] == 1234570)
			return $this->EmergencyLoginReceipt($o);

		if ($o['offr_id'] == 1234574)
			return $this->EmergencyLoginReceipt($o);

		if (($o['camp_id'] == 1234571) ||
			($o['camp_id'] == 1234572) ||
			($o['camp_id'] == 1234573) ||
			($o['offr_id'] == 1234571) ||
			($o['offr_id'] == 1234572) ||
			($o['offr_id'] == 1234573))
			{
			
		$mail = new Zend_Mail();

		$mail_body =
"

Dear $o[bill_first] $o[bill_last],

Congratulations and thank you for signing up for your instant access to EZ Profit System. Your Order id number is: $o[ordr_id]

We look forward to working with you and helping you develop your online marketing techniques through our membership site.

Below, you should find your login username and password:

Username: $o[username]
Password: $o[password]

Follow this link to the login page and log into your membership:
http://ezdollarclub.com/

================================================================

The total of your order is: \$$o[total_sale]

Call us at (800) 609-8384 if you have any questions! For further details about our service, go to: http://ezdollarclub.com/p/terms.html

Sincerely,
EZ Profit Support

";

		$mail->setBodyText($mail_body);
		$mail->setFrom('support@ezdollarclub.com', 'Support EZDollar Club');
		$mail->addTo($o['bill_email'], "$o[bill_first] $o[bill_last]");
		//$mail->addBcc('marat@maratgleyzer.com', 'Marat Gleyzer');
		$mail->setSubject("$o[bill_first], login and order confirmation!");
		if ($mail->send()) return true;
		
		return false;
		
			}

	}
    
    
	
	
	
	
	private function EmergencyLoginReceipt($o) {
	
		$mail = new Zend_Mail();

		$mail_body =
"

Dear $o[bill_first] $o[bill_last],

You have now been given access to http://easygoprosystems.com! 

Your membership site is packed full of success stories and useful tips and tricks of the online marketing industry, updated weekly!

Below, you should find your login username and password:

Username: $o[username]
Password: $o[password]

================================================================

The total of your order is: \$$o[total_sale]

Call us at (800) 609-8384 if you have any questions!

Sincerely,
EZ Profit Support

";

		$mail->setBodyText($mail_body);
		$mail->setFrom('support@ezdollarclub.com', 'Support EZDollar Club');
		$mail->addTo($o['bill_email'], "$o[bill_first] $o[bill_last]");
		//$mail->addBcc('marat@maratgleyzer.com', 'Marat Gleyzer');
		$mail->setSubject("$o[bill_first], login and order confirmation!");
		if ($mail->send()) return true;
		
		return false;

	}
	
	
	
	
	
	
	
	private function SendErrorNotice($g, $o, $r) {

		$mail = new Zend_Mail();

		$mail_body =
"

!!GATE ID #$g[plan_id], $g[gate_name] HAS ERRORED AND BEEN DISABLED!!

Current Purchase Level: $g[purchases] of Threshold: $g[threshold]

The gateway returned the following RAW query string: $r

The following information was submitted to the gateway for processing:

";
		
		foreach ($o as $key => $value) $mail_body .= "$key => $value \n";

		$mail->setBodyText($mail_body);
		$mail->setFrom('gateway@proleroinc.com', 'GATEWAY ERROR');
		$mail->addTo('marat@maratgleyzer.com', 'Marat');
		$mail->addTo('waynard@pruloo.com', 'Waynard');
		$mail->addTo('pete@prolero.com', 'Pete');
		$mail->setSubject("DISABLED! $g[gate_name]");
		if ($mail->send()) return true;
		
		return false;

	}
	
	
	
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function CheckRequiredData($post) {
		
		if (!isset($post['mm-camp_id'])) { echo "Missing required parameter 'mm-camp_id'."; exit; }
		if (!isset($post['mm-user_id'])) { echo "Missing required parameter 'mm-user_id'."; exit; }

		$this->user_id = $post['mm-user_id'];
		$this->camp_id = $post['mm-camp_id'];
		
	}
	

	
	
	private function CheckContactData($post) {

		if (!isset($post['mm-bill_first']) || ($post['mm-bill_first'] == '') || eregi("^[ \'\`\.\,\-]+$",$post['mm-bill_first']) || !eregi("^[A-Z \'\`\.\,\-]+$",$post['mm-bill_first'])) {
			echo "Missing or invalid first name."; exit;
		}

		if (!isset($post['mm-bill_last']) || ($post['mm-bill_last'] == '') || eregi("^[ \'\`\.\,\-]+$",$post['mm-bill_last']) || !eregi("^[A-Z \'\`\.\,\-]+$",$post['mm-bill_last'])) {
			echo "Missing or invalid last name."; exit;
		}

		if (!isset($post['mm-bill_address']) || ($post['mm-bill_address'] == '') || eregi("^[ \'\`\.\,\/\#\-]+$",$post['mm-bill_address']) || !eregi("^[A-Z0-9 \'\`\.\,\/\#\-]+$",$post['mm-bill_address'])) {
			echo "Missing or invalid street address."; exit;
		}
		
		if (!isset($post['mm-bill_zip']) || ($post['mm-bill_zip'] == '') || eregi("^[ \-]+$",$post['mm-bill_zip']) || !eregi("^[0-9A-Z \-]+$",$post['mm-bill_zip'])) {
			echo "Missing or invalid zip code. Zip code format should be XXXXX."; exit;
		}
		
		if (!isset($post['mm-bill_email']) || ($post['mm-bill_email'] == '') || !eregi("^[0-9A-Z\_\#\.\-]+@[0-9A-Z\.\-]+\.[A-Z]{2,4}$",$post['mm-bill_email'])) {
			echo "Missing or invalid email address. Email address format should be xx(x...)@xx(x...).xx(xx)."; exit;
		}
		
		$this->CheckContactPhone($post);

	}
	
	
	
	
	private function CheckBillingData($post) {

//		if (!isset($post['mm-card_type']) || ($post['mm-card_type'] == '') || !eregi("^(VISA|MASTERCARD|AMEX|DISCOVER)$",$post['mm-card_type'])) {
//			echo "Missing or invalid credit card type. Visa, Mastercard, Amex, Discover accepted."; exit;
//		}

		if (!isset($post['mm-card_number']) || ($post['mm-card_number'] == '') || !eregi("^[0-9]{15,16}$",$post['mm-card_number'])) {
			echo "Missing or invalid credit card number. Number format should be XXXXXXXXXXXXXXX(X)."; exit;
		}

		if (!isset($post['mm-expires_mm']) || ($post['mm-expires_mm'] == '') || !eregi("^[0-9]{2}$",$post['mm-expires_mm'])) {
			echo "Missing or invalid credit card expiration month. Format should be (01...12)."; exit;
		}

		if (!isset($post['mm-expires_yy']) || ($post['mm-expires_yy'] == '') || !eregi("^[0-9]{2}$",$post['mm-expires_yy'])) {
			echo "Missing or invalid credit card expiration year. Format should be (09...20)."; exit;
		}

	}

	
	
	private function CheckShippingData($post) {

		if (isset($post['mm-same_as_billing']) && ($post['mm-same_as_billing'] > 0))
			return false;
		
		if (!isset($post['mm-ship_first']) || ($post['mm-ship_first'] == '') || eregi("^[ \'\`\.\,\-]+$",$post['mm-ship_first']) || !eregi("^[A-Z \'\`\.\,\-]+$",$post['mm-ship_first'])) {
			echo "Missing or invalid first name."; exit;
		}

		if (!isset($post['mm-ship_last']) || ($post['mm-ship_last'] == '') || eregi("^[ \'\`\.\,\-]+$",$post['mm-ship_last']) || !eregi("^[A-Z \'\`\.\,\-]+$",$post['mm-ship_last'])) {
			echo "Missing or invalid last name."; exit;
		}

		if (!isset($post['mm-ship_address']) || ($post['mm-ship_address'] == '') || eregi("^[ \'\`\.\,\/\#\-]+$",$post['mm-ship_address']) || !eregi("^[A-Z0-9 \'\`\.\,\/\#\-]+$",$post['mm-ship_address'])) {
			echo "Missing or invalid street address."; exit;
		}
		
		if (!isset($post['mm-ship_zip']) || ($post['mm-ship_zip'] == '') || eregi("^[ \-]+$",$post['mm-ship_zip']) || !eregi("^[0-9A-Z \-]+$",$post['mm-ship_zip'])) {
			echo "Missing or invalid zip code. Zip code format should be XXXXX."; exit;
		}
		
		if (!isset($post['mm-ship_email']) || ($post['mm-ship_email'] == '') || !eregi("^[^[0-9A-Z\_\#\.\-]+@[0-9A-Z\.\-]+\.[A-Z]{2,4}$",$post['mm-ship_email'])) {
			echo "Missing or invalid email address. Email address format should be xx(x...)@xx(x...).xx(xx)."; exit;
		}
		
		$this->CheckShippingPhone($post);

	}
	

	
	
	private function GetCardSecurityCode($post) {
		
		$this->cvv_code = $post['mm-cvv_code'];
		
	}
	
	
	
	
	private function CheckCountryOfOrigin($post) {

		if (!isset($post['mm-bill_country']) || ($post['mm-bill_country'] == '') || !eregi("^[A-Z]{2}$",$post['mm-bill_country'])) {
			echo "Missing or invalid country code. Format should be (US, GB, UK, CA, etc..)."; exit;
		}

		$this->country = $post['mm-bill_country'];
		
	}
	

	
	private function CheckContactPhone($post) {
			
		if (eregi("(GB|UK)",$this->country)) {

			if (isset($post['mm-bill_phone'])) {
				if (($post['mm-bill_phone'] == '') || eregi("^[ \-]+$",$post['mm-bill_phone']) || !eregi("^[0-9 \-]$",$post['mm-bill_phone'])) {
					echo "Missing or invalid phone number. Phone number format should be XXXXXXX-XXXXXX."; exit;
				}
			}
			
			if (!isset($post['mm-bill_phone_3']) || ($post['mm-bill_phone_3'] == '') || !eregi("^[0-9]{3,7}$",$post['mm-bill_phone_3'])) {
				echo "Missing or invalid phone number prefix. Phone number format should be XXXXX(XX)."; exit;
			}

			if (!isset($post['mm-bill_phone_4']) || ($post['mm-bill_phone_4'] == '') || !eregi("^[0-9]{3,6}$",$post['mm-bill_phone_4'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXX(XXX)."; exit;
			}
			
		}
		
		else {
			
			if (isset($post['mm-bill_phone'])) {
				if (($post['mm-bill_phone'] == '') || eregi("^[ \-]+$",$post['mm-bill_phone']) || !eregi("^[0-9 \-]$",$post['mm-bill_phone'])) {
					echo "Missing or invalid phone number. Phone number format should be XXX-XXX-XXXX."; exit;
				}
			}
			
			if (!isset($post['mm-bill_phone_2']) || ($post['mm-bill_phone_2'] == '') || !eregi("^[0-9]{3}$",$post['mm-bill_phone_2'])) {
				echo "Missing or invalid phone number area code. Phone number format should be XXX."; exit;
			}

			if (!isset($post['mm-bill_phone_3']) || ($post['mm-bill_phone_3'] == '') || !eregi("^[0-9]{3}$",$post['mm-bill_phone_3'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXX."; exit;
			}

			if (!isset($post['mm-bill_phone_4']) || ($post['mm-bill_phone_4'] == '') || !eregi("^[0-9]{4}$",$post['mm-bill_phone_4'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXXX."; exit;
			}

		}

	}

	
	
	
	private function CheckShippingPhone($post) {
			
		if (eregi("(GB|UK)",$this->country)) {

			if (isset($post['mm-ship_phone'])) {
				if (($post['mm-ship_phone'] == '') || eregi("^[ \-]+$",$post['mm-ship_phone']) || !eregi("^[0-9 \-]$",$post['mm-ship_phone'])) {
					echo "Missing or invalid phone number. Phone number format should be XXXXXXX-XXXXXX."; exit;
				}
			}
			
			if (!isset($post['mm-ship_phone_3']) || ($post['mm-ship_phone_3'] == '') || !eregi("^[0-9]{3,7}$",$post['mm-ship_phone_3'])) {
				echo "Missing or invalid phone number prefix. Phone number format should be XXXXX(XX)."; exit;
			}

			if (!isset($post['mm-ship_phone_4']) || ($post['mm-ship_phone_4'] == '') || !eregi("^[0-9]{3,6}$",$post['mm-ship_phone_4'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXX(XXX)."; exit;
			}
			
		}
		
		else {
			
			if (isset($post['mm-ship_phone'])) {
				if (($post['mm-ship_phone'] == '') || eregi("^[ \-]+$",$post['mm-ship_phone']) || !eregi("^[0-9 \-]$",$post['mm-ship_phone'])) {
					echo "Missing or invalid phone number. Phone number format should be XXX-XXX-XXXX."; exit;
				}
			}
			
			if (!isset($post['mm-ship_phone_2']) || ($post['mm-ship_phone_2'] == '') || !eregi("^[0-9]{3}$",$post['mm-ship_phone_2'])) {
				echo "Missing or invalid phone number area code. Phone number format should be XXX."; exit;
			}

			if (!isset($post['mm-ship_phone_3']) || ($post['mm-ship_phone_3'] == '') || !eregi("^[0-9]{3}$",$post['mm-ship_phone_3'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXX."; exit;
			}

			if (!isset($post['mm-ship_phone_4']) || ($post['mm-ship_phone_4'] == '') || !eregi("^[0-9]{4}$",$post['mm-ship_phone_4'])) {
				echo "Missing or invalid phone number suffix. Phone number format should be XXXX."; exit;
			}

		}

	}
	
	
	
	
	
	private function ParseOrderData($post) {
		
    	foreach ($post as $key => $value) {
    		if (strlen($value) == 0) continue;
    		if (!eregi("mm-",$key)) continue;
    		$order[strtolower(substr(ltrim(rtrim($key)),3))] = $value;
    	}
    	
    	return $order;
		
	}
	


	
	private function FormatContactPhone($order) {

		if (eregi("(GB|UK)",$this->country)) {
			if (isset($order['bill_phone'])) {
				$order['bill_phone'] = eregi_replace("[^0-9]+","",$order['bill_phone']);
			}
			else {
				$order['bill_phone_3'] = eregi_replace("[^0-9]+","",$order['bill_phone_3']);
				$order['bill_phone_4'] = eregi_replace("[^0-9]+","",$order['bill_phone_4']);
				$order['bill_phone'] = $order['bill_phone_3']."-".$order['bill_phone_4'];
			}
		}
		
		else {
			if (isset($order['bill_phone'])) {
				$phone = eregi_replace("[^0-9]+","",$order['bill_phone']);
				$order['bill_phone_2'] = substr($phone,0,3);
				$order['bill_phone_3'] = substr($phone,3,3);
				$order['bill_phone_4'] = substr($phone,6,4);
				$order['bill_phone'] = substr($phone,0,3)."-".substr($phone,3,3)."-".substr($phone,6,4);
			}
			else {
				$order['bill_phone_2'] = eregi_replace("[^0-9]+","",$order['bill_phone_2']);
				$order['bill_phone_3'] = eregi_replace("[^0-9]+","",$order['bill_phone_3']);
				$order['bill_phone_4'] = eregi_replace("[^0-9]+","",$order['bill_phone_4']);
				$order['bill_phone'] = $order['bill_phone_2']."-".$order['bill_phone_3']."-".$order['bill_phone_4'];
			}
		}

		return $order;

	}
	
	

	private function FormatShippingPhone($order) {

		if (isset($order['same_as_billing']) && ($order['same_as_billing'] > 0)) {
			if (isset($order['same_as_billing'])) unset($order['same_as_billing']);
			return $order;
		}

		if (eregi("(GB|UK)",$this->country)) {
			if (isset($order['ship_phone'])) {
				$order['ship_phone'] = eregi_replace("[^0-9]+","",$order['ship_phone']);
			}
			else {
				$order['ship_phone_3'] = eregi_replace("[^0-9]+","",$order['ship_phone_3']);
				$order['ship_phone_4'] = eregi_replace("[^0-9]+","",$order['ship_phone_4']);
				$order['ship_phone'] = $order['ship_phone_3']."-".$order['ship_phone_4'];
			}
		}
		
		else {
			if (isset($order['ship_phone'])) {
				$phone = eregi_replace("[^0-9]+","",$order['ship_phone']);
				$order['ship_phone_2'] = substr($phone,0,3);
				$order['ship_phone_3'] = substr($phone,3,3);
				$order['ship_phone_4'] = substr($phone,6,4);
				$order['ship_phone'] = substr($phone,0,3)."-".substr($phone,3,3)."-".substr($phone,6,4);
			}
			else {
				$order['ship_phone_2'] = eregi_replace("[^0-9]+","",$order['ship_phone_2']);
				$order['ship_phone_3'] = eregi_replace("[^0-9]+","",$order['ship_phone_3']);
				$order['ship_phone_4'] = eregi_replace("[^0-9]+","",$order['ship_phone_4']);
				$order['ship_phone'] = $order['ship_phone_2']."-".$order['ship_phone_3']."-".$order['ship_phone_4'];
			}
		}

		return $order;

	}
	

	
	private function NormalizeContactData($order) {
		
   		$order['bill_first']	= ucwords(strtolower($order['bill_first']));
   		$order['bill_last']		= ucwords(strtolower($order['bill_last']));
    	$order['bill_address']	= ucwords(strtolower($order['bill_address']));
   		$order['bill_city']		= ucwords(strtolower($order['bill_city']));
   		$order['bill_zip']		= strtoupper(eregi_replace("[^0-9]+","",$order['bill_zip']));
    	$order['bill_state']	= strtoupper($order['bill_state']);
   		$order['bill_country']	= strtoupper($this->country);
   		$order['bill_email']	= strtolower($order['bill_email']);
   		
   		return $order;
   		
	}
	
	
	
	
	private function NormalizeBillingData($order) {
		
    	$order['card_number']	= eregi_replace("[^0-9]+","",$order['card_number']);
    	$order['expires_mm']	= eregi_replace("[^0-9]+","",$order['expires_mm']);
   		$order['expires_yy']	= eregi_replace("[^0-9]+","",$order['expires_yy']);
   		$order['expiration']	= $order['expires_mm'].$order['expires_yy'];
   		
   		return $order;
   		
	}
	
	
	
	private function NormalizeShippingData($order) {
		
		if (isset($order['same_as_billing']) && ($order['same_as_billing'] > 0)) {

			if (isset($order['ship_first'])) unset($order['ship_first']);
			if (isset($order['ship_last'])) unset($order['ship_last']);
			if (isset($order['ship_address'])) unset($order['ship_address']);
			if (isset($order['ship_city'])) unset($order['ship_city']);
			if (isset($order['ship_zip'])) unset($order['ship_zip']);
			if (isset($order['ship_state'])) unset($order['ship_state']);
			if (isset($order['ship_country'])) unset($order['ship_country']);
			if (isset($order['ship_email'])) unset($order['ship_email']);			

			return $order;

		}
		
   		$order['ship_first']	= ucwords(strtolower($order['ship_first']));
   		$order['ship_last']		= ucwords(strtolower($order['ship_last']));
    	$order['ship_address']	= ucwords(strtolower($order['ship_address']));
   		$order['ship_city']		= ucwords(strtolower($order['ship_city']));
   		$order['ship_zip']		= strtoupper(eregi_replace("[^0-9]+","",$order['ship_zip']));
    	$order['ship_state']	= strtoupper($order['ship_state']);
   		$order['ship_country']	= strtoupper($this->country);
   		$order['ship_email']	= strtolower($order['ship_email']);
   		
   		return $order;
   		
	}
		

	
	private function SetActionIds($post) {

		$this->ordr_id = 0;
		$this->offr_id = 0;
		$this->ship_id = 0;
		
		if (isset($post['mm-ordr_id']) && ($post['mm-ordr_id'] > 0))
			$this->ordr_id = $post['mm-ordr_id'];
		
		if (isset($post['mm-offr_id']) && ($post['mm-offr_id'] > 0))
			$this->offr_id = $post['mm-offr_id'];
		
		if (isset($post['mm-ship_id']) && ($post['mm-ship_id'] > 0))
			$this->ship_id = $post['mm-ship_id'];
			
	}	
	
	
	
	private function CheckIfCampaignExists() {

   		$db = $this->getInvokeArg('bootstrap')->getResource('db');

   		$sql =
"
select
c.camp_id,
c.user_id,
c.offr_id,
c.upsl_id,
c.bump_id,
c.impulse,
p.prod_id,
p.product_name,
c.impulse_campaign_id
from mm_user_campaign `c`, mm_user_offer `o`, mm_user_product `p`, mm_user `u`
where c.camp_id = $this->camp_id
  and c.user_id = $this->user_id
  and c.user_id = u.user_id
  and c.offr_id = o.offr_id
  and o.prod_id = p.prod_id
  and c.disable = 0
  and u.disable = 0
limit 1
";

   		$campaign = $db->fetchAll($sql);
		$db->closeConnection();

   		if (!is_array($campaign)) {
   			echo "An error has occured with the. Please click here, to go back and try again.";
   			exit;
   		}
   		if (count($campaign) == 0) {
   			echo "This product or service is no longer available.";
   			exit;
   		}

		return $campaign[0];   		

	}

	
	
	private function SaveLeadData($order) {

   		$db = $this->getInvokeArg('bootstrap')->getResource('db');

   		unset($order['cvv_code']);

   		$order['order_status'] = "LEAD";
   		$order['lead_date'] = date("Y-m-d");
		$order['lead_time'] = date("H:i:s");

		$order['username'] = $order['bill_email'];
		$order['password'] = base64_encode(substr($order['bill_email'],0,strpos($order['bill_email'],'@')));

		$order['ip_address'] = $_SERVER['REMOTE_ADDR'];
//var_dump($order_data); exit;			
   		try {
		  $db->beginTransaction();
		  $db->insert('mm_user_order', $order);
		  $oid = $db->lastInsertId('mm_user_order', 'ordr_id');
		  $db->commit(); $db->closeConnection();
		} catch ( Exception $e ) {
		  $db->rollback(); $db->closeConnection();
		  echo "Duplicate detected! You are attempting to submit a duplicate request.";
		  exit;
		}

		return $oid;

	}
	
	
	

	private function GetAffiliateId() {

		$affi_id = $this->_request->getParam('affid');
		if ($affi_id < 1) $affi_id = $this->_request->getParam('afid');

		return $affi_id;

	}


	
	private function GetSubAffiliateId() {

		return $this->_request->getParam('subs_id');

	}
	
	
	
	private function GetOrderForBilling() {

   		$db = $this->getInvokeArg('bootstrap')->getResource('db');

    	$sql =
"
select
distinct
o.*,
c.upsl_id,
c.bump_id,
c.impulse,
c.impulse_campaign_id,
c2s.ship_id,
s.ship_cost,
p.product_cost,
p.product_size,
p.product_name,
off.offer_cost,
off.recur_term,
off.trial_cost,
off.trial_term,
off.impulse_product_id
from mm_user_order `o`, mm_user_campaign `c`
LEFT JOIN campaign2shipping AS `c2s` on (c.camp_id = c2s.camp_id)
LEFT JOIN mm_user_shipping AS `s` on (c2s.ship_id = s.ship_id".($this->ship_id > 0 ? " and s.ship_id = $this->ship_id" : "")." and s.disable = 0),
mm_user_offer `off`, mm_user_product `p`, mm_user `u`
where o.ordr_id = $this->ordr_id
  and o.camp_id = c.camp_id
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = $this->user_id
  and u.disable = 0
  and c.disable = 0
limit 1;
";

    	$ALLDATA = $db->fetchAll($sql);
    	$db->closeConnection();

    	if (!is_array($ALLDATA)) {
   			echo "An error has occured with the Payment Processor. Please click here, to go back and try again.";
    		exit;
    	}

    	if (count($ALLDATA) == 0) {
    		echo "This product or service is no longer available.";
    		mail('marat@maratgleyzer.com','GetOrderForBilling',$sql);
    		exit;
    	}

    	if (!isset($this->offr_id) || (($this->offr_id == 0) && ($ALLDATA[0]['plan_id'] > 0)))
    		if (eregi("(SALE|REFUND)", $ALLDATA[0]['order_status'])) {
    			echo "Thank you! You have already completed this transaction.";
    			exit;
    		}

		return $ALLDATA[0];
		
	}
	
	
	
	private function GetAlternateCampaign() {

   		$db = $this->getInvokeArg('bootstrap')->getResource('db');

		$sql =
"
select
distinct
c.camp_id,
c.upsl_id,
c.bump_id,
c.impulse,
c.impulse_campaign_id,
c2s.ship_id,
s.ship_cost,
p.product_cost,
p.product_size,
p.product_name,
off.offer_cost,
off.recur_term,
off.trial_cost,
off.trial_term,
off.impulse_product_id
from mm_user_campaign `c`
LEFT JOIN campaign2shipping AS `c2s` on (c.camp_id = c2s.camp_id)
LEFT JOIN mm_user_shipping AS `s` on (c2s.ship_id = s.ship_id".($this->ship_id > 0 ? " and s.ship_id = $this->ship_id" : "")." and s.disable = 0),
mm_user_offer `off`, mm_user_product `p`, mm_user `u`
where c.camp_id = $this->camp_id
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = $this->user_id
  and u.disable = 0
  and c.disable = 0
limit 1
";

		$ALTERNATE = $db->fetchAll($sql);
   		$db->closeConnection();

    	if (!is_array($ALTERNATE)) {
   			echo "An error has occured with the Payment Processor. Please click here, to go back and try again.";
    		exit;
    	}

    	if (count($ALTERNATE) == 0) {
    		echo "This product or service is no longer available.";
    		mail('marat@maratgleyzer.com','GetAlternateCampaign',$sql);
    		exit;
    	}

		return $ALTERNATE[0];

	}

	
	
	
	private function GetAlternateOffer() {

   		$db = $this->getInvokeArg('bootstrap')->getResource('db');

   		$sql =
"
select
distinct
p.product_cost,
p.product_size,
p.product_name,
off.offer_cost,
off.recur_term,
off.trial_cost,
off.trial_term,
off.impulse_product_id
from mm_user_offer `off`, mm_user_product `p`, mm_user `u`
where off.offr_id = $this->offr_id
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = $this->user_id
  and u.disable = 0
limit 1
";
    	
	  	$ALTERNATE = $db->fetchAll($sql);
    	$db->closeConnection();

    	if (!is_array($ALTERNATE)) {
   			echo "An error has occured with the Payment Processor. Please click here, to go back and try again.";
    		exit;
    	}
    	
    	if (count($ALTERNATE) == 0) {
    		echo "This product or service is no longer available.";
    		mail('marat@maratgleyzer.com','GetAlternateOffer',$sql);
    		exit;
    	}
		
    	return $ALTERNATE[0];
		
		
		
	}
	
	
	
	
	
	private function GetOrderTotals($order, $storedOrder) {

    	$order['product_sale'] = 0;
    	$order['shipper_sale'] = 0;

		if ($storedOrder['trial_term'] > 0) {
			$order['product_sale'] += $storedOrder['trial_cost'];
			$order['rebill_date'] = date("Y-m-d", strtotime("+".$storedOrder['trial_term']." day", strtotime(date("Y-m-d"))));
		}
		elseif ($storedOrder['recur_term'] > 0) {
			$order['product_sale'] += ($storedOrder['offer_cost'] > $storedOrder['product_cost'] ? $storedOrder['offer_cost'] : $storedOrder['product_cost']);
			$order['rebill_date'] = date("Y-m-d", strtotime("+".$storedOrder['recur_term']." day", strtotime(date("Y-m-d"))));
		}
		else {
			$order['product_sale'] += ($storedOrder['offer_cost'] > $storedOrder['product_cost'] ? $storedOrder['offer_cost'] : $storedOrder['product_cost']);
		}
		if ($this->ship_id > 0) {
			$order['shipper_sale'] += $storedOrder['ship_cost'];
			$order['weight'] += $storedOrder['product_size'];
		}

    	$order['total_sale'] = $order['product_sale'] + $order['shipper_sale'];

    	return $order;

	}
	
	
	
	
	private function SaveSaleData($order) {
   		
		$db = $this->getInvokeArg('bootstrap')->getResource('db');

		unset($order['cvv_code']);
		
   		$order['sale_date'] = date("Y-m-d");
		$order['sale_time'] = date("H:i:s");

		$order_where[] = "ordr_id = ".$this->ordr_id;
		$order_where[] = "user_id = ".$this->user_id;
			
		unset($order['ordr_id']);
		unset($order['ship_cost']);
		unset($order['product_size']);
		unset($order['product_cost']);
		unset($order['offer_cost']);
		unset($order['recur_term']);
		unset($order['trial_cost']);
		unset($order['trial_term']);
		unset($order['upsl_id']);
		unset($order['bump_id']);
		unset($order['impulse']);
		unset($order['product_name']);
		unset($order['impulse_campaign_id']);
		unset($order['impulse_product_id']);
		
		$message = "";
		
		foreach ($order as $key => $value)
			$message .= "$key => $value \n";
		
		//mail('marat@maratgleyzer.com','SaveSaleData',$message);

		try {
    	$db->beginTransaction();
    	if ($this->offr_id > 0) {
    		$db->insert('mm_user_order', $order);
    	}
    	else {
    		$db->update('mm_user_order', $order, $order_where);
    	}
		$db->commit(); $db->closeConnection();
		} catch ( Exception $e ) {
		$db->rollback(); $db->closeConnection();
		echo "Thank you! You have already completed this transaction.";
		exit;
		}
		
		return true;
		
	}
	
	
	
	
	
	
	
	
}

?>