<?php

class Report
{

	protected $MM_COUNTRY			= 'mm_country';
	protected $MM_GATEWAY			= 'mm_gateway';
	protected $MM_USER				= 'mm_user';
	protected $MM_USER_OFFER		= 'mm_user_offer';
	protected $MM_USER_ORDER		= 'mm_user_order';
	protected $MM_USER_ORDER_ITEM	= 'mm_user_order_item';
	protected $MM_USER_PRODUCT		= 'mm_user_product';
	protected $MM_USER_GATEWAY		= 'mm_user_gateway';
	protected $MM_USER_SHIPPING		= 'mm_user_shipping';
	protected $MM_USER_CAMPAIGN		= 'mm_user_campaign';
	protected $CAMPAIGN2COUNTRY		= 'campaign2country';
	protected $CAMPAIGN2SHIPPING	= 'campaign2shipping';

	
	public function __construct() { }
	
	
	public function DrawForm() {
		
    	$f = new Zend_Form;

    	$report_options = array(
    	"ReportByProduct" => "Report By Product",
    	"ReportByOffer" => "Report By Offer",
    	"ReportByCampaign" => "Report By Campaign",
    	"ReportByAffiliate" => "Report By Affiliate",
    	"ReportByCountry" => "Report By Country",
    	"DeclineCodeReport" => "Decline Code Report",
    	"RebillReport" => "Rebill Report"
    	);
    	
		$i1 = $f->createElement('select', 'report_type', array('style' => 'margin:6px;width:94%;'));
		$i1->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($report_options);
        if (isset($_POST['report_type']))
           $i1->setValue($_POST['report_type']);

        $i2 = $f->createElement('text', 'start_date', array('class' => 'date_input', 'size' => '10', 'readonly' => true, 'style' => 'margin-left:40px'));
		$i2->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(10, 10));

		$i3 = $f->createElement('text', 'stop_date', array('class' => 'date_input', 'size' => '10', 'readonly' => true, 'style' => 'margin-left:40px'));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('StringLength', true, array(10, 10));

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
	
	
	
	
	
	
	
	
	
	public function ReportByProduct($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('m/d/Y'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('m/d/Y'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.prod_id,
ap.product_name,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select p.prod_id,
		p.product_name
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\")) and o.user_id = 12345 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id 
	 group by p.prod_id
) as `ap`
LEFT JOIN
(select p.prod_id,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" and (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'REFUND') and o.user_id = 12345 and o.rebill < 1 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id  
	 group by p.prod_id
) as `os` on (ap.prod_id = os.prod_id)
LEFT JOIN
(select p.prod_id,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.user_id = 12345 and o.retry < 1 and o.rebill < 1 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id  
	 group by p.prod_id
) as `ns` on (ap.prod_id = ns.prod_id)
LEFT JOIN
(select p.prod_id,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.user_id = 12345 and o.retry < 1 and o.rebill > 0 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id
	 group by p.prod_id
) as `re` on (ap.prod_id = re.prod_id)
LEFT JOIN
(select p.prod_id,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" and (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.user_id = 12345 and o.retry > 0 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id 
	 group by p.prod_id
) as `sa` on (ap.prod_id = sa.prod_id)
LEFT JOIN
(select p.prod_id,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\" and o.order_status = 'REFUND' and o.user_id = 12345 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id
 	 group by p.prod_id
) as `vo` on (ap.prod_id = vo.prod_id)
LEFT JOIN
(select p.prod_id,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" and (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.user_id = 12345 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id
 	 group by p.prod_id
) as `de` on (ap.prod_id = de.prod_id)
LEFT JOIN
(select p.prod_id,
		count(*) as `lead_count`
	 from $this->MM_USER_PRODUCT `p`, $this->MM_USER_OFFER `off`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_ORDER `o`
	 where o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\" and o.order_status = 'LEAD' and o.user_id = 12345 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and off.prod_id = p.prod_id
 	 group by p.prod_id
) as `le` on (ap.prod_id = le.prod_id)
order by ap.prod_id
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}

	}
	
	
	
	public function ReportByOffer($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.offr_id,
ap.offer_name,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select off.offr_id,
		off.offer_name
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where o.user_id = 12345 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by off.offr_id
) as `ap`
LEFT JOIN
(select off.offr_id,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'REFUND') and o.rebill < 1 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by off.offr_id
) as `os` on (ap.offr_id = os.offr_id)
LEFT JOIN
(select off.offr_id,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by off.offr_id
) as `ns` on (ap.offr_id = ns.offr_id)
LEFT JOIN
(select off.offr_id,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill > 0 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by off.offr_id
) as `re` on (ap.offr_id = re.offr_id)
LEFT JOIN
(select off.offr_id,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.retry > 0 and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by off.offr_id
) as `sa` on (ap.offr_id = sa.offr_id)
LEFT JOIN
(select off.offr_id,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where o.order_status = 'REFUND' and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
 	 group by off.offr_id
) as `vo` on (ap.offr_id = vo.offr_id)
LEFT JOIN
(select off.offr_id,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
 	 group by off.offr_id
) as `de` on (ap.offr_id = de.offr_id)
LEFT JOIN
(select off.offr_id,
		count(*) as `lead_count`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`, $this->MM_USER_OFFER `off`
	 where o.order_status = 'LEAD' and o.camp_id = c.camp_id and c.offr_id = off.offr_id and o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\"
 	 group by off.offr_id
) as `le` on (ap.offr_id = le.offr_id)
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
	

	public function ReportByCampaign($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.camp_id,
ap.campaign,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select c.camp_id,
		c.campaign
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where o.user_id = 12345 and o.camp_id = c.camp_id and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by c.camp_id
) as `ap`
LEFT JOIN
(select c.camp_id,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'REFUND') and o.rebill < 1 and o.camp_id = c.camp_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by c.camp_id
) as `os` on (ap.camp_id = os.camp_id)
LEFT JOIN
(select c.camp_id,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.camp_id = c.camp_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by c.camp_id
) as `ns` on (ap.camp_id = ns.camp_id)
LEFT JOIN
(select c.camp_id,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill > 0 and o.camp_id = c.camp_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by c.camp_id
) as `re` on (ap.camp_id = re.camp_id)
LEFT JOIN
(select c.camp_id,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.retry > 0 and o.camp_id = c.camp_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by c.camp_id
) as `sa` on (ap.camp_id = sa.camp_id)
LEFT JOIN
(select c.camp_id,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where o.order_status = 'REFUND' and o.camp_id = c.camp_id and o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"
 	 group by c.camp_id
) as `vo` on (ap.camp_id = vo.camp_id)
LEFT JOIN
(select c.camp_id,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.camp_id = c.camp_id and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
 	 group by c.camp_id
) as `de` on (ap.camp_id = de.camp_id)
LEFT JOIN
(select c.camp_id,
		count(*) as `lead_count`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER_CAMPAIGN `c`
	 where o.order_status = 'LEAD' and o.camp_id = c.camp_id and o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\"
 	 group by c.camp_id
) as `le` on (ap.camp_id = le.camp_id)
";
				
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();// var_dump($result);exit;
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection();
		return $e;
		}
		
	}
	
	public function ReportByAffiliate($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.user_id,
ap.business,
ap.user_first,
ap.user_last,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select u.user_id,
		u.old_affi_id,
		u.user_first,
		u.user_last,
		u.business
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where o.user_id = 12345 and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by u.user_id
) as `ap`
LEFT JOIN
(select u.user_id,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'REFUND') and o.rebill < 1 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by u.user_id
) as `os` on (ap.user_id = os.user_id or ap.old_affi_id = os.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by u.user_id
) as `ns` on (ap.user_id = ns.user_id or ap.old_affi_id = ns.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by u.user_id
) as `re` on (ap.user_id = re.user_id or ap.old_affi_id = re.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.retry > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by u.user_id
) as `sa` on (ap.user_id = sa.user_id or ap.old_affi_id = sa.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and o.order_status = 'REFUND' and o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"  
 	 group by u.user_id
) as `vo` on (ap.user_id = vo.user_id or ap.old_affi_id = sa.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
 	 group by u.user_id
) as `de` on (ap.user_id = de.user_id or ap.old_affi_id = de.user_id)
LEFT JOIN
(select u.user_id,
		count(*) as `lead_count`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and o.order_status = 'LEAD' and o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\"  
 	 group by u.user_id
) as `le` on (ap.user_id = le.user_id or ap.old_affi_id = le.user_id)
order by ap.business desc, ap.user_last
";
				
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
	}
	
	
	public function ReportByCountry($b) {
			
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
				
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.bill_country `country`,
ap.abbr,
ap.verbose,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select o.bill_country,
		c.abbr,
		c.verbose
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where o.user_id = 12345 and o.bill_country = c.abbr and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by o.bill_country
) as `ap`
LEFT JOIN
(select o.bill_country,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'REFUND') and o.rebill < 1 and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.bill_country
) as `os` on (ap.bill_country = os.bill_country)
LEFT JOIN
(select o.bill_country,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.bill_country
) as `ns` on (ap.bill_country = ns.bill_country)
LEFT JOIN
(select o.bill_country,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill > 0 and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by o.bill_country
) as `re` on (ap.bill_country = re.bill_country)
LEFT JOIN
(select o.bill_country,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.retry > 0 and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.bill_country
) as `sa` on (ap.bill_country = sa.bill_country)
LEFT JOIN
(select o.bill_country,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where o.order_status = 'REFUND' and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.bill_country
) as `vo` on (ap.bill_country = vo.bill_country)
LEFT JOIN
(select o.bill_country,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.bill_country = c.abbr and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\" 
	 group by o.bill_country
) as `de` on (ap.bill_country = de.bill_country)
LEFT JOIN
(select o.bill_country,
		count(*) as `lead_count`
	 from $this->MM_USER_ORDER `o`, $this->MM_COUNTRY `c`
	 where o.order_status = 'LEAD' and o.bill_country = c.abbr and o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\" 
	 group by o.bill_country
) as `le` on (ap.bill_country = le.bill_country)
order by le.lead_count desc, ns.new_sale_count desc
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();// var_dump($result);exit;
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
	}
	
	
	
	
	
	
	
	
	public function AffiliateReport($mm,$id,$b) {
		
		$db = $b->getResource('db');
		
		$start_date = $mm->report_start;
		$stop_date = $mm->report_stop;
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];
		
		$sql =
"
select
ap.subs_id,
os.original_sale_count,
os.original_sale_sales,
ns.new_sale_count,
ns.new_sale_sales,
re.rebill_count,
re.rebill_sales,
sa.saved_count,
sa.saved_sales,
vo.refund_count,
vo.refund_sales,
de.declined_count,
de.declined_sales,
le.lead_count
from
(select o.subs_id
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by o.subs_id
) as `ap`
LEFT JOIN
(select o.subs_id,
	 	count(*) as `original_sale_count`,
	 	SUM(o.total_sale) as `original_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.subs_id
) as `os` on (ap.subs_id = os.subs_id)
LEFT JOIN
(select o.subs_id,
	 	count(*) as `new_sale_count`,
	 	SUM(o.total_sale) as `new_sale_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill < 1 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.subs_id
) as `ns` on (ap.subs_id = ns.subs_id)
LEFT JOIN
(select o.subs_id,
	 	count(*) as `rebill_count`,
	 	SUM(o.total_sale) as `rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and o.retry < 1 and o.rebill > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.subs_id
) as `re` on (ap.subs_id = re.subs_id)
LEFT JOIN
(select o.subs_id,
	 	count(*) as `saved_count`,
	 	SUM(o.total_sale) as `saved_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING' or o.order_status = 'RETRY') and o.retry > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by o.subs_id
) as `sa` on (ap.subs_id = sa.subs_id)
LEFT JOIN
(select o.subs_id,
	 	count(*) as `refund_count`,
	 	SUM(o.product_sale) as `refund_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where  u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and o.order_status = 'REFUND' and o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"  
 	 group by o.subs_id
) as `vo` on (ap.subs_id = vo.subs_id)
LEFT JOIN
(select o.subs_id,
	 	count(*) as `declined_count`,
	 	SUM(o.product_sale) as `declined_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where  u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and (o.order_status = 'DECLINE' or o.order_status = 'ERROR') and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
 	 group by o.subs_id
) as `de` on (ap.subs_id = de.subs_id)
LEFT JOIN
(select o.subs_id,
		count(*) as `lead_count`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where u.user_id = $id and (u.old_affi_id = o.affi_id or u.user_id = o.affi_id) and o.order_status = 'LEAD' and o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\"  
 	 group by o.subs_id
) as `le` on (ap.subs_id = le.subs_id)
";

		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
	}
	
	
	
	
	
	
	
	public function DeclineCodeReport($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];

		$sql =
"
select
distinct
aa.user_id,
aa.business,
aa.user_first,
aa.user_last,
ab.decline_count,
ab.decline_reason,
ab.decline_total
from
(select count(*) as `decline_count`,
		o.affi_id,
		o.decline_reason,
		o2.decline_total
	from $this->MM_USER_ORDER `o`,
	(select count(*) as `decline_total` from $this->MM_USER_ORDER where order_status = 'DECLINE' and sale_date >= \"$start_date\" and sale_date <= \"$stop_date\") `o2` 
	where o.order_status = 'DECLINE'
	  and o.sale_date >= \"$start_date\"
	  and o.sale_date <= \"$stop_date\"
group by o.affi_id, o.decline_reason
order by o.affi_id, o2.decline_total desc
) as `ab`
LEFT JOIN
(select u.user_id,
		u.old_affi_id,
		u.user_first,
		u.user_last,
		u.business
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where o.user_id = 12345 and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
) as `aa` on (aa.old_affi_id = ab.affi_id or aa.user_id = ab.affi_id)
order by aa.business desc, aa.user_last, ab.decline_count desc
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();// var_dump($result);exit;
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
		
	}
	
	
	
	
	
	
	public function RebillReport($b) {
		
		$db = $b->getResource('db');
		
		$start_date = (isset($_POST['start_date']) && $_POST['start_date'] > 0 ? $_POST['start_date'] : date('Y-m-d'));
		$stop_date = (isset($_POST['stop_date']) && $_POST['stop_date'] > 0 ? $_POST['stop_date'] : date('Y-m-d'));
		
		$start_date = explode("/",$start_date);
		$stop_date = explode("/",$stop_date);

		$start_date = $start_date[2]."/".$start_date[0]."/".$start_date[1];
		$stop_date = $stop_date[2]."/".$stop_date[0]."/".$stop_date[1];

		$sql =
"
select
ap.user_id,
ap.business,
ap.user_first,
ap.user_last,
ns.total_rebill_count,
ns.total_rebill_sales,
re.declined_rebill_count,
re.declined_rebill_sales
from
(select u.user_id,
		u.old_affi_id,
		u.user_first,
		u.user_last,
		u.business
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where o.user_id = 12345 and (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and ((o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\") or (o.lead_date >= \"$start_date\" and o.lead_date <= \"$stop_date\") or (o.void_date >= \"$start_date\" and o.void_date <= \"$stop_date\"))
	 group by u.user_id
) as `ap`
LEFT JOIN
(select u.user_id,
	 	count(*) as `total_rebill_count`,
	 	SUM(o.total_sale) as `total_rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and (o.order_status = 'SALE' or o.order_status = 'PENDING') and rebill > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"
	 group by u.user_id
) as `ns` on (ap.user_id = ns.user_id or ap.old_affi_id = ns.user_id)
LEFT JOIN
(select u.user_id,
	 	count(*) as `declined_rebill_count`,
	 	SUM(o.total_sale) as `declined_rebill_sales`
	 from $this->MM_USER_ORDER `o`, $this->MM_USER `u`
	 where (o.affi_id = u.user_id or o.affi_id = u.old_affi_id) and o.order_status = 'DECLINE' and o.rebill > 0 and o.sale_date >= \"$start_date\" and o.sale_date <= \"$stop_date\"  
	 group by u.user_id
) as `re` on (ap.user_id = re.user_id or ap.old_affi_id = re.user_id)
order by ap.business desc, ap.user_last
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();// var_dump($result);exit;
		return $result;
		} catch ( Exception $e ) {
		$db->closeConnection(); echo $e;exit;
		return $e;
		}
		
	}
	
	
	
	
	
	
}




?>