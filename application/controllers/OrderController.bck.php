<?php

class OrderController extends Zend_Controller_Action
{

    public function indexAction()
    {

    	ini_set('error_reporting','~E_NOTICE');

    	$post = $this->_request->getPost();
  	
    	foreach ($post as $key => $value) {
    		if (strlen($value) == 0) continue;
    		if (!eregi("mm-",$key)) continue;
    		$order_data[substr(ltrim(rtrim($key)),3)] = $value;
    	}

		if (!isset($order_data['ordr_id']) || ($order_data['ordr_id'] == 0) || ($order_data['ordr_id'] == "")) {

	   		$b = $this->getInvokeArg('bootstrap');
   			$db = $b->getResource('db');

    		$sql =
"
select
c.camp_id,
c.user_id,
c.offr_id,
c.impulse,
p.prod_id,
p.product_name,
c.impulse_campaign_id
from mm_user_campaign `c`, mm_user_offer `o`, mm_user_product `p`, mm_user `u`
where c.camp_id = ".$order_data['camp_id']."
  and c.user_id = ".$order_data['user_id']."
  and c.user_id = u.user_id
  and c.offr_id = o.offr_id
  and o.prod_id = p.prod_id
  and c.disable = 0
  and u.disable = 0
limit 1
";

    		$campaign = $db->fetchAll($sql);

    		if (!is_array($campaign)) {
    			echo "<a onclick=\"mm_back_to_contact();\" href=\"javascript:\">An error has occured with the. Please click here, to go back and try again.</a>";
    			exit;
    		}
    		if (count($campaign) == 0) {
    			echo "This product or service is no longer available.";
    			exit;
    		}

    		$order_data['bill_country'] = $this->_request->getParam('mm-bill_country');
    		$order_data['bill_zip'] = eregi_replace("[^A-Z0-9]+","",$order_data['bill_zip']);

			$gd = $this->GetGoogleData($order_data['bill_address'],$order_data['bill_zip'],$order_data['bill_country']);
			if (!is_array($gd)) {
    			echo "<a onclick=\"mm_back_to_contact();\" href=\"javascript:\">We were unable to verify your address. Please click here to go back and make sure you've entered your correct address and zip code.</a>";
    			exit;
			}

			$order_data['bill_country'] = $gd['country'];
			$order_data['bill_state'] = $gd['state'];
			$order_data['bill_city'] = $gd['city'];

	    	$order_data['affi_id'] = $this->_request->getParam('affid');
    		$order_data['subs_id'] = $this->_request->getParam('sid');

	    	if ($order_data['affi_id'] < 1)
    			$order_data['affi_id'] = $this->_request->getParam('afid');

    		//normalize
    		$order_data['bill_first'] = ucwords(strtolower($order_data['bill_first']));
    		$order_data['bill_last'] = ucwords(strtolower($order_data['bill_last']));
	    	$order_data['bill_address'] = ucwords(strtolower($order_data['bill_address']));
    		$order_data['bill_city'] = ucwords(strtolower($order_data['bill_city']));
    		$order_data['bill_zip'] = strtoupper($order_data['bill_zip']);
	    	$order_data['bill_state'] = strtoupper($order_data['bill_state']);
    		$order_data['bill_country'] = strtoupper($order_data['bill_country']);
    		$order_data['bill_email'] = strtolower($order_data['bill_email']);

	    	$order_data['bill_phone'] 		= (isset($order_data['bill_phone_1']) ? $order_data['bill_phone_1']."-" : "").
											  (eregi("(UK|GB)",$order_data['bill_country']) ? "" : (isset($order_data['bill_phone_2']) ? $order_data['bill_phone_2'] : "")).
											  (isset($order_data['bill_phone_3']) ? (eregi("(UK|GB)",$order_data['bill_country']) ? $order_data['bill_phone_3'] : "-".$order_data['bill_phone_3']) : "").
											  (isset($order_data['bill_phone_4']) ? "-".$order_data['bill_phone_4'] : "").
											  (isset($order_data['bill_phone_5']) ? "-".$order_data['bill_phone_5'] : "");

			if (isset($order_data['same_as_billing']) &&
			   ($order_data['same_as_billing'] < 1)) {
				$order_data['ship_phone']	= (isset($order_data['ship_phone_1']) ? $order_data['ship_phone_1']."-" : "").
											  (eregi("(UK|GB)",$order_data['ship_country']) ? "" : (isset($order_data['ship_phone_2']) ? $order_data['ship_phone_2'] : "")).
											  (isset($order_data['ship_phone_3']) ? "-".$order_data['ship_phone_3'] : "").
											  (isset($order_data['ship_phone_4']) ? "-".$order_data['ship_phone_4'] : "").
											  (isset($order_data['ship_phone_5']) ? "-".$order_data['ship_phone_5'] : "");
				$order_data['ship_first']	= (isset($order_data['ship_first']) ? ucwords(strtolower($order_data['ship_first'])) : "");
			   	$order_data['ship_last']	= (isset($order_data['ship_last']) ? ucwords(strtolower($order_data['ship_last'])) : "");
				$order_data['ship_address']	= (isset($order_data['ship_address']) ? ucwords(strtolower($order_data['ship_address'])) : "");
				$order_data['ship_city']	= (isset($order_data['ship_city']) ? ucwords(strtolower($order_data['ship_city'])) : "");
				$order_data['ship_zip']		= (isset($order_data['ship_zip']) ? strtoupper($order_data['ship_zip']) : "");
				$order_data['ship_state']	= (isset($order_data['ship_state']) ? strtoupper($order_data['ship_state']) : "");
				$order_data['ship_country'] = (isset($order_data['ship_country']) ? strtoupper($order_data['ship_country']) : "");
				$order_data['ship_email']	= (isset($order_data['ship_email']) ? strtolower($order_data['ship_email']) : "");
			}

			unset($order_data['cvv_code']);
    		
    		$order_data['order_status'] = "LEAD";
    		$order_data['lead_date'] = date("Y-m-d");
			$order_data['lead_time'] = date("H:i:s");

			$order_data['username'] = $order_data['bill_email'];
			$order_data['password'] = base64_encode(substr($order_data['bill_email'],0,strpos($order_data['bill_email'],'@')));
			
			$order_data['ip_address'] = $_SERVER['REMOTE_ADDR'];
//var_dump($order_data);exit;			
    		try { $db->beginTransaction();
    			  $db->insert('mm_user_order', $order_data);
				  $oid = $db->lastInsertId('mm_user_order', 'ordr_id');
				  $db->commit(); $db->closeConnection();
			} catch ( Exception $e ) {
				  $db->rollback();
				  $db->closeConnection();
				  echo "Duplicate detected! You are attempting to submit a duplicate request.";
				  exit;
			}

			$order_data['ordr_id'] = $oid;
			$order_data['product_name'] = $campaign[0]['product_name']; 

			if ($campaign[0]['impulse'] > 0) {
				$order_data['impulse_campaign_id'] = $campaign[0]['impulse_campaign_id'];
				$impulse = $this->SendLeadToImpulse($order_data, 1);
			}

			echo $oid;
			exit;

		}

    	$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');

    	$sql =
"
select
distinct
o.*,
c.upsl_id,
c.impulse,
c.impulse_campaign_id,
c2c.country,
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
LEFT JOIN campaign2country AS `c2c` on (c.camp_id = c2c.camp_id".($order_data['bill_country'] ? " and c2c.country = \"".$order_data['bill_country']."\"" : "")." and c2c.disable = 0)
LEFT JOIN mm_country AS `co` on (c2c.country = co.abbr)
LEFT JOIN campaign2shipping AS `c2s` on (c.camp_id = c2s.camp_id)
LEFT JOIN mm_user_shipping AS `s` on (c2s.ship_id = s.ship_id and s.disable = 0),
mm_user_offer `off`, mm_user_product `p`, mm_user `u`
where o.ordr_id = ".$order_data['ordr_id']."
  and o.camp_id = c.camp_id
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = ".$order_data['user_id']."
  and u.disable = 0
  and c.disable = 0
limit 1;
";
    	
    	$ALLDATA = $db->fetchAll($sql);

    	if (!is_array($ALLDATA)) {
   			echo "<a onclick=\"mm_back_to_billing();\" href=\"javascript:\">An error has occured with the Payment Processor. Please click here, to go back and try again.</a>";
   			$db->closeConnection();
    		exit;
    	}
    	
    	if (count($ALLDATA) == 0) {
    		echo "This product or service is no longer available.";
    		$db->closeConnection();
    		exit;
    	}

    	if ((!isset($order_data['offr_id']) || ($order_data['offr_id'] == 0)) && ($ALLDATA[0]['plan_id'] > 0))
    		if (eregi("(SALE|REFUND)", $ALLDATA[0]['order_status'])) {
    			echo "Thank you! You have already completed this transaction.";
    			$db->closeConnection();
    			exit;
    		}

		if ($ALLDATA[0]['camp_id'] != $order_data['camp_id']) {
			
			$sql =
"
select
distinct
c.camp_id,
c.upsl_id,
c.impulse,
c.impulse_campaign_id,
c2c.country,
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
LEFT JOIN campaign2country AS `c2c` on (c.camp_id = c2c.camp_id".($order_data['bill_country'] ? " and c2c.country = \"".$order_data['bill_country']."\"" : "")." and c2c.disable = 0)
LEFT JOIN mm_country AS `co` on (c2c.country = co.abbr)
LEFT JOIN campaign2shipping AS `c2s` on (c.camp_id = c2s.camp_id)
LEFT JOIN mm_user_shipping AS `s` on (c2s.ship_id = s.ship_id and s.disable = 0),
mm_user_offer `off`, mm_user_product `p`, mm_user `u`
where c.camp_id = ".$order_data['camp_id']."
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = ".$order_data['user_id']."
  and u.disable = 0
  and c.disable = 0
limit 1
";
    	
  		  	$PROMO = $db->fetchAll($sql);

    		if (!is_array($PROMO)) {
   				echo "<a onclick=\"mm_back_to_billing();\" href=\"javascript:\">An error has occured with the Payment Processor. Please click here, to go back and try again.</a>";
   				$db->closeConnection();
    			exit;
    		}
    	
    		if (count($PROMO) == 0) {
    			echo "This product or service is no longer available.";
    			$db->closeConnection();
    			exit;
    		}
		
			foreach ($PROMO[0] as $key => $value)
				$ALLDATA[0][$key] = $PROMO[0][$key];
    		
		}

    	if (($order_data['offr_id'] > 0) && ($ALLDATA[0]['offr_id'] != $order_data['offr_id'])) {
			
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
where off.offr_id = ".$order_data['offr_id']."
  and off.prod_id = p.prod_id
  and p.user_id = u.user_id
  and u.user_id = ".$order_data['user_id']."
  and u.disable = 0
limit 1
";
    	
  		  	$UPSELL = $db->fetchAll($sql);

    		if (!is_array($UPSELL)) {
   				echo "<a onclick=\"mm_back_to_billing();\" href=\"javascript:\">An error has occured with the Payment Processor. Please click here, to go back and try again.</a>";
   				$db->closeConnection();
    			exit;
    		}
    	
    		if (count($UPSELL) == 0) {
    			echo "This product or service is no longer available.";
    			$db->closeConnection();
    			exit;
    		}
		
			foreach ($UPSELL[0] as $key => $value)
				$ALLDATA[0][$key] = $UPSELL[0][$key];

		}
		
    	$order_data['product_sale'] = 0;
    	$order_data['shipper_sale'] = 0;
    		
		if ($ALLDATA[0]['trial_term'] > 0) {
			$order_data['product_sale'] += $ALLDATA[0]['trial_cost'];
			$order_data['rebill_date'] = date("Y-m-d", strtotime("+".$ALLDATA[0]['trial_term']." day", strtotime(date("Y-m-d"))));
		}
		elseif ($ALLDATA['recur_term'] > 0) {
			$order_data['product_sale'] += ($ALLDATA[0]['offer_cost'] > $ALLDATA[0]['product_cost'] ? $ALLDATA[0]['offer_cost'] : $ALLDATA[0]['product_cost']);
			$order_data['rebill_date'] = date("Y-m-d", strtotime("+".$ALLDATA[0]['recur_term']." day", strtotime(date("Y-m-d"))));
		}
		else {
			$order_data['product_sale'] += ($ALLDATA[0]['offer_cost'] > $ALLDATA[0]['product_cost'] ? $ALLDATA[0]['offer_cost'] : $ALLDATA[0]['product_cost']);
		}
		if ($ALLDATA[0]['ship_id'] == $order_data['ship_id']) {
			$order_data['shipper_sale'] += $ALLDATA[0]['ship_cost'];
			$order_data['weight'] += $ALLDATA[0]['product_size'];
		}
					
		$order_data['expiration'] = (isset($order_data['expires_mm']) ? $order_data['expires_mm'] : "").
									(isset($order_data['expires_yy']) ? $order_data['expires_yy'] : "");

    	$order_data['total_sale'] = $order_data['product_sale'] + $order_data['shipper_sale'];
		
		$this->cvv_code = (isset($order_data['cvv_code']) ? $order_data['cvv_code'] : "");
		unset($order_data['cvv_code']);
		
    	$order_data['order_status'] = "SALE";
   		$order_data['sale_date'] = date("Y-m-d");
		$order_data['sale_time'] = date("H:i:s");
		
		foreach ($order_data as $key => $value)
			$ALLDATA[0][$key] = $order_data[$key];

		$order_data = $ALLDATA[0];
//		var_dump($order_data);exit;	
		
		$this->order_data = $order_data;
		$order_data = $this->BillGateway('default',false);
		$response = $order_data['order_status'];

		if (eregi("ERROR",$response)) {
			echo $response; exit; 
		}
		
		$order_where[] = "ordr_id = ".$order_data['ordr_id'];
		$order_where[] = "user_id = ".$order_data['user_id'];

		// only mail logins if its a sale
		// with rebilling and a "0" product size
		if (eregi("SALE",$order_data['order_status']) &&
			($order_data['recur_term'] > 0) && ($order_data['product_size'] == 0))
			$mailsent = $this->MailLoginReceipt($order_data);
			
		unset($order_data['ordr_id']);
		unset($order_data['ship_cost']);
		unset($order_data['product_size']);
		unset($order_data['product_cost']);
		unset($order_data['offer_cost']);
		unset($order_data['recur_term']);
		unset($order_data['trial_cost']);
		unset($order_data['trial_term']);
		unset($order_data['upsl_id']);
		unset($order_data['impulse']);
		unset($order_data['product_name']);
		unset($order_data['impulse_campaign_id']);
		unset($order_data['impulse_product_id']);

		try {
    	$db->beginTransaction();
    	if ($order_data['offr_id'] > 0) {
    		$db->insert('mm_user_order', $order_data);
    	}
    	else {
    		$db->update('mm_user_order', $order_data, $order_where);
    	}
		$db->commit();
		$db->closeConnection();
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		echo "ERROR"; exit;
		}

		if (($ALLDATA[0]['impulse'] > 0) && ($order_data['affi_id'] != 12474) && eregi("SALE",$response)) {
			$impulse = $this->SendLeadToImpulse($ALLDATA[0], 2);
			$impulse = $this->SendSaleToImpulse($ALLDATA[0]);
		}

		if (($ALLDATA[0]['upsl_id'] > 0) && ($order_data['affi_id'] != 12474) && eregi("SALE",$response))
			$response = "UPSELL";
		
		echo $response;
		exit;
		
    }
    	
    	
    	
    

    
    
    
    
    
    public function refundAction()
    {
    	
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
    
    
    
    
    
    
    
    
    
    
    
    public function rebillAction()
    {
    	
    	ini_set('error_reporting','~E_NOTICE');

    	if (!($order_data = $this->GetOrder())) {
    		exit;
    	}
    	
    	if (!($order_item_data = $this->GetOrderItem())) {
    		exit;
    	}
    	
		$order_data['rebill'] = "1";
		$order_data['sale_date'] = date("Y-m-d");
		$order_data['sale_time'] = date("H:i:s");
		
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
		
    	unset($order_data['ordr_id']);
		
		$b = $this->getInvokeArg('bootstrap');
    	$db = $b->getResource('db');
    	
		try {
    	$db->beginTransaction();
		$db->insert('mm_user_order', $order_data);
		$oid = $db->lastInsertId('mm_user_order', 'ordr_id');
		$db->commit(); $db->closeConnection();
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		}
		
		echo $response;
		exit;
    }
    
    
    
    
    
    
    
    
    public function retryAction() {
    	
    	ini_set('error_reporting','~E_NOTICE');

    	if (!($order_data = $this->GetOrder())) {
    		exit;
    	}

    	if (!($order_item_data = $this->GetOrderItem())) {
    		exit;
    	}
    	
		$order_data['sale_date'] = date("Y-m-d");
		$order_data['sale_time'] = date("H:i:s");
		
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
			$order_data['retry'] = '1';
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
		$order_where[] = "camp_id = ".$order_data['camp_id'];
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
		$db->commit();
		$db->closeConnection();
		echo "Order ID $oid has been CANCELLED and will no longer rebill or be able to log into the system.";
		if (!$this->refund) exit;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		echo "An error has occured, and the Order cannot be CANCELLED at this time.";
		if (!$this->refund) exit;
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
					$excluded_gates[] = $gateway['gate_id'];
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
						$order_status['retry'] = 1;
						$excluded_gates[] = $gateway['gate_id'];
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
						$order_status['retry'] = 1;
						$excluded_gates[] = $gateway['gate_id'];
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
.$o['ip_address'].
($sale == 1 ? "&delay=20" : "&reject=1");

		$response = $this->Curl('http://63.135.225.115/partials/mgr.php', $post, array());	

		//mail('marat@maratgleyzer.com','impulse partial secondary',($response ? $post."\n\n\n\n".$response : $post."\n\n\n\n"."NOTHING"));

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
.$o['sale_date']." ".$o['sale_time'].
"&SourceProductQuantitySold="
.$o['quantity'];
	
		$response = $this->Curl('https://www.impulse123.com/C60CF423-F7BF-4838-B204-9C8600FEFE7C/Lead/Insert', $post, array());
			
		mail('marat@maratgleyzer.com','impulse lead data',($response ? $post."\n\n\n\n".$response : $post."\n\n\n\n"."NOTHING"));
		
		return $response;
		
	}
	
	
	
	
	
	
	
	
	
	
	public function resendAction() {
		
		$oid = $this->_request->getParam('oid');
		$pid = $this->_request->getParam('pid');
		$user = $this->_request->getParam('user');
		
		if ($oid > 0) {
		
			$sql = "select ordr_id, camp_id, offr_id, username, password, bill_email, bill_first, bill_last, total_sale from mm_user_order where ordr_id = $oid limit 1";
		    	
   			$b = $this->getInvokeArg('bootstrap');
    		$db = $b->getResource('db');

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

			try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			$this->MailLoginReceipt($result[0]);
			echo "SUCCESS";
			} catch ( Exception $e ) {
			$db->closeConnection();
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
	
	
	
	
	
	
    private function GetGoogleData($sa="address",$sz="zip",$sc="country")
    {

    	$google_query = urlencode("$sa, $sz, $sc");
						 
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

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address']))
		{
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'CA';
		}
		
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

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address']))
		{
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'US';
		}
		
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

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
//			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];
			$gd['state'] = '';

		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address']))
		{
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'GB';
		}
		
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

    	if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']))
//			$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];
			$gd['state'] = '';

   		if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
   		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']))
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];
		}
		else if (isset($google_data['Placemark'][0]['AddressDetails']['Address']))
		{
			$address = explode(", ",$google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']);
			$gd['city'] = $address[count($address)-3];
			$gd['country'] = 'GB';
		}
		
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
	
	
	
	
	
}

?>