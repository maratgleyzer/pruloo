<?php

class Dashboard
{

	protected $MM_COUNTRY			= 'mm_country';
	protected $MM_GATEWAY			= 'mm_gateway';
	protected $MM_USER				= 'mm_user';
	protected $MM_USER_ORDER		= 'mm_user_order';
	protected $MM_USER_ORDER_NOTE	= 'mm_user_order_note';	
	protected $MM_USER_OFFER		= 'mm_user_offer';
	protected $MM_USER_PRODUCT		= 'mm_user_product';
	protected $MM_USER_GATEWAY		= 'mm_user_gateway';
	protected $MM_USER_SHIPPING		= 'mm_user_shipping';
	protected $MM_USER_CAMPAIGN		= 'mm_user_campaign';
	protected $CAMPAIGN2COUNTRY		= 'campaign2country';
	protected $CAMPAIGN2SHIPPING	= 'campaign2shipping';
	
	
	
	
	
	
	
	public function DrawForm($b) {
		
	    $f = new Zend_Form;

	    $i1 = $f->createElement('text', 'start_date', array('class' => 'date_input', 'size' => '10', 'readonly' => true, 'style' => 'margin-left:40px'));
		$i1->setRequired(false)
		   ->removeDecorator('label')
    	   ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(10, 10));

		$i2 = $f->createElement('text', 'stop_date', array('class' => 'date_input', 'size' => '10', 'readonly' => true, 'style' => 'margin-left:40px'));
		$i2->setRequired(false)
		   ->removeDecorator('label')
    	   ->removeDecorator('HtmlTag')
	       ->addValidator('StringLength', true, array(10, 10));

	    $campaigns = $this->GetCampaigns($b);
        
	    if (is_array($campaigns))
    	  	if (count($campaigns) > 0) {
       			$options['0'] = " -- select a campaign -- ";        
        			foreach ($campaigns as $campaign)
        				$options[$campaign['camp_id']] = $campaign['campaign'];
	        	}
				else $options['0'] = "No campaigns found.";
       		else $options['0'] = "An error occured.";
       	
		$i3 = $f->createElement('select', 'campaign', array('style' => 'width:92%;margin:8px 6px;', 'onchange' => 'this.form.submit()'));
		$i3->setRequired(false)
		   ->removeDecorator('label')
    	   ->removeDecorator('HtmlTag')
	       ->addMultiOptions($options);
                      
		// Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2)
		  ->addElement($i3);

		$f->isValid($_POST);
		if (isset($_POST['start_date']) && $_POST['start_date'] > 0)
           $f->start_date->setValue($_POST['start_date']);
        else $f->start_date->setValue(date('m/d/Y'));		  

		if (isset($_POST['stop_date']) && $_POST['stop_date'] > 0)
           $f->stop_date->setValue($_POST['stop_date']);
        else $f->stop_date->setValue(date('m/d/Y'));		  
        
		return $f;
		  
	}
	
	
	
	
	
	
		
	
	public function GetCampaigns($b) {
	
		$db = $b->getResource('db');
		
		$sql =
"
select
c.camp_id,
c.campaign
from $this->MM_USER_CAMPAIGN `c`, $this->MM_USER `u`
where c.user_id = 12345
  and c.user_id = u.user_id
  and c.disable = 0
order by c.camp_id desc
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return false;
		}
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	public function TotalNewOrders($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.retry < 1 and o.rebill < 1
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
	
				
	
	public function NewOrderSales($b) {

		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);

		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));

		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];

		$sql =
"
select SUM(o.total_sale) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.retry < 1 and o.rebill < 1
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}

	}
	
	
	
	
	
	


	
	public function TotalCurrentRebills($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.rebill > 0
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	

	
	public function CurrentRebillSales($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select SUM(o.total_sale) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.rebill > 0
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	

	
	public function TomorrowsRebills($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.rebill_date = \"$stop_date\" + INTERVAL 1 DAY
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
		
	


	
	public function TotalFutureRebills($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.rebill_date > \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'PENDING')
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
		
	
	
	
	
	
	
	
	public function TotalDeclinedOrders($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'DECLINE' or o.order_status = 'ERROR')
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
		
	

	
	
	
	
		
	
	public function DeclinedNewOrders($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'DECLINE' or o.order_status = 'ERROR')
  and rebill < 1
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
		
	
	
	
	
	
	
		
	public function DeclinedRebills($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'DECLINE' or o.order_status = 'ERROR')
  and rebill > 0
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
		

	
	
	
	
	
	
	
	public function TotalVoidsRefunds($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.order_status = 'REFUND'
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
		
	
	public function VoidsRefundsValue($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select SUM(o.total_sale) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.order_status = 'REFUND'
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	public function NewOrderRefunds($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.order_status = 'REFUND'
  and rebill < 1
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	public function RebillRefunds($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.order_status = 'REFUND'
  and rebill > 0
  and o.user_id = 12345
  and c.disable = 0  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	

	
	
	public function TotalArchiveOrders($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select count(*) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.user_id = 12345
  and c.disable = 1  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
	
	
	public function ActiveProspects($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct o.bill_email) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'LEAD' or o.order_status = 'DECLINE' or o.order_status = 'ERROR' or o.order_status = 'REFUND')
  and o.user_id = 12345
  and c.disable = 0
limit 1  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	

	
	
	
	
	
	public function ActiveCustomers($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct o.bill_email) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and o.order_status = 'SALE'
  and o.user_id = 12345
  and c.disable = 0
limit 1  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
		
	
	public function BlacklistedCustomers($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct o.bill_email) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
where o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and (o.order_status = 'SALE' or o.order_status = 'REFUND' or o.order_status = 'DECLINE')
  and o.user_id = 12345
  and o.blacklist = 1
  and c.disable = 0
limit 1  
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
	public function ActiveAffiliates($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct u.user_id) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER `u`
where u.referer = 12345
  and u.referer = o.user_id 
  and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id)
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and c.disable = 0
  and u.affiliate = 1
  and u.disable = 0
limit 1
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}

	
	
	
	
	
		
	
	public function PendingAffiliates($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct u.user_id) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER `u`
where o.user_id = 12345
  and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id)
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and c.disable = 0
  and u.affiliate = 1
limit 1
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	

			
	
	public function BlacklistedAffiliates($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select count(distinct u.user_id) as `total`
from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER `u`
where o.user_id = 12345
  and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id)
  and o.camp_id = c.camp_id ".($campaign > 0 ? "and c.camp_id = $campaign" : "")."
  and c.disable = 0
  and u.affiliate = 1
  and u.disable = 1
limit 1
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
			
	
	public function UnusedCapacity($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select (SUM(ug.threshold) - SUM(ug.purchases)) as `total`
from $this->MM_USER_GATEWAY `ug`
where ug.user_id = 12345
  and ug.disable = 0
limit 1
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
			
	
	public function PurchasesTotal($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select SUM(ug.purchases) as `total`
from $this->MM_USER_GATEWAY `ug`
where ug.user_id = 12345
  and ug.disable = 0
limit 1
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
			
	
	public function ThresholdTotal($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select SUM(ug.threshold) as `total`
from $this->MM_USER_GATEWAY `ug`
where ug.user_id = 12345
  and ug.disable = 0
limit 1
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
		
			
	
	public function FirstMonthRebillRate($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select SUM(ug.threshold) as `total`
from $this->MM_USER_GATEWAY `ug`
where ug.user_id = 12345
  and ug.disable = 0
limit 1
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
		
			
	
	public function SecondMonthRebillRate($b) {
		
		$db = $b->getResource('db');

		$campaign = (isset($_POST['campaign']) && $_POST['campaign'] > 0 ? $_POST['campaign'] : 0);
		
		$sql =
"
select SUM(ug.threshold) as `total`
from $this->MM_USER_GATEWAY `ug`
where ug.user_id = 12345
  and ug.disable = 0
limit 1
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0]['total'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return "An error has occured.";
		}
		
	}
	
	
	
	
	
	
	
	
	
	
}

?>