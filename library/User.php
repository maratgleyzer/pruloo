<?php

class User
{

	protected $MM_COUNTRY			= 'mm_country';
	protected $MM_USER				= 'mm_user';


	public function DrawForm() {
		
    	$f = new Zend_Form;

    	$user_options = array(
    	"" => "-- select --",
    	"administrator" => "Administrator",
    	"affiliate" => "Affiliate",
    	"employee" => "Employee"
    	);
    	
		$i1 = $f->createElement('select', 'user_type');
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($user_options);
        if (isset($_POST['user_type']))
           $i1->setValue($_POST['user_type']);

		$i2 = $f->createElement('text', 'business', array('maxlength' => 32, 'size' => 30));
		$i2->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 32))
           ->addFilter('StringTrim');

        $i3 = $f->createElement('text', 'user_first', array('maxlength' => 24, 'size' => 20));
		$i3->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 24))
           ->addFilter('StringTrim');

        $i4 = $f->createElement('text', 'user_last', array('maxlength' => 24, 'size' => 20));
		$i4->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 24))
           ->addFilter('StringTrim');

        $i5 = $f->createElement('text', 'user_address', array('maxlength' => 64, 'size' => 30));
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 64))
           ->addFilter('StringTrim');
           
        $i6 = $f->createElement('text', 'user_zip', array('maxlength' => 8, 'size' => 10));
		$i6->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 8))
           ->addFilter('StringTrim');

        $i7 = $f->createElement('text', 'user_phone', array('maxlength' => 24, 'size' => 15));
		$i7->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 24))
           ->addFilter('StringTrim');

        $i8 = $f->createElement('text', 'user_email', array('maxlength' => 64, 'size' => 30));
		$i8->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(8, 64))
           ->addFilter('StringTrim');
           
        $i9 = $f->createElement('text', 'username', array('maxlength' => 64, 'size' => 20));
		$i9->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 64))
           ->addFilter('StringTrim');
           
        $i10 = $f->createElement('text', 'password', array('maxlength' => 32, 'size' => 20));
		$i10->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');
           
        $i11 = $f->createElement('text', 'confirm', array('maxlength' => 32, 'size' => 20));
		$i11->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 32))
           ->addFilter('StringTrim');
           
        $i12 = $f->createElement('text', 'messenger', array('maxlength' => 64, 'size' => 30));
		$i12->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 64))
           ->addFilter('StringTrim');
           
        $i13 = $f->createElement('textarea', 'wire_info', array('maxlength' => 255, 'cols' => 50, 'rows' => "3"));
		$i13->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 255))
           ->addFilter('StringTrim');
           
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
		  ->addElement($i10)
		  ->addElement($i11)
		  ->addElement($i12)
		  ->addElement($i13);

		return $f;
		  
	}
	
	
	
	
	
	
	public function DrawFindForm() {
		
    	$f = new Zend_Form;

    	$user_options = array(
    	"" => "-- select --",
    	"administrator" => "Administrator",
    	"affiliate" => "Affiliate",
    	"employee" => "Employee"
    	);
    	
		$i1 = $f->createElement('select', 'find_user_type', array('style' => 'width:92%;margin:8px 6px;'));
		$i1->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($user_options);
        if (isset($_POST['user_type']))
           $i1->setValue($_POST['user_type']);

        $i2 = $f->createElement('text', 'find_user_last', array('maxlength' => 24, 'style' => 'width:88%;margin:10px 6px;'));
		$i2->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 24))
           ->addFilter('StringTrim');

        $i3 = $f->createElement('text', 'find_user_first', array('maxlength' => 24, 'style' => 'width:88%;margin:10px 6px;'));
		$i3->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 24))
           ->addFilter('StringTrim');

        $i4 = $f->createElement('text', 'find_user_email', array('maxlength' => 64, 'style' => 'width:88%;margin:10px 6px;'));
		$i4->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 64))
           ->addFilter('StringTrim');
           
        $i5 = $f->createElement('text', 'find_user_phone', array('maxlength' => 8, 'style' => 'width:88%;margin:10px 6px;'));
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(0, 16))
           ->addFilter('StringTrim');
           
		// Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2)
		  ->addElement($i3)
		  ->addElement($i4)
		  ->addElement($i5);

		$f->isValid($_POST);
		
		return $f;
		  
	}
	
	
	
	
	
	
	
	
	
	public function SaveForm($f, $b)
	{
		
		foreach ($f->getValues() as $key => $value)
			if ($data[$key] = '') $data[$key] = " ";
			else $data[$key] = $value;

		if ($data['password'] != $data['confirm'])
			return "conf error";
			
		$data['referer'] = '12345';
		$data['created'] = date("Y-m-d");
	
		$data[$data['user_type']] = "1";
		unset($data['user_type']);
		unset($data['confirm']);

		//$data['password'] = crypt($data['password'],'mm');
		
		$db = $b->getResource('db');
	    			
		$db->beginTransaction();
			
		try {
		$db->insert($this->MM_USER, $data);
		$uid = $db->lastInsertId($this->MM_USER, 'user_id');
		$db->commit();
		$db->closeConnection();
		return $uid;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}
		
	}
	
	
	
	
	
	
	
	
	
	public function ListRecords($f, $b)
	{
		
		$db = $b->getResource('db');
		
		$sql =
"
select
u.user_id,
u.administrator,
u.affiliate,
u.employee,
u.old_affi_id,
u.old_subs_id,
u.business,
u.user_first,
u.user_last,
u.user_address,
u.user_zip,
u.user_city,
u.user_state,
u.user_country,
u.user_phone,
u.user_email,
u.messenger,
u.username,
u.disable,
u.log_date,
u.log_time,
u.out_date,
u.out_time,
u.changed,
u.created
from $this->MM_USER `u`
where u.referer = 12345
".
($f->getValue('find_user_type') ? " and u.".$f->getValue('find_user_type')." > 0" : "").
($f->getValue('find_user_last') ? " and u.user_last like \"".$f->getValue('find_user_last')."%\"" : "").
($f->getValue('find_user_first') ? " and u.user_first like \"".$f->getValue('find_user_first')."%\"" : "").
($f->getValue('find_user_email') ? " and u.user_email like \"".$f->getValue('find_user_email')."%\"" : "").
($f->getValue('find_user_phone') ? " and u.user_phone like \"".$f->getValue('find_user_phone')."%\"" : "").
"
order by u.user_id desc
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
	
	
	
	
	
	
		public function DisableUser($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "user_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	

	
	
	
	
	public function EnableUser($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "user_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user', $data, $where);
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