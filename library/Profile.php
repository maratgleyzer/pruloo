<?php

class Profile
{

	protected $MM_USER			= 'mm_user';
	protected $MM_GATEWAY		= 'mm_gateway';	
	protected $MM_USER_CHANNEL	= 'mm_user_channel';
	protected $MM_USER_GATEWAY	= 'mm_user_gateway';
	protected $MM_USER_SHIPPING	= 'mm_user_shipping';

	
	public function __construct() { }
	
	

	public function DrawGatewayForm($b) {
		
    	$f = new Zend_Form;

    	$db = $b->getResource('db');

    	$sql = "select gate_id, gate_name from mm_gateway where disable = 0";
    	
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		if (count($result) == 0) $options[0] = 'No gateways found.';
		else $options[0] = ' -- select a payment gateway you are authorized to use -- ';
		foreach ($result as $gateway)
		$options[$gateway['gate_id']] = $gateway['gate_name'];
		} catch ( Exception $e ) {
		$db->closeConnection();
		$options[0] = ' -- an error occured -- ';
		}
    	
		$i1 = $f->createElement('text', 'gate_name', array('maxlength' => 32, 'size' => 40));
		$i1->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 32))
           ->addFilter('StringTrim');
		
		$i2 = $f->createElement('select', 'gate_id', array('style' => 'width:100%;'));
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addMultiOptions($options);
       		   
		$i3 = $f->createElement('text', 'gate_acct', array('maxlength' => 32, 'size' => 20));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');
       		   
		$i4 = $f->createElement('text', 'gate_user', array('maxlength' => 32, 'size' => 20));
		$i4->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');
       		   
		$i5 = $f->createElement('text', 'gate_pass', array('maxlength' => 32, 'size' => 20));
		$i5->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');
       		   
		$i6 = $f->createElement('text', 'conf_pass', array('maxlength' => 32, 'size' => 20));
		$i6->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');

		$i7 = $f->createElement('text', 'gate_plan', array('maxlength' => 16, 'size' => 10));
		$i7->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 16))
           ->addFilter('StringTrim');

		$i8 = $f->createElement('text', 'threshold', array('maxlength' => 8, 'size' => 10));
		$i8->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 16))
           ->addFilter('StringTrim');
           
		$i9 = $f->createElement('checkbox', 'checking', array('style' => 'margin-left:0;'));
		$i9->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag');
           
		$i10 = $f->createElement('checkbox', 'weight', array('style' => 'margin-left:0;'));
		$i10->setRequired(false)
		    ->removeDecorator('label')
            ->removeDecorator('HtmlTag');

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

		return $f;
		  
	}
	
	
	
	

	public function DrawShippingForm() {
		
    	$f = new Zend_Form;

    	$i1 = $f->createElement('text', 'ship_name', array('maxlength' => 48, 'size' => 40));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 48))
           ->addFilter('StringTrim');
         		   
		$i2 = $f->createElement('text', 'ship_cost', array('maxlength' => 6, 'size' => 6));
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(1, 6))
           ->addFilter('StringTrim');

        // Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2);

		return $f;
		  
	}
	

	
	
	
	public function SaveGatewayForm($f, $b) {
	
		foreach ($f->getValues() as $key => $value) {
			$data[$key] = $value;
		}

		if ($data['gate_id'] == '0')
			return "gate error";
		
		if ($data['gate_pass'] != $data['conf_pass'])
			return "conf error";
			
		unset($data['conf_pass']);
				
		$data['user_id'] = '12345';
		$data['created'] = date("Y-m-d");

		$db = $b->getResource('db');			
		$db->beginTransaction();
			
		try {
			$db->insert($this->MM_USER_GATEWAY, $data);
			$pid = $db->lastInsertId($this->MM_USER_GATEWAY, 'plan_id');
			$db->commit(); $db->closeConnection();
			return $pid;
			} catch ( Exception $e ) {
			$db->rollback();
			$db->closeConnection();
			return false;
			}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function EditGatewayForm($f, $b) {
	
		foreach ($f->getValues() as $key => $value) {
			$data[$key] = $value;
		}

		$id = $_POST['id'];
		
		if ($data['gate_id'] == '0')
			return "gate error";
		
		if ($data['gate_pass'] != $data['conf_pass'])
			return "conf error";
			
		unset($data['conf_pass']);
				
		$data['user_id'] = '12345';
		$data['changed'] = date("Y-m-d");

		$db = $b->getResource('db');		
		$where[] = "plan_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_gateway', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	public function SaveShippingForm($f, $b) {

		foreach ($f->getValues() as $key => $value) {
			$data[$key] = $value;
		}
				
		$data['user_id'] = '12345';

		$db = $b->getResource('db');
		$db->beginTransaction();
			
		try {
			$db->insert($this->MM_USER_SHIPPING, $data);
			$sid = $db->lastInsertId($this->MM_USER_SHIPPING, 'ship_id');
			$db->commit(); $db->closeConnection();
			return $sid;
			} catch ( Exception $e ) {
			$db->rollback();
			$db->closeConnection();
			return false;
			}
	
	}
	
	
	
	
	public function ListGatewayRecords($b) {
		
		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
g.gate_id,
g.gate_name,
ug.plan_id,
ug.gate_name `alias`,
ug.gate_user,
ug.gate_plan,
ug.purchases,
ug.threshold,
ug.checking,
ug.disable,
ug.weight
from $this->MM_USER_GATEWAY `ug`, $this->MM_GATEWAY `g`, $this->MM_USER `u`
where ug.user_id = 12345
  and ug.gate_id = g.gate_id
  and g.disable = 0
order by ug.plan_id desc
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
	
	
	
	
	
	
	public function ListShippingRecords($b) {
		
		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
s.ship_id,
s.ship_name,
s.ship_cost,
s.disable
from $this->MM_USER_SHIPPING `s`, $this->MM_USER `u`
where s.user_id = 12345
  and s.user_id = u.user_id
order by s.ship_name
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
	
	
	
	
	
	
	
	
	public function DisableGateway($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "plan_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_gateway', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	
	
	
	
	
	public function EnableGateway($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "plan_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_gateway', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	
	
	
	
	public function DisableShipping($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "ship_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_shipping', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	

	
	
	
	
	public function EnableShipping($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "ship_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_shipping', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	
	
	
	public function GetGateway($id,$b) {
		
		$db = $b->getResource('db');

		$sql =
"
select
g.gate_id,
ug.gate_name `alias`,
ug.gate_acct,
ug.gate_user,
ug.gate_pass,
ug.gate_plan,
ug.threshold,
ug.checking,
ug.weight
from $this->MM_USER_GATEWAY `ug`, $this->MM_GATEWAY `g`, $this->MM_USER `u`
where ug.plan_id = $id
  and ug.user_id = 12345
  and ug.gate_id = g.gate_id
  and g.disable = 0
limit 1
";
		
		try {
		$result = $db->fetchAll($sql);
		$db->closeConnection();
		return $result[0];
		} catch ( Exception $e ) {
		$db->closeConnection();
		return false;
		}
		
	}	

	
	

	
	
	
	
	
	
	
	
	
	
	
	
}


?>