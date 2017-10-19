<?php

class Order
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
	
	
	public function __construct() { }
	
	
	public function DrawFindForm($p, $b) {
		
    	$f = new Zend_Form;

		$i1 = $f->createElement('text', 'bill_last', array('maxlength' => 24, 'style' => 'width:88%;margin:10px 6px;'));
		$i1->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addFilter('StringTrim');
				   
		$i2 = $f->createElement('text', 'bill_first', array('maxlength' => 24, 'style' => 'width:88%;margin:10px 6px;'));
		$i2->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addFilter('StringTrim');
         		   
		$i3 = $f->createElement('text', 'bill_email', array('maxlength' => 64, 'style' => 'width:88%;margin:10px 6px;'));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addFilter('StringTrim');
		   
		$i4 = $f->createElement('text', 'ordr_id', array('maxlength' => 8, 'style' => 'width:88%;margin:10px 6px;'));
		$i4->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addFilter('StringTrim');

        $campaigns = $this->GetCampaigns($b);
        
        if (is_array($campaigns))
        	if (count($campaigns) > 0) {
        		$options['0'] = " -- select a campaign -- ";        
        		foreach ($campaigns as $campaign)
        			$options[$campaign['camp_id']] = $campaign['campaign'];
        	}
			else $options['0'] = "No campaigns found.";
       	else $options['0'] = "An error occured.";
       	
		$i5 = $f->createElement('select', 'campaign', array('style' => 'width:92%;margin:8px 6px;'));
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $status_options = array("SALE" => "SALE", "LEAD" => "LEAD", "RETRY" => "RETRY", "DECLINE" => "DECLINE", "REFUND" => "REFUND");
           
		$i6 = $f->createElement('select', 'order_status', array('style' => 'width:85%;margin:8px 6px;'));
		$i6->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($status_options);

		$i7 = $f->createElement('text', 'card_number', array('maxlength' => 16, 'style' => 'width:88%;margin:10px 6px;'));
		$i7->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addFilter('StringTrim');

        $options = array();
        $affiliates = $this->GetAffiliates($b);
        
        if (is_array($affiliates))
        	if (count($affiliates) > 0) {
        		$options['0'] = " -- select an affiliate -- ";        
        		foreach ($affiliates as $affiliate)
        			$options[($affiliate['old_affi_id'] > 0 ? $affiliate['old_affi_id'] : $affiliate['user_id'])] = "$affiliate[user_last], $affiliate[user_first]";
        	}
			else $options['0'] = "No affiliates found.";
       	else $options['0'] = "An error occured.";
       	
		$i8 = $f->createElement('select', 'affi_id', array('style' => 'width:92%;margin:8px 6px;'));
		$i8->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $options = array();
        $gateways = $this->GetGateways($b);
        
        if (is_array($gateways))
        	if (count($gateways) > 0) {
        		$options['0'] = " -- select a gateway -- ";        
        		foreach ($gateways as $gateway)
        			$options[$gateway['plan_id']] = $gateway['gate_name'];
        	}
			else $options['0'] = "No gatewats found.";
       	else $options['0'] = "An error occured.";
       	
		$i9 = $f->createElement('select', 'plan_id', array('style' => 'width:92%;margin:8px 6px;'));
		$i9->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $options = array();
        $countries = $this->GetCountries($b);
        
        if (is_array($countries))
        	if (count($countries) > 0) {
        		$options['0'] = " -- select a country -- ";        
        		foreach ($countries as $country)
        			$options[$country['abbr']] = $country['verbose'];
        	}
			else $options['0'] = "No countries found.";
       	else $options['0'] = "An error occured.";
       	
		$i10 = $f->createElement('select', 'bill_country', array('style' => 'width:92%;margin:8px 6px;'));
		$i10->setRequired(false)
		    ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addMultiOptions($options);
           
        // Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2)
		  ->addElement($i3)
		  ->addElement($i4)
		  ->addElement($i5)
		  ->addElement($i6)
		  ->addElement($i7)
		  ->addElement($i8)
		  ->addElement($i9)
		  ->addElement($i10);

		$f->isValid($_POST);
		  
		return $f;
		  
	}
	
	
	
	
	public function DrawNoteForm() {

    	$f = new Zend_Form;
		         		   
		$i1 = $f->createElement('textarea', 'note', array('maxlength' => 255, 'rows' => 3, 'cols' => 70));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 255))
           ->addFilter('StringTrim');
           
        // Add elements to form:
		$f->addElement($i1);
		  
		return $f;
		
	}
	
	
	
	
	
	
	
	public function SaveNoteForm($f, $b) {

    	$mm = new Zend_Session_Namespace('moneymachine');
    	
		$data['ordr_id'] = $f['ordr_id'];
		$data['note'] = $f['note'];

    	$data['user_id'] = $mm->user_id;
    	$data['create_date'] = date('Y-m-d');
    	$data['create_time'] = date('H:m:s');
		$db = $b->getResource('db');

		$db->beginTransaction();
			
		try {
			$db->insert($this->MM_USER_ORDER_NOTE, $data);
			$db->commit(); $db->closeConnection();
			return true;
			} catch ( Exception $e ) {
			$db->rollback();
			$db->closeConnection(); echo $e;exit;
			return false;
			}
	
	}
	
	
	
	
	
	public function ListRecords($mm, $b) {
		
		$db = $b->getResource('db');

		$sql =
"
select
o.ordr_id,
o.plan_id,
o.void_id,
o.bill_first,
o.bill_last,
o.bill_email,
o.bill_phone,
o.total_sale,
o.order_status,
o.rebill_date,
o.retry,
o.rebill,
o.bank_check,
o.disable,
o.fraud,
o.lead_date,
o.lead_time,
o.sale_date,
o.sale_time,
o.void_date,
o.void_time,
tot.total_records
from (select count(*) as `total_records` from $this->MM_USER_ORDER `o2`"
.(isset($mm->search['campaign']) && ($mm->search['campaign'] > 0) ? ", $this->MM_USER_CAMPAIGN `c2`" : "").
"
	where o2.user_id = 12345
".
(isset($mm->search['order_status']) && (strlen($mm->search['order_status']) > 0) && (!eregi("RETRY",$mm->search['order_status'])) ? " and o2.order_status = \"".$mm->search['order_status']."\"" : "").
(isset($mm->search['order_status']) && (strlen($mm->search['order_status']) > 0) && (eregi("RETRY",$mm->search['order_status'])) ? " and o2.retry > 0" : "").
(isset($mm->search['affi_id']) && ($mm->search['affi_id'] > 0) ? " and o2.affi_id = ".$mm->search['affi_id'] : "").
(isset($mm->search['plan_id']) && ($mm->search['plan_id'] > 0) ? " and o2.plan_id = ".$mm->search['plan_id'] : "").
(isset($mm->search['bill_country']) && (strlen($mm->search['bill_country']) > 1) ? " and o2.bill_country = \"".$mm->search['bill_country']."\"" : "").
(isset($mm->search['bill_last']) && (strlen($mm->search['bill_last']) > 0) ? " and o2.bill_last like \"".$mm->search['bill_last']."%\"" : "").
(isset($mm->search['bill_first']) && (strlen($mm->search['bill_first']) > 0) ? " and o2.bill_first like \"".$mm->search['bill_first']."%\"" : "").
(isset($mm->search['bill_email']) && (strlen($mm->search['bill_email']) > 0) ? " and o2.bill_email like \"%".$mm->search['bill_email']."%\"" : "").
(isset($mm->search['ordr_id']) && (strlen($mm->search['ordr_id']) > 0) ? " and o2.ordr_id like \"%".$mm->search['ordr_id']."%\"" : "").
(isset($mm->search['card_number']) && (strlen($mm->search['card_number']) > 0) ? " and o2.card_number like \"%".$mm->search['card_number']."\"" : "").
(isset($mm->search['campaign']) && ($mm->search['campaign'] > 0) ? " and o2.camp_id = c2.camp_id and c2.camp_id = ".$mm->search['campaign'] : "").
"
) `tot`, $this->MM_USER_ORDER `o`"
.(isset($mm->search['campaign']) && ($mm->search['campaign'] > 0) ? ", $this->MM_USER_CAMPAIGN `c`" : "").
"
	where o.user_id = 12345
".
(isset($mm->search['order_status']) && (strlen($mm->search['order_status']) > 0) && (!eregi("RETRY",$mm->search['order_status'])) ? " and o.order_status = \"".$mm->search['order_status']."\"" : "").
(isset($mm->search['order_status']) && (strlen($mm->search['order_status']) > 0) && (eregi("RETRY",$mm->search['order_status'])) ? " and o.retry > 0" : "").
(isset($mm->search['affi_id']) && ($mm->search['affi_id'] > 0) ? " and o.affi_id = ".$mm->search['affi_id'] : "").
(isset($mm->search['plan_id']) && ($mm->search['plan_id'] > 0) ? " and o.plan_id = ".$mm->search['plan_id'] : "").
(isset($mm->search['bill_country']) && (strlen($mm->search['bill_country']) > 1) ? " and o.bill_country = \"".$mm->search['bill_country']."\"" : "").
(isset($mm->search['bill_last']) && (strlen($mm->search['bill_last']) > 0) ? " and o.bill_last like \"".$mm->search['bill_last']."%\"" : "").
(isset($mm->search['bill_first']) && (strlen($mm->search['bill_first']) > 0) ? " and o.bill_first like \"".$mm->search['bill_first']."%\"" : "").
(isset($mm->search['bill_email']) && (strlen($mm->search['bill_email']) > 0) ? " and o.bill_email like \"%".$mm->search['bill_email']."%\"" : "").
(isset($mm->search['ordr_id']) && (strlen($mm->search['ordr_id']) > 0) ? " and o.ordr_id like \"%".$mm->search['ordr_id']."%\"" : "").
(isset($mm->search['card_number']) && (strlen($mm->search['card_number']) > 0) ? " and o.card_number like \"%".$mm->search['card_number']."\"" : "").
(isset($mm->search['campaign']) && ($mm->search['campaign'] > 0) ? " and o.camp_id = c.camp_id and c.camp_id = ".$mm->search['campaign'] : "").
"
order by o.void_date desc, o.void_time desc, o.sale_date desc, o.sale_time desc, o.lead_date desc, o.lead_time desc
limit ".$mm->search['limit_start'].", ".$mm->search['show_per_page'];
//echo $sql; exit;
		try {
			$result = $db->fetchAll($sql);
			$db->closeConnection();
			return $result;
			} catch ( Exception $e ) {
			$db->closeConnection();
			return $e;
			}
		
	}
	
	
	
	
	
	
	
	public function ViewOrder($id, $b) {

		$db = $b->getResource('db');
		
		$sql =
"
select
o.ordr_id,
o.user_id,
o.lead_id,
o.camp_id,
o.plan_id,
o.ship_id,
o.affi_id,
o.subs_id,
o.void_id,
o.disable,
o.bill_address,
o.bill_city,
o.bill_state,
o.bill_zip,
o.bill_country,
o.ship_address,
o.ship_city,
o.ship_state,
o.ship_zip,
o.ship_country,
o.shipper_sale,
o.quantity,
o.weight,
o.delivery,
o.signature,
o.tracking,
o.country,
o.total_sale,
o.product_sale,
o.shipper_sale,
o.card_type,
o.card_number,
o.expiration,
o.ip_address,
o.ip_lookup,
o.order_status,
o.decline_reason,
o.blacklist,
o.fraud,
o.chargeback,
o.rma_number,
o.rma_reason,
o.transaction,
o.auth_number,
o.rebill_date,
o.rebill_disc,
o.expired,
o.lead_date,
o.lead_time,
o.sale_date,
o.sale_time,
o.void_date,
o.void_time,
c.campaign,
co.verbose,
g.gate_name,
ug.gate_name `gate_alias`,
s.ship_name,
s.ship_cost,
off.offr_id,
off.prod_id,
off.offer_name,
off.offer_cost,
off.recur_term,
off.trial_cost,
off.trial_term,
p.product_name,
p.product_cost,
p.product_size,
p.product_sku,
u2.user_first `affi_first`,
u2.user_last `affi_last`
from $this->MM_USER_ORDER `o`
left join $this->MM_COUNTRY as `co` on (o.country = co.abbr)
left join $this->MM_USER as `u2` on (o.affi_id = u2.old_affi_id or o.affi_id = u2.user_id)
left join $this->MM_USER_SHIPPING as `s` on (o.ship_id = s.ship_id)
left join $this->MM_USER_GATEWAY as `ug` on (o.plan_id = ug.plan_id)
left join $this->MM_GATEWAY as `g` on (ug.gate_id = g.gate_id),
$this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`,
$this->MM_USER_PRODUCT `p`, $this->MM_USER `u`
where o.ordr_id = $id
  and o.user_id = 12345
  and o.user_id = u.user_id
  and o.camp_id = c.camp_id
  and c.offr_id = off.offr_id
  and off.prod_id = p.prod_id
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0];
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
	}
	
	
	

	
	
	
	
	
	public function ViewNotes($id, $b) {
		
		$db = $b->getResource('db');
		
		$sql =
"
select
n.user_id,
n.note,
u.user_first,
u.user_last,
n.create_date,
n.create_time
from $this->MM_USER_ORDER_NOTE `n`, $this->MM_USER `u`
where n.ordr_id = $id
  and n.user_id = u.user_id
order by n.create_date desc, n.create_time desc 
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
order by c.camp_id desc
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return $e;
		}
		
		
		
	}
	
	
	
	
	
	public function GetAffiliates($b) {
	
		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
u.user_id,
u.user_last,
u.user_first,
u.old_affi_id
from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
where o.user_id = 12345
  and (o.affi_id = u.old_affi_id or o.affi_id = u.user_id)
order by u.user_last
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return $e;
		}

	}	
	
	
	
	
	
	
	
	public function GetGateways($b) {
	
		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
ug.plan_id,
ug.gate_name
from $this->MM_USER_ORDER `o`, $this->MM_USER_GATEWAY `ug`
where o.user_id = 12345
  and o.plan_id = ug.plan_id
order by ug.gate_id, ug.gate_name
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return $e;
		}
		
		
		
	}
		
	
	
	
	
	public function GetCountries($b) {
	
		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
c.abbr,
c.verbose
from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
where o.user_id = 12345
  and o.bill_country = c.abbr
order by c.verbose
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return $e;
		}

	}	

	
	
}


?>