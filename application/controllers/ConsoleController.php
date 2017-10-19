<?php

class ConsoleController extends Zend_Controller_Action
{

    public function init()
    {

    	$this->mm = new Zend_Session_Namespace('moneymachine');

    	if (!$this->mm->user_id) $this->_redirect('/login');
    	if (!$this->mm->username) $this->_redirect('/login');

    	if ($this->mm->employee > 0) $this->_helper->layout->setLayout('telemarketer');

    }

    
    public function indexAction()
    {

    	require_once('Dashboard.php');
        $dashboard = new Dashboard();
                
        if ($this->_request->getParam('start_date'))
        	$this->mm->report_start = $_POST['start_date'];
        if ($this->_request->getParam('stop_date'))
        	$this->mm->report_stop = $_POST['stop_date'];
        
        $this->form = $dashboard->DrawForm($this->getInvokeArg('bootstrap'));
    	
    	$this->view->total_new_orders = $dashboard->TotalNewOrders($this->getInvokeArg('bootstrap'));
    	$this->view->new_order_sales = $dashboard->NewOrderSales($this->getInvokeArg('bootstrap'));
    	$this->view->total_current_rebills = $dashboard->TotalCurrentRebills($this->getInvokeArg('bootstrap'));
    	$this->view->current_rebill_sales = $dashboard->CurrentRebillSales($this->getInvokeArg('bootstrap'));
    	$this->view->tomorrows_rebills = $dashboard->TomorrowsRebills($this->getInvokeArg('bootstrap'));
    	$this->view->total_future_rebills = $dashboard->TotalFutureRebills($this->getInvokeArg('bootstrap'));
    	$this->view->total_declined_orders = $dashboard->TotalDeclinedOrders($this->getInvokeArg('bootstrap'));
    	$this->view->declined_new_orders = $dashboard->DeclinedNewOrders($this->getInvokeArg('bootstrap'));
    	$this->view->declined_rebills = $dashboard->DeclinedRebills($this->getInvokeArg('bootstrap'));
    	$this->view->total_voids_refunds = $dashboard->TotalVoidsRefunds($this->getInvokeArg('bootstrap'));
    	$this->view->voids_refunds_value = $dashboard->VoidsRefundsValue($this->getInvokeArg('bootstrap'));
    	$this->view->new_order_refunds = $dashboard->NewOrderRefunds($this->getInvokeArg('bootstrap'));
		$this->view->rebill_refunds = $dashboard->RebillRefunds($this->getInvokeArg('bootstrap'));
		$this->view->total_archive_orders = $dashboard->TotalArchiveOrders($this->getInvokeArg('bootstrap'));
		$this->view->active_prospects = $dashboard->ActiveProspects($this->getInvokeArg('bootstrap'));
		$this->view->active_customers = $dashboard->ActiveCustomers($this->getInvokeArg('bootstrap'));
		$this->view->blacklisted_customers = $dashboard->BlacklistedCustomers($this->getInvokeArg('bootstrap'));
		$this->view->active_affiliates = $dashboard->ActiveAffiliates($this->getInvokeArg('bootstrap'));
		$this->view->pending_affiliates = $dashboard->PendingAffiliates($this->getInvokeArg('bootstrap'));
		$this->view->blacklisted_affiliates = $dashboard->BlacklistedAffiliates($this->getInvokeArg('bootstrap'));
		$this->view->unused_capacity = $dashboard->UnusedCapacity($this->getInvokeArg('bootstrap'));
		$this->view->purchases_total = $dashboard->PurchasesTotal($this->getInvokeArg('bootstrap'));
		$this->view->threshold_total = $dashboard->ThresholdTotal($this->getInvokeArg('bootstrap'));

        $inputs[1] = $this->form->getElement('start_date');
        $inputs[2] = $this->form->getElement('stop_date');
        $inputs[3] = $this->form->getElement('campaign');
       
        $this->view->inputs = $inputs;
    	

    }
    
    
    

    public function affiliatesAction()
    {
    	
        require_once('Affiliates.php');
        $affiliates = new Affiliates();
        
    }
    
    

    public function ordersAction()
    {
    	
        require_once('Orders.php');
        $orders = new Orders();
        
    }

    public function orders2Action()
    {
    	
        require_once('Orders2.php');
        $orders = new Orders();
        
    }
    
    public function orderAction()
    {
    	
    	require_once('Order.php');    	
        $order = new Order();

		if ($this->_request->getParam('view')) {
			$this->view->order = $order->ViewOrder(
			$this->_request->getParam('view'), $this->getInvokeArg('bootstrap'));
			echo $this->view->render('console/order/view.phtml');
  			exit;
		}

		if ($this->view->ordr_id = $this->_request->getParam('notate')) {
			$this->view->notes = $order->ViewNotes(
			$this->_request->getParam('notate'), $this->getInvokeArg('bootstrap'));
			$this->note_form = $order->DrawNoteForm();
			$inputs[1] = $this->note_form->getElement('note');
			$this->view->note_input = $inputs;
			echo $this->view->render('console/order/note.phtml');
  			exit;
		}

        if ($this->view->ordr_id = $this->_request->getParam('save')) {
        	$this->note_form = $order->DrawNoteForm();
        	if ($this->note_form->isValid($_POST)) {
          		$result = $order->SaveNoteForm($this->_request->getParams(), $this->getInvokeArg('bootstrap'));
        		if ($result) $this->view->save_success = "Your order note has been saved successfully.";
				else $this->view->save_error = "You are attempting to save a duplicate order note.";
        	}
        	else {
        		$this->view->save_error = 1;
				$this->view->notes = $order->ViewNotes(
				$this->_request->getParam('ordr_id'), $this->getInvokeArg('bootstrap'));
				$inputs[1] = $this->note_form->getElement('note');
				$this->view->note_input = $inputs;
				$this->view->note_form = $this->view->render('console/order/note.phtml');
        	}
        }

        $this->form = $order->DrawFindForm($this->_request->getParams(), $this->getInvokeArg('bootstrap'));

        $inputs[1] = $this->form->getElement('campaign');
        $inputs[2] = $this->form->getElement('bill_last');
        $inputs[3] = $this->form->getElement('bill_first');
        $inputs[4] = $this->form->getElement('bill_email');
        $inputs[5] = $this->form->getElement('ordr_id');
        $inputs[6] = $this->form->getElement('order_status');
        $inputs[7] = $this->form->getElement('card_number');
        $inputs[8] = $this->form->getElement('affi_id');
        $inputs[9] = $this->form->getElement('plan_id');
        $inputs[10] = $this->form->getElement('bill_country');
       
        $mm = new Zend_Session_Namespace('moneymachine');
        if ($this->_request->getParam('search') ||
        	$this->_request->getParam('first') ||
        	$this->_request->getParam('prev') ||
        	$this->_request->getParam('next') ||
        	$this->_request->getParam('last')) {
			foreach ($this->_request->getParams() as $key => $value)
				$mm->search[$key] = $value;
        }

        //paging display
        include("Paging.php");
        $paging = new Paging();
        $mm->search = $paging->CalcPageParams($mm);
        $records = $order->ListRecords($mm, $this->getInvokeArg('bootstrap'));
        if (!is_array($records)) { $this->view->list_error = "An error has occured and the record list cannot be displayed."; }
        elseif (count($records) == 0) { $this->view->list_error = "No records found."; $mm->search['total_records'] = 0; }
        else { $mm->search['total_records'] = $records[0]['total_records'];
        $mm->search['limit_stop'] = $mm->search['limit_start'] + count($records);
        $this->view->total_records = $mm->search['total_records'];
        $this->view->limit_start = $mm->search['limit_start'];
        $this->view->limit_stop = $mm->search['limit_stop'];
        $this->view->paging_options = $paging->DrawPageOptions($mm);
        $this->view->page_display_options = $paging->DrawDisplayOptions($mm);
        $this->view->paging = $this->view->render('console/paging.phtml');
        }
	    //--> end paging

        $this->view->records = $records;
        $this->view->inputs = $inputs;
        
    }

    public function productAction()
    {
    	
    	require_once('Product.php');    	
        $product = new Product();
    
		if ($this->_request->getParam('form')) {
			$this->view->login_code = $product->BuildFormCode($this->_request->getParam('form'));
			$this->view->escaped_login_code = htmlspecialchars($this->view->login_code);
			$this->view->remind_code = $product->BuildRemindFormCode($this->_request->getParam('form'));
			$this->view->escaped_remind_code = htmlspecialchars($this->view->remind_code);
			$this->view->product_id = $this->_request->getParam('form');
			$this->_helper->viewRenderer->setRender('product/form'); 
			return;
		}
		
        $this->form = $product->DrawForm();

        if ($this->_request->getParam('save'))
        	if ($this->form->isValid($_POST)) {
        		$result = $product->SaveForm($this->form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your product has been saved successfully.";
				else $this->view->save_error = "You are attempting to save a duplicate product record.";
				$this->view->save_id = $result;
        	}
        	else { $this->view->save_error = 1; }
        	
        if ($prod_id = $this->_request->getParam('disable')) {
       		$result = $product->DisableProduct($prod_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Product ID #$prod_id has been disabled.";
			else $this->view->save_success = "An error has occured and the product cannot be disabled at this time.";
       	}
    
        if ($prod_id = $this->_request->getParam('enable')) {
       		$result = $product->EnableProduct($prod_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Product ID #$prod_id has been enabled.";
			else $this->view->save_success = "An error has occured and the product cannot be enabled at this time.";
       	}

        $inputs[1] = $this->form->getElement('product_name');
        $inputs[2] = $this->form->getElement('product_desc');
        $inputs[3] = $this->form->getElement('product_sku');
        $inputs[4] = $this->form->getElement('product_cost');
        $inputs[5] = $this->form->getElement('product_size');
        $inputs[6] = $this->form->getElement('has_login');
       
        $records = $product->ListRecords($this->getInvokeArg('bootstrap'));

        if (!is_array($records)) $this->view->list_error = "An error has occured and the record list cannot be displayed.";
        elseif (count($records) == 0) $this->view->list_error = "No records found.";
        
        $this->view->records = $records;
        $this->view->inputs = $inputs;
        
    }

    public function offerAction()
    {
    	
    	require_once('Offer.php');    	
        $offer = new Offer();
        
        $this->form = $offer->DrawForm($this->getInvokeArg('bootstrap'));

        if ($this->_request->getParam('save'))
        	if ($this->form->isValid($_POST)) {
        		$result = $offer->SaveForm($this->form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your offer has been saved successfully.";
				else $this->view->save_error = "You are attempting to save a duplicate offer record.";
				$this->view->save_id = $result;
        	}
        	else { $this->view->save_error = 1; }
        	
        if ($offr_id = $this->_request->getParam('disable')) {
       		$result = $offer->DisableOffer($offr_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Offer ID #$offr_id has been disabled.";
			else $this->view->save_success = "An error has occured and the offer cannot be disabled at this time.";
       	}
    
        if ($offr_id = $this->_request->getParam('enable')) {
       		$result = $offer->EnableOffer($offr_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Offer ID #$offr_id has been enabled.";
			else $this->view->save_success = "An error has occured and the offer cannot be enabled at this time.";
       	}
	        	
        $inputs[1] = $this->form->getElement('offer_name');
        $inputs[2] = $this->form->getElement('prod_id');
        $inputs[3] = $this->form->getElement('offer_cost');
        $inputs[4] = $this->form->getElement('recur_term');
        $inputs[5] = $this->form->getElement('trial_cost');
        $inputs[6] = $this->form->getElement('trial_term');
        $inputs[7] = $this->form->getElement('impulse_product_id');
       
        $records = $offer->ListRecords($this->getInvokeArg('bootstrap'));

        if (!is_array($records)) $this->view->list_error = "An error has occured and the record list cannot be displayed.";
        elseif (count($records) == 0) $this->view->list_error = "No records found.";
        
        $this->view->records = $records;
        $this->view->inputs = $inputs;
        
    }

    public function campaignAction()
    {

    	require_once('Campaign.php');
        $campaign = new Campaign();

		if ($this->_request->getParam('form')) {
			$campaign_records = $campaign->GetCampaignParams(
			$this->_request->getParam('id'), $this->getInvokeArg('bootstrap'));
			if (is_array($campaign_records)) {
				$this->view->code = $campaign->BuildFormCode($this->_request->getParam('form'),
									$this->_request->getParam('id'),$campaign_records);
				$this->view->escaped_code = htmlspecialchars($this->view->code);
				$this->view->campaign_id = $this->_request->getParam('id');
			}
			else {
				$this->view->form_code_error = "An error has occured and the form code for this campaign cannot be displayed at this time.";
			}
  			$this->_helper->viewRenderer->setRender('campaign/'
  			.(eregi('MULTI',$this->_request->getParam('form')) ? 'multi' : 'single')); 
			return;
		}

        $this->form = $campaign->DrawForm($this->getInvokeArg('bootstrap'));

        if ($this->_request->getParam('save'))
        	if ($this->form->isValid($_POST)) {
        		$result = $campaign->SaveForm($this->form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your campaign has been saved successfully.";
				else $this->view->save_error = "You are attempting to save a duplicate campaign record.";
				$this->view->save_id = $result;
        	}
        	else { $this->view->save_error = 1; }

        if ($camp_id = $this->_request->getParam('disable')) {
       		$result = $campaign->DisableCampaign($camp_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Campaign ID #$camp_id has been disabled.";
			else $this->view->save_success = "An error has occured and the campaign cannot be disabled at this time.";
       	}

        if ($camp_id = $this->_request->getParam('enable')) {
       		$result = $campaign->EnableCampaign($camp_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Campaign ID #$camp_id has been enabled.";
			else $this->view->save_success = "An error has occured and the campaign cannot be enabled at this time.";
       	}

        $inputs[1] = $this->form->getElement('campaign');
        $inputs[2] = $this->form->getElement('pages');
		$inputs[3] = $this->form->getElement('offr_id');
		$inputs[4] = $this->form->getElement('upsl_id');
		$inputs[5] = $this->form->getElement('bump_id');
		$inputs[6] = $this->form->getElement('variety_countries');
		$inputs[7] = $this->form->getElement('countries');
		$inputs[8] = $this->form->getElement('variety_shippers');
		$inputs[9] = $this->form->getElement('shippers');
		$inputs[10] = $this->form->getElement('impulse');
		$inputs[11] = $this->form->getElement('impulse_campaign_id');

        $records = $campaign->ListRecords($this->getInvokeArg('bootstrap'));

        if (!is_array($records)) $this->view->list_error = "An error has occured and the record list cannot be displayed.";
        elseif (count($records) == 0) $this->view->list_error = "No records found.";

        $this->view->records = $records;
        $this->view->inputs = $inputs;

    }

    
    
    
    public function reportAction()
    {
    	
    	require_once('Report.php');
        $report = new Report();

        if ($this->_request->getParam('start_date'))
        	$this->mm->report_start = $_POST['start_date'];
        if ($this->_request->getParam('stop_date'))
        	$this->mm->report_stop = $_POST['stop_date'];
        
    	if ($this->_request->getParam('user')) {
			$this->view->records = $report->AffiliateReport($this->mm,
			$this->_request->getParam('user'), $this->getInvokeArg('bootstrap'));
			echo $this->view->render('console/report/AffiliateReport.phtml');
  			exit;
		}

        $this->form = $report->DrawForm($this->getInvokeArg('bootstrap'));
        
        $inputs[1] = $this->form->getElement('report_type');
        $inputs[2] = $this->form->getElement('start_date');
        $inputs[3] = $this->form->getElement('stop_date');

        $report_type = "ReportByProduct";
        if ($this->_request->getParam('report')) {
        	$report_type = $this->_request->getParam('report_type');
        }
        
        $records = $report->$report_type($this->getInvokeArg('bootstrap'));

        if (!is_array($records)) $this->view->list_error = "An error has occured and the record list cannot be displayed.";
	    elseif (count($records) == 0) $this->view->list_error = "No records found.";
	
		$this->view->records = $records;        
        $this->view->inputs = $inputs;

        $this->view->report = $this->view->render('console/report/'.$report_type.'.phtml');
        
    }
    
    
    
    
    public function profileAction()
    {
    	
    	require_once('Profile.php');
        $profile = new Profile();

        $this->g_form = $profile->DrawGatewayForm($this->getInvokeArg('bootstrap'));
        $this->s_form = $profile->DrawShippingForm();
	        	
        if (eregi("GATEWAY", $this->_request->getParam('save')))
        	if ($this->g_form->isValid($_POST)) {
        		$result = $profile->SaveGatewayForm($this->g_form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your gateway has been saved successfully.";
        		elseif (eregi("gate error",$result)) $this->view->save_error_gateway = "You have not selected a gateway to add.";
        		elseif (eregi("conf error",$result)) $this->view->save_error_gateway = "The password and confirmation do not match.";
				else $this->view->save_error_gateway = "You are attempting to save a duplicate gateway record.";
				$this->view->g_save_id = $result;
        	}
        	else { $this->view->save_error_gateway = 1; }

        if (eregi("SHIPPING", $this->_request->getParam('save')))
        	if ($this->s_form->isValid($_POST)) {
        		$result = $profile->SaveShippingForm($this->s_form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your shipper has been saved successfully.";
				else $this->view->save_error_shipping = "You are attempting to save a duplicate shipper record.";
				$this->view->s_save_id = $result;
        	}
        	else { $this->view->save_error_shipping = 1; }        	

        if (eregi("GATEWAY", $this->_request->getParam('edit'))) {
        	$plan_id = $this->_request->getParam('id');	$this->view->edit = $plan_id;
			$gateway = $profile->GetGateway($plan_id, $this->getInvokeArg('bootstrap'));
			$this->g_form->gate_name->setValue($gateway['alias']);
			$this->g_form->gate_id->setValue($gateway['gate_id']);
			$this->g_form->gate_acct->setValue($gateway['gate_acct']);
			$this->g_form->gate_user->setValue($gateway['gate_user']);
			$this->g_form->gate_pass->setValue($gateway['gate_pass']);
			$this->g_form->gate_plan->setValue($gateway['gate_plan']);
			$this->g_form->threshold->setValue($gateway['threshold']);
			$this->g_form->weight->setValue($gateway['weight']);
        	if ($this->_request->isPost()) {
        		if ($this->g_form->isValid($_POST)) {
        			$result = $profile->EditGatewayForm($this->g_form, $this->getInvokeArg('bootstrap'));
	        		if ($result == true) {
	        			$this->view->save_success = "Gateway ID #$plan_id has been edited.";
	        			$this->view->plan = false;
	        		}
	        		else {
						$this->view->save_error_gateway = "You are attempting to save a duplicate gateway record.";
	        		}
	        	}
        	    else { $this->view->save_error_gateway = 1; }
        	}
        	else { $this->view->save_error_gateway = 1; }
        }

        if (eregi("SHIPPING", $this->_request->getParam('edit'))) {
        	$plan_id = $this->_request->getParam('id');	$this->view->edit = $plan_id;
			$gateway = $profile->GetGateway($plan_id, $this->getInvokeArg('bootstrap'));
			$this->g_form->gate_name->setValue($gateway['alias']);
			$this->g_form->gate_id->setValue($gateway['gate_id']);
			$this->g_form->gate_acct->setValue($gateway['gate_acct']);
			$this->g_form->gate_user->setValue($gateway['gate_user']);
			$this->g_form->gate_pass->setValue($gateway['gate_pass']);
			$this->g_form->gate_plan->setValue($gateway['gate_plan']);
			$this->g_form->threshold->setValue($gateway['threshold']);
        	if ($this->_request->isPost()) {
        		if ($this->g_form->isValid($_POST)) {
        			$result = $profile->EditGatewayForm($this->g_form, $this->getInvokeArg('bootstrap'));
	        		if ($result == true) {
	        			$this->view->save_success = "Gateway ID #$plan_id has been edited.";
	        			$this->view->plan = false;
	        		}
	        		else {
						$this->view->save_error_gateway = "You are attempting to save a duplicate gateway record.";
	        		}
	        	}
        	    else { $this->view->save_error_gateway = 1; }
        	}
        	else { $this->view->save_error_gateway = 1; }
        }     	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        if (eregi("GATEWAY", $this->_request->getParam('disable'))) {
        	$plan_id = $this->_request->getParam('id');
       		$result = $profile->DisableGateway($plan_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Gateway ID #$plan_id has been disabled.";
			else $this->view->save_success = "An error has occured and the gateway cannot be disabled at this time.";
       	}
    
        if (eregi("GATEWAY", $this->_request->getParam('enable'))) {
        	$plan_id = $this->_request->getParam('id');
       		$result = $profile->EnableGateway($plan_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Gateway ID #$plan_id has been enabled.";
			else $this->view->save_success = "An error has occured and the gateway cannot be enabled at this time.";
       	}
       	
        if (eregi("SHIPPING", $this->_request->getParam('disable'))) {
        	$ship_id = $this->_request->getParam('id');
       		$result = $profile->DisableShipping($ship_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Shipping Method ID #$ship_id has been disabled.";
			else $this->view->save_success = "An error has occured and the shipping method cannot be disabled at this time.";
       	}

       	if (eregi("SHIPPING", $this->_request->getParam('enable'))) {
        	$ship_id = $this->_request->getParam('id');
       		$result = $profile->EnableShipping($ship_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "Shipping Method ID #$ship_id has been enabled.";
			else $this->view->save_success = "An error has occured and the shipping method cannot be enabled at this time.";
       	}
        	
        $g_inputs[1] = $this->g_form->getElement('gate_name');
        $g_inputs[2] = $this->g_form->getElement('gate_id');
        $g_inputs[3] = $this->g_form->getElement('gate_acct');
        $g_inputs[4] = $this->g_form->getElement('gate_user');
        $g_inputs[5] = $this->g_form->getElement('gate_pass');
        $g_inputs[6] = $this->g_form->getElement('conf_pass');
        $g_inputs[7] = $this->g_form->getElement('gate_plan');
        $g_inputs[8] = $this->g_form->getElement('threshold');
        $g_inputs[9] = $this->g_form->getElement('checking');
        $g_inputs[10] = $this->g_form->getElement('weight');
        
        $s_inputs[1] = $this->s_form->getElement('ship_name');
        $s_inputs[2] = $this->s_form->getElement('ship_cost');
		
        $gateways = $profile->ListGatewayRecords($this->getInvokeArg('bootstrap'));
        $shippers = $profile->ListShippingRecords($this->getInvokeArg('bootstrap'));
        
        if (!is_array($gateways)) $this->view->list_error_gateway = "An error has occured and the record list cannot be displayed.";
        elseif (count($gateways) == 0) $this->view->list_error_gateway = "No records found.";
        
        if (!is_array($shippers)) $this->view->list_error_shipping = "An error has occured and the record list cannot be displayed.";
        elseif (count($shippers) == 0) $this->view->list_error_shipping = "No records found.";

        $this->view->gateways = $gateways;
        $this->view->g_inputs = $g_inputs;

        $this->view->shippers = $shippers;
        $this->view->s_inputs = $s_inputs;
        
    }
    
    
    
    
    
    public function userAction()
    {

    	require_once('User.php');    	
        $user = new User();

        $this->form = $user->DrawForm();
        $this->find_form = $user->DrawFindForm();

        if ($this->_request->getParam('save'))
        	if ($this->form->isValid($_POST)) {
        		$result = $user->SaveForm($this->form, $this->getInvokeArg('bootstrap'));
        		if (is_numeric($result)) $this->view->save_success = "Your user has been saved successfully.";
        		elseif (eregi("conf error",$result)) $this->view->save_error = "The password and confirmation do not match.";
				else $this->view->save_error = "You are attempting to save a duplicate user record.";
				$this->view->save_id = $result;
        	}
        	else { $this->view->save_error = 1; }

        if ($user_id = $this->_request->getParam('disable')) {
       		$result = $user->DisableUser($user_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "User ID #$user_id has been disabled.";
			else $this->view->save_success = "An error has occured and the user cannot be disabled at this time.";
       	}
    
        if ($user_id = $this->_request->getParam('enable')) {
       		$result = $user->EnableUser($user_id, $this->getInvokeArg('bootstrap'));
       		if ($result == true) $this->view->save_success = "User ID #$user_id has been enabled.";
			else $this->view->save_success = "An error has occured and the user cannot be enabled at this time.";
       	}
        	
        $this->find_form = $user->DrawFindForm();	
        
        $search_inputs[1] = $this->find_form->getElement('find_user_type'); 
        $search_inputs[2] = $this->find_form->getElement('find_user_last');
        $search_inputs[3] = $this->find_form->getElement('find_user_first');
        $search_inputs[4] = $this->find_form->getElement('find_user_email');
        $search_inputs[5] = $this->find_form->getElement('find_user_phone');
        	
        $inputs[1] = $this->form->getElement('user_type');
        $inputs[2] = $this->form->getElement('business');
        $inputs[3] = $this->form->getElement('user_first');
        $inputs[4] = $this->form->getElement('user_last');
        $inputs[5] = $this->form->getElement('user_address');
        $inputs[6] = $this->form->getElement('user_zip');
        $inputs[7] = $this->form->getElement('user_phone');
        $inputs[8] = $this->form->getElement('user_email');
        $inputs[9] = $this->form->getElement('username');
        $inputs[10] = $this->form->getElement('password');
        $inputs[11] = $this->form->getElement('confirm');
        $inputs[12] = $this->form->getElement('messenger');
        $inputs[13] = $this->form->getElement('wire_info');
        
        $records = $user->ListRecords($this->find_form, $this->getInvokeArg('bootstrap'));

        if (!is_array($records)) $this->view->list_error = "An error has occured and the record list cannot be displayed.";
        elseif (count($records) == 0) $this->view->list_error = "No records found.";

        $this->view->search_inputs = $search_inputs;
        $this->view->records = $records;
        $this->view->inputs = $inputs;

    }
    
    
    
    public function logoutAction() {

    	unset($this->mm->user_id);
    	unset($this->mm->administrator);
    	unset($this->mm->affiliate);
    	unset($this->mm->employee);
    	unset($this->mm->username);
    	unset($this->mm->user_email);
    	unset($this->mm->user_first);
    	unset($this->mm->user_last);

    	$this->_redirect('/login');
    	
    }
    
    
    
}