<?php

class Product
{

	protected $MM_USER		   = 'mm_user';
	protected $MM_USER_PRODUCT = 'mm_user_product';

	
	public function __construct() { }
	
	
	public function DrawForm() {
		
    	$f = new Zend_Form;
		   
		$i1 = $f->createElement('text', 'product_name', array('maxlength' => 64, 'size' => 40));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 64))
           ->addFilter('StringTrim');
         		   
		$i2 = $f->createElement('textarea', 'product_desc', array('maxlength' => 255, 'rows' => 3, 'cols' => 35));
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 255))
           ->addFilter('StringTrim');
		   
		$i3 = $f->createElement('text', 'product_sku', array('maxlength' => 32, 'size' => 20));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 32))
           ->addFilter('StringTrim');
		   
		$i4 = $f->createElement('text', 'product_cost', array('maxlength' => 6, 'size' => 6));
		$i4->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 6))
           ->addFilter('StringTrim');
		   
		$i5 = $f->createElement('text', 'product_size', array('maxlength' => 3, 'size' => 6));
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 3))
           ->addFilter('StringTrim');

		$i6 = $f->createElement('checkbox', 'has_login', array('style' => 'margin-left:0;'));
		$i6->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag');
	
		// Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2)
		  ->addElement($i3)
		  ->addElement($i4)
		  ->addElement($i5)
		  ->addElement($i6);

		return $f;
		  
	}
	
	
	
	
	
	public function SaveForm($f, $b) {
		
		foreach ($f->getValues() as $key => $value) {
			if (eregi("sharing",$key)) continue;
			$data[$key] = $value;
		}
				
		$data['user_id'] = '12345';
		$data['created'] = date("Y-m-d");
		
		$db = $b->getResource('db');	    			
		$db->beginTransaction();
			
		try {
			$db->insert($this->MM_USER_PRODUCT, $data);
			$pid = $db->lastInsertId($this->MM_USER_PRODUCT, 'prod_id');
			$db->commit();
			$db->closeConnection();
			return $pid;
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
p.prod_id,
p.public,
p.private,
p.personal,
p.disable,
p.product_name,
p.product_cost,
p.product_size,
p.product_sku,
p.has_login,
p.created
from $this->MM_USER_PRODUCT `p`, $this->MM_USER `u`
where p.user_id = 12345
  and p.user_id = u.user_id
order by p.prod_id desc
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
	
	
	
	
	
		public function DisableProduct($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "prod_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_product', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	

	
	
	
	
	public function EnableProduct($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "prod_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_product', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	public function BuildFormCode($id) {
		
		$FORM_CODE =

"
<form method=\"POST\" action=\"login.php\">
<input type=\"hidden\" name=\"product\" value=\"$id\" />
<table><tr>
<td>Username</td><td><input type=\"text\" name=\"username\" /></td>
</tr><tr>
<td>Password</td><td><input type=\"password\" name=\"password\" /></td>
</tr><tr>
<td colspan=\"2\" align=\"right\"><a href=\"remind.php\">Forgot password?</a>
<input type=\"image\" src=\"images/login.png\" name=\"submit\" src=\"images/login.png\" alt=\"LOGIN\" /></td>
</tr></table>
</form>
";
		return $FORM_CODE;
		
	}
	
	
		
	
	public function BuildRemindFormCode($id) {
		
		$FORM_CODE =

"
<form method=\"POST\" action=\"remind.php\">
<input type=\"hidden\" name=\"product\" value=\"$id\" />
<table><tr>
<td>Email</td>
<td><input type=\"text\" name=\"email\" maxlength=\"64\" /></td>
</tr><tr>
<td colspan=\"2\" align=\"right\"><input type=\"image\" name=\"submit\" src=\"images/login.png\" alt=\"REMIND\" /></td>
</tr></table>
</form>
";
		return $FORM_CODE;
		
	}
	
	
	
	
}


?>