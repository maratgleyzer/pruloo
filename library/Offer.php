<?php

class Offer
{

	protected $MM_USER			= 'mm_user';
	protected $MM_USER_OFFER	= 'mm_user_offer';
	protected $MM_USER_PRODUCT	= 'mm_user_product';

	
	public function __construct() { }
	
	
	public function DrawForm($b) {
		
    	$f = new Zend_Form;

    	$i1 = $f->createElement('text', 'offer_name', array('maxlength' => 64, 'size' => 40));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 64))
           ->addFilter('StringTrim');
         		   
        $options = array('0' => '-- select --');
        $products = $this->GetProducts($b);
        
        if (is_array($products))
        	if (count($products) > 0)        
        		foreach ($products as $product)
        			$options[$product['prod_id']] = "$product[product_name] ($product[product_cost])".($product['product_size'] > 0 ? " $product[product_size] lb/ea." : "");
			else $options['0'] = "No products found.";
       	else $options['0'] = "An error occured.";
       	
		$i2 = $f->createElement('select', 'prod_id');
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);
           
        $i3 = $f->createElement('text', 'offer_cost', array('maxlength' => 6, 'size' => 6));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0,6))
           ->addFilter('StringTrim');
		   
		$i4 = $f->createElement('text', 'recur_term', array('maxlength' => 2, 'size' => 4));
		$i4->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 2))
           ->addFilter('StringTrim');
		   
		$i5 = $f->createElement('text', 'trial_cost', array('maxlength' => 6, 'size' => 6));
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 6))
           ->addFilter('StringTrim');
		   
		$i6 = $f->createElement('text', 'trial_term', array('maxlength' => 2, 'size' => 4));
		$i6->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 2))
           ->addFilter('StringTrim');
 		   
		$i7 = $f->createElement('text', 'impulse_product_id', array('maxlength' => 64, 'size' => 30));
		$i7->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 64))
           ->addFilter('StringTrim');
                   
		// Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2)
		  ->addElement($i3)
		  ->addElement($i4)
		  ->addElement($i5)
		  ->addElement($i6)
		  ->addElement($i7);

		return $f;
		  
	}
	
	
	
	
	
	public function SaveForm($f, $b) {

		foreach ($f->getValues() as $key => $value) {
			$data[$key] = $value;
		}
				
		$data['user_id'] = '12345';
		$data['created'] = date("Y-m-d");
		
		$db = $b->getResource('db');	    			
		$db->beginTransaction();
			
		try {
		$db->insert($this->MM_USER_OFFER, $data);
		$oid = $db->lastInsertId($this->MM_USER_OFFER, 'offr_id');
		$db->commit(); $db->closeConnection();
		return $oid;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}
	
	}
	
	
	
	
	
	public function ListRecords($b) {
		
		$db = $b->getResource('db');
		
		$sql =
"
select
o.offr_id,
o.prod_id,
o.public,
o.private,
o.personal,
o.disable,
o.offer_name,
o.offer_cost,
o.recur_term,
o.trial_cost,
o.trial_term,
o.created,
p.product_name,
p.product_cost
from $this->MM_USER_OFFER `o`, $this->MM_USER_PRODUCT `p`, $this->MM_USER `u`
where o.user_id = 12345
  and o.prod_id = p.prod_id
  and o.user_id = u.user_id
order by o.offr_id desc
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
	
	
	
	
	
	
	public function GetProducts($b) {

		$db = $b->getResource('db');
		
		$sql =
"
select
p.prod_id,
p.product_name,
p.product_cost,
p.product_size
from $this->MM_USER_PRODUCT `p`, $this->MM_USER `u`
where p.user_id = 12345
  and p.user_id = u.user_id
order by p.product_name
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
	
	
	
	
	
	
	
	
		
	
	public function DisableOffer($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "offr_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_offer', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	

	
	
	
	
	public function EnableOffer($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "offr_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_offer', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	
	
	
	
}


?>