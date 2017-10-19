<?php

class LoginController extends Zend_Controller_Action
{
	
    public function init()
    {

    	
    	
    }

    public function indexAction()
    {
    	
	   	$mm = new Zend_Session_Namespace('moneymachine');
    	
    	unset($this->mm->user_id);
    	unset($this->mm->administrator);
    	unset($this->mm->affiliate);
    	unset($this->mm->employee);
    	unset($this->mm->username);
    	unset($this->mm->user_email);
    	unset($this->mm->user_first);
    	unset($this->mm->user_last);
    	
    	$this->form = $this->DrawForm();

        if ($this->_request->isPost())
        	if ($this->form->isValid($_POST)) {
        		$this->Login();
        	}

        $this->_helper->layout->setLayout('login');

    	$inputs[1] = $this->form->getElement('username');
        $inputs[2] = $this->form->getElement('password');
        
        $this->view->inputs = $inputs;
        
    }
    
    
    
    
    private function Login() {
    	
    	$username = $this->form->getValue('username');
    	$password = $this->form->getValue('password');

    	//$password = crypt($password,"mm");

    	$b = $this->getInvokeArg('bootstrap');
		$db = $b->getResource('db');
    	
    	$sql = "select user_id from mm_user where username = \"$username\" limit 1";
    	$result = $db->fetchAll($sql);
        $db->closeConnection();
    	
    	if (!$result) { $this->view->login_error = "An error has occured. Please try logging in again."; return false; }
    	if (count($result) == 0) { $this->view->login_error = "You have entered an invalid 'Username'."; return false; }
    	
		$db = $b->getResource('db');
    	$sql = "select user_id,administrator,affiliate,employee,user_email,user_first,user_last,username,password,disable from mm_user where username = \"$username\" and password = \"$password\" limit 1";
        $result = $db->fetchAll($sql);        
        $db->closeConnection();
        
    	if (!$result) { $this->view->login_error = "An error has occured. Please try logging in again."; return false; }
    	if (count($result) == 0) { $this->view->login_error = "You have entered an invalid 'Password'."; return false; }
        if ($result[0]['disable'] > 0) { $this->view->login_error = "Your login has been disabled. You are no longer able to log into the system. To dispute deactivation, please contact support@proloerinc.com."; return false; }
        	   	
		$mm = new Zend_Session_Namespace('moneymachine');

		$mm->user_id 		= $result[0]['user_id'];
		$mm->administrator 	= $result[0]['administrator'];
		$mm->affiliate 		= $result[0]['affiliate'];
		$mm->employee	 	= $result[0]['employee'];
		$mm->username 		= $result[0]['username'];
		$mm->user_email 	= $result[0]['user_email'];
		$mm->user_first 	= $result[0]['user_first'];
		$mm->user_last 		= $result[0]['user_last'];

		$this->_redirect('/console');
		exit;
		
    } 
    
    

    
    public function consumerAction() {

    	$username = $this->_request->getParam('username');
    	$password = $this->_request->getParam('password');
    	$product = $this->_request->getParam('product');

    	//$password = crypt($password,"mm");

    	$b = $this->getInvokeArg('bootstrap');
		$db = $b->getResource('db');

    	$sql =
"
select
ordr_id
from mm_user_order `o`, mm_user_campaign `c`, mm_user_offer `of`, mm_user_product `p`
where o.username = \"$username\"
  and o.camp_id = c.camp_id
  and (c.offr_id = of.offr_id
    or o.offr_id = of.offr_id)
  and of.prod_id = p.prod_id
  and p.prod_id = $product
  and p.disable = 0
  and of.disable = 0
  and o.disable = 0
limit 1
";

    	$result = $db->fetchAll($sql);
        $db->closeConnection();

        if (!is_array($result)) { echo "An error has occured. Please try logging in again."; exit; }
    	if (count($result) == 0) { echo "You have entered an invalid 'Username'."; exit; }

		$db = $b->getResource('db');

    	$sql =
"
select
ordr_id,
bill_email
from mm_user_order `o`, mm_user_campaign `c`, mm_user_offer `of`, mm_user_product `p`
where o.username = \"$username\"
  and o.password = \"$password\"
  and o.camp_id = c.camp_id
  and (c.offr_id = of.offr_id
    or o.offr_id = of.offr_id)
  and of.prod_id = p.prod_id
  and p.prod_id = $product
  and p.disable = 0
  and of.disable = 0
  and o.disable = 0
limit 1
";

        $result = $db->fetchAll($sql);        
        $db->closeConnection();

    	if (!is_array($result)) { echo "An error has occured. Please try logging in again."; exit; }
    	if (count($result) == 0) { echo "You have entered an invalid 'Password'."; exit; }

		echo "SUCCESS,".$result[0]['ordr_id'].",".$result[0]['bill_email']; exit;

    } 
    
    
    
    
    
    private function DrawForm() {
    	
    	$f = new Zend_Form;
    	
		$i1 = $f->createElement('text', 'username', array('maxlength' => 64, 'size' => 20));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(4, 64))
           ->addFilter('StringTrim');
		
		$i2 = $f->createElement('password', 'password', array('maxlength' => 32, 'size' => 20));
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(4, 24))
           ->addFilter('StringTrim');
           
        // Add elements to form:
		$f->addElement($i1)
		  ->addElement($i2);

		return $f;

    }
    
    
}
    
?>