<?php

class Campaign
{

	protected $MM_COUNTRY			= 'mm_country';
	protected $MM_GATEWAY			= 'mm_gateway';
	protected $MM_USER				= 'mm_user';
	protected $MM_USER_OFFER		= 'mm_user_offer';
	protected $MM_USER_PRODUCT		= 'mm_user_product';
	protected $MM_USER_GATEWAY		= 'mm_user_gateway';
	protected $MM_USER_SHIPPING		= 'mm_user_shipping';
	protected $MM_USER_CAMPAIGN		= 'mm_user_campaign';
	protected $CAMPAIGN2COUNTRY		= 'campaign2country';
	protected $CAMPAIGN2SHIPPING	= 'campaign2shipping';

	
	public function __construct() { }
	
	
	public function DrawForm($b) {
		
    	$f = new Zend_Form;
   
		$i1 = $f->createElement('text', 'campaign', array('maxlength' => 64, 'size' => 40));
		$i1->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('stringLength', false, array(2, 64))
           ->addFilter('StringTrim');

        $options = array(
        '1' => '1 page layout',
        '2' => '2 page layout',
        '3' => '3 page layout');
       	
		$i2 = $f->createElement('select', 'pages');
		$i2->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);
           
        $options = array('0' => '-- select --');
        $offers = $this->GetOffers($b);

        if (is_array($offers))
        	if (count($offers) > 0)        
        		foreach ($offers as $offer)
        			$options[$offer['offr_id']] = "$offer[offer_name] ($offer[offer_cost])";
			else $options['0'] = "No offers found.";
       	else $options['0'] = "An error occured.";

		$i3 = $f->createElement('select', 'offr_id');
		$i3->setRequired(true)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $options = array('0' => '-- select --');
        $offers = $this->GetOffers($b);
        
        if (is_array($offers))
        	if (count($offers) > 0)    
        		foreach ($offers as $offer)
        			$options[$offer['offr_id']] = "$offer[offer_name] ($offer[offer_cost])";
			else $options['0'] = "No offers found.";
       	else $options['0'] = "An error occured.";

		$i4 = $f->createElement('select', 'upsl_id');
		$i4->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $options = array('0' => '-- select --');
        $offers = $this->GetOffers($b);
        
        if (is_array($offers))
        	if (count($offers) > 0)    
        		foreach ($offers as $offer)
        			$options[$offer['offr_id']] = "$offer[offer_name] ($offer[offer_cost])";
			else $options['0'] = "No offers found.";
       	else $options['0'] = "An error occured.";

		$i5 = $f->createElement('select', 'bump_id');
		$i5->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);

        $options = array();
        $countries = $this->GetCountries($b);
        
        if (is_array($countries))
        	if (count($countries) > 0) {
        		$options['US'] = 'UNITED STATES';
        		foreach ($countries as $country) {
        			if ($country['abbr'] == 'US') continue;
        			$options[$country['abbr']] = "$country[verbose] $country[language]";
        		}
        	}
			else $options['0'] = "No countries found.";
       	else $options['0'] = "An error occured.";
       	
		$i6 = $f->createElement('select', 'variety_countries', array('size' => 3, 'onchange' => 'addOpt(this.options[this.selectedIndex].text,this.value,countries);', 'style' => 'width:100%;'));
		$i6->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);
          
		$i7 = $f->createElement('multiselect', 'countries', array('size' => 3, 'onchange' => 'this.remove(this.selectedIndex);', 'style' => 'width:100%;'));
		$i7->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->setRegisterInArrayValidator(false);           

        $options = array();
        $shippers = $this->GetShippers($b);
        
        if (is_array($shippers))
        	if (count($shippers) > 0)        
        		foreach ($shippers as $shipper)
        			$options[$shipper['ship_id']] = "$shipper[ship_name] ($shipper[ship_cost])";
			else $options['0'] = "No shippers found.";
       	else $options['0'] = "An error occured.";
       	
		$i8 = $f->createElement('select', 'variety_shippers', array('size' => 3, 'onchange' => 'addOpt(this.options[this.selectedIndex].text,this.value,shippers);', 'style' => 'width:100%;'));
		$i8->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addMultiOptions($options);
          
		$i9 = $f->createElement('multiselect', 'shippers', array('size' => 3, 'onchange' => 'this.remove(this.selectedIndex);', 'style' => 'width:100%;'));
		$i9->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->setRegisterInArrayValidator(false);   

		$i10 = $f->createElement('checkbox', 'impulse');
		$i10->setRequired(false)
		   ->removeDecorator('label')
           ->removeDecorator('HtmlTag');
   
		$i11 = $f->createElement('text', 'impulse_campaign_id', array('maxlength' => 64, 'size' => 30));
		$i11->setRequired(false)
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
		  ->addElement($i7)
		  ->addElement($i8)
		  ->addElement($i9)
		  ->addElement($i10)
		  ->addElement($i11);
		  

		return $f;

	}
	
	
	
	
	
	public function SaveForm($f, $b) {

		foreach ($f->getValues() as $key => $value) {
			if (eregi("variety_countries",$key)) continue;
			if (eregi("countries",$key)) continue;
			if (eregi("variety_shippers",$key)) continue;
			if (eregi("shippers",$key)) continue;
			$data[$key] = $value;
		}
				
		$data['user_id'] = '12345';
		$data['created'] = date("Y-m-d");
		$db = $b->getResource('db');
	    			
		$db->beginTransaction();
			
		try {
			$db->insert($this->MM_USER_CAMPAIGN, $data);
			$cid = $db->lastInsertId($this->MM_USER_CAMPAIGN, 'camp_id');
			$variety = $f->getValue('countries');
			if (is_array($variety))
			foreach ($variety as $vari_id) {
				$data = array("camp_id" => $cid, "country" => $vari_id);
				$db->insert($this->CAMPAIGN2COUNTRY, $data);
			}
			$variety = $f->getValue('shippers');
			if (is_array($variety))
			foreach ($variety as $vari_id) {
				$data = array("camp_id" => $cid, "ship_id" => $vari_id);
				$db->insert($this->CAMPAIGN2SHIPPING, $data);
			}
			$db->commit();
			$db->closeConnection();
			return $cid;
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
c.camp_id,
c.public,
c.private,
c.personal,
c.disable,
c.campaign,
c.pages,
c.created,
o.offer_name,
o.offer_cost,
c2c.countries
from $this->MM_USER_CAMPAIGN `c`
LEFT JOIN (SELECT camp_id, count(*) as `countries` FROM $this->CAMPAIGN2COUNTRY GROUP BY camp_id) AS `c2c` ON (c.camp_id = c2c.camp_id),
$this->MM_USER_OFFER `o`, $this->MM_USER `u`
where c.user_id = 12345
  and c.user_id = u.user_id
  and c.offr_id = o.offr_id
order by c.camp_id desc
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
	
	
	
	
	
	
	public function GetOffers($b) {

		$db = $b->getResource('db');
		
		$sql =
"
select
o.offr_id,
o.offer_name,
o.offer_cost
from $this->MM_USER_OFFER `o`, $this->MM_USER `u`
where o.user_id = 12345
  and o.user_id = u.user_id
  and o.disable = 0
order by o.offer_name
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
c.abbr,
c.verbose,
c.language,
c.currency
from $this->MM_COUNTRY `c`
where c.disable = 0
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
	
	
	
	
	
	
	
	
		
	
	public function DisableCampaign($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "1");
		$where[] = "camp_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_campaign', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	

	
	
	
	
	public function EnableCampaign($id,$b) {
		
		$db = $b->getResource('db');

		$data = array("disable" => "0");
		$where[] = "camp_id = $id";

		try {
    	$db->beginTransaction();
		$db->update('mm_user_campaign', $data, $where);
		$db->commit();
		$db->closeConnection();
		return true;
		} catch ( Exception $e ) {
		$db->rollback();
		$db->closeConnection();
		return false;
		}		
		
	}
	
	
	
	
	

	
	
	
	
	public function GetShippers($b) {

		$db = $b->getResource('db');
		
		$sql =
"
select
s.ship_id,
s.ship_name,
s.ship_cost
from $this->MM_USER_SHIPPING `s`, $this->MM_USER `u`
where s.user_id = 12345
  and s.user_id = u.user_id
  and s.disable = 0
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
	
	
	
	
	
	
	
	public function GetCampaignParams($id, $b) {

		$db = $b->getResource('db');
		
		$sql =
"
select
distinct
c.user_id,
c.camp_id,
c.upsl_id,
c2c.country,
c2s.ship_id,
p.product_sku,
p.product_name,
p.product_cost,
p.product_size,
o.offer_cost,
o.recur_term,
o.trial_cost,
o.trial_term,
s.ship_name,
s.ship_cost
from $this->MM_USER_CAMPAIGN `c`
LEFT JOIN $this->MM_USER_OFFER AS `o` on (c.offr_id = o.offr_id)
LEFT JOIN $this->MM_USER_PRODUCT AS `p` on (o.prod_id = p.prod_id)
LEFT JOIN $this->CAMPAIGN2SHIPPING AS `c2s` on (c.camp_id = c2s.camp_id)
LEFT JOIN $this->MM_USER_SHIPPING AS `s` on (c2s.ship_id = s.ship_id)
LEFT JOIN $this->CAMPAIGN2COUNTRY AS `c2c` on (c.camp_id = c2c.camp_id),
$this->MM_USER `u`
where c.camp_id = $id
  and c.user_id = u.user_id
  and u.user_id = 12345
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
	
	
	
	
	
	public function BuildFormCode($t, $id, $v) {
		
		$user_id = $v[0]['user_id'];

		// get all the unique values for the respective columns
		foreach ($v as $row)
			foreach ($row as $key => $value) {
				if (eregi("SHIP",$key) && ($value != "")) $distincts['shipping'][$key][$value] = true;
				if ($value != "") $distincts[$key][$value] = true;
			}
		
		// set these vars to false, and prevent a warning notice
		$has_billing = false;
		$has_shipping = false;
			
		// if a payment gateway was assigned for this campaign, then it has billing
		if (key_exists('trial_cost',$distincts) && ($distincts['trial_cost'] > 0))
			$has_billing = true;		
		
		// if a payment gateway was assigned for this campaign, then it has billing
		if (key_exists('offer_cost',$distincts) && ($distincts['offer_cost'] > 0))
			$has_billing = true;		

		// if a payment gateway was assigned for this campaign, then it has billing
		if (key_exists('product_cost',$distincts) && ($distincts['product_cost'] > 0))
			$has_billing = true;

		// if a payment gateway was assigned for this campaign, then it has billing
		if (key_exists('upsl_id',$distincts) && ($distincts['upsl_id'] > 0)) {
			$hidden_upsell = "<input type=\"hidden\" id=\"mm-offr_id\" name=\"mm-offr_id\" value=\"".$distincts['upsl_id']."\" />";
			$has_upsell = true;
		}
		
		// if shipping methods were selected for this campaign, then it has shipping
		if (key_exists('ship_id',$distincts) && ($distincts['ship_id'] > 0)) {
			// assemble shipping fields and values into manageable arrays
			foreach ($distincts['shipping'] as $column_name => $column_values) { $i = -1;
				foreach ($column_values as $value => $bool)
					$shippers[++$i][$column_name] = $value;
			}

			// create drop-down options for shipping form
			for ($i = 0;$i < count($shippers);++$i)
				$shipping_options[$i] = "<option value=\"".$shippers[$i]['ship_id']."\">".$shippers[$i]['ship_name']." (".$shippers[$i]['ship_cost'].")</option>";

			$shipping_options = implode("",$shipping_options);
			$has_shipping = true;
		}
		
		// stuff countries for this campaign into a manageable array
		if (key_exists('country',$distincts) && ($distincts['country'] > 0)) { $i = -1;
			foreach ($distincts['country'] as $country => $bool) {
				$countries['country'][++$i] = $country;
			}

			// build the hidden input for a country value
			if (is_array($countries))
			$hidden_country = "<input type=\"hidden\" id=\"mm-country\" name=\"mm-country\" value=\"".$countries['country'][0]."\" />";
		}
		
		// stuff offers for this campaign into a manageable array		
		if (key_exists('offr_id',$distincts) && ($distincts['offr_id'] > 0)) { $i = -1;
			foreach ($distincts['offr_id'] as $offr_id => $bool) {
				$offers['offr_id'][++$i] = $offr_id;
			}

			// build the hidden input for an offer value
			if (is_array($offers))
			$hidden_offer = "<input type=\"hidden\" id=\"mm-offr_id\" name=\"mm-offr_id\" value=\"".$offers['offr_id'][0]."\" />";
		}

		// stuff products for this campaign into a manageable array
		if (key_exists('prod_id',$distincts) && ($distincts['prod_id'] > 0)) { $i = -1;
			foreach ($distincts['prod_id'] as $prod_id => $bool) {
				$products['prod_id'][++$i] = $prod_id;
			}

			// build the hidden input for a product value
			if (is_array($products))
			$hidden_product = "<input type=\"hidden\" id=\"mm-prod_id\" name=\"mm-prod_id\" value=\"".$products['prod_id'][0]."\" />";
		}

		// build the hidden inputs for campaign and user id values
		$hidden_campaign = "<input type=\"hidden\" id=\"mm-camp_id\" name=\"mm-camp_id\" value=\"".$id."\" />";
		$hidden_user = "<input type=\"hidden\" id=\"mm-user_id\" name=\"mm-user_id\" value=\"".$user_id."\" />";
		
		$MULTI_FORM_CODE =
"
<br />
<h3>Landing Page Code (Contact Form)</h3>
<pre style=\"width:90%;overflow-x:auto;overflow-y:auto;\">
"
.htmlspecialchars(
"
<link href=\"http://www.proleroinc.com/css/mm-multi-form-style.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />
<script src=\"http://www.proleroinc.com/js/mm-multi-form-script.js\" type=\"text/javascript\"></script>
<script language=\"javascript\"> mm_kill_cookie() </script>
<form id=\"mm-order-form\" method=\"post\">
"
.$hidden_campaign.$hidden_user.
"

<fieldset id=\"mm-contact-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\" width=\"90\">First Name:</td><td><input id=\"mm-bill_first\" name=\"mm-bill_first\" type=\"text\" maxlength=\"24\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Last Name:</td><td><input id=\"mm-bill_last\" name=\"mm-bill_last\" type=\"text\" maxlength=\"24\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">St. Address:</td><td><input id=\"mm-bill_address\" name=\"mm-bill_address\" type=\"text\" maxlength=\"48\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Zip Code:</td><td><input id=\"mm-bill_zip\" name=\"mm-bill_zip\" type=\"text\" maxlength=\"8\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Phone:</td><td>
<input id=\"mm-bill_phone_2\" name=\"mm-bill_phone_2\" type=\"text\" maxlength=\"3\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,2,'mm-bill_phone_3');\" />
<input id=\"mm-bill_phone_3\" name=\"mm-bill_phone_3\" type=\"text\" maxlength=\"3\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,2,'mm-bill_phone_4');\"/>
<input id=\"mm-bill_phone_4\" name=\"mm-bill_phone_4\" type=\"text\" maxlength=\"4\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,3,'mm-bill_email');\"/>
</td></tr>
<tr><td id=\"mm-label\">eMail:</td><td><input id=\"mm-bill_email\" name=\"mm-bill_email\" type=\"text\" maxlength=\"64\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_contact('mm-order-form',".($has_billing ? '1' : '0').");\" /></td></tr>
</table></span>
</fieldset>

<fieldset id=\"mm-loading-pane\">
<span id=\"mm-form-span\"><img src=\"http://www.proleroinc.com/img/loader64.gif\" alt=\"\" style=\"border:0;margin:85px;\" /></span>
</fieldset>

<fieldset id=\"mm-response-pane\">
<legend id=\"mm-response\" class=\"mm-legend\"></legend>
<div id=\"mm-spacer\">&nbsp;</div><span id=\"mm-form-span\"><span id=\"mm-response-message\"></span></span>
</fieldset>
</form>
")."
</pre>

".($has_billing ?
"
<br />
<h3>Order Page Code (Billing/Shipping Form)</h3>
<pre style=\"width:90%;overflow-x:auto;overflow-y:auto;\">
"
.htmlspecialchars(
"
<link href=\"http://www.proleroinc.com/css/mm-multi-form-style.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />
<script src=\"http://www.proleroinc.com/js/mm-multi-form-script.js\" type=\"text/javascript\"></script>
<form id=\"mm-order-form\" method=\"post\">
"
.$hidden_campaign.$hidden_user.
"

<fieldset id=\"mm-billing-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\">Card Type:</td><td><select id=\"mm-card_type\" name=\"mm-card_type\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onchange=\"mm_start_card(this)\">
<option value=\"Visa\">Visa</option><option value=\"Mastercard\">Mastercard</option><option value=\"Amex\">American Express</option><option value=\"Discover\">Discover Card</option></select></td></tr>
<tr><td id=\"mm-label\">Card Number:</td><td><input id=\"mm-card_number\" name=\"mm-card_number\" value=\"4\" type=\"text\" maxlength=\"16\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\"\">Expiration Date:</td><td><select id=\"mm-expires_mm\" name=\"mm-expires_mm\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" />
<option value=\"\">MM</option><option value=\"01\">01</option><option value=\"02\">02</option><option value=\"03\">03</option><option value=\"04\">04</option><option value=\"05\">05</option><option value=\"06\">06</option><option value=\"07\">07</option><option value=\"08\">08</option><option value=\"09\">09</option><option value=\"10\">10</option><option value=\"11\">11</option><option value=\"12\">12</option>
</select><select id=\"mm-expires_yy\" name=\"mm-expires_yy\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" />
<option value=\"\">YYYY</option><option value=\"10\">2010</option><option value=\"11\">2011</option><option value=\"12\">2012</option><option value=\"13\">2013</option><option value=\"14\">2014</option><option value=\"15\">2015</option></select></td></tr>
<tr><td id=\"mm-label\">Security Code:</td><td><input id=\"mm-cvv_code\" name=\"mm-cvv_code\" type=\"text\" maxlength=\"4\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /> <a id=\"mm-what-is-it\" href=\"#\">what is this?</a></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_billing('mm-order-form',".($has_shipping ? '1' : '0').");\" /></td></tr>
</table></span>
</fieldset>
".($has_shipping ? "

<fieldset id=\"mm-shipping-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\">Shipping Type:</td><td><select id=\"mm-ship_id\" name=\"mm-ship_id\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\"><option value=\"\"> -- select -- </option>$shipping_options</select></td></tr>
<tr><td id=\"mm-label\" colspan=\"2\">Shipping address same as billing? <input id=\"mm-same_as_billing\" name=\"mm-same_as_billing\" type=\"checkbox\" value=\"1\" /></td></tr>
<tr><td id=\"mm-label\" colspan=\"2\">If not, add your shipping address here:</td></tr>
<tr><td id=\"mm-label\">St. Address:</td><td><input id=\"mm-ship_address\" name=\"mm-ship_address\" type=\"text\" maxlength=\"48\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Zip Code:</td><td><input id=\"mm-ship_zip\" name=\"mm-ship_zip\" type=\"text\" maxlength=\"5\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_shipping('mm-order-form');\" /></td></tr>
</table></span>
</fieldset>
" : "")."
<fieldset id=\"mm-loading-pane\">
<span id=\"mm-form-span\"><img src=\"http://www.proleroinc.com/img/loader64.gif\" alt=\"\" style=\"border:0;margin:85px;\" /></span>
</fieldset>

<fieldset id=\"mm-response-pane\">
<div id=\"mm-spacer\">&nbsp;</div><span id=\"mm-form-span\"><span id=\"mm-response-message\"></span></span>
</fieldset>
</form>
")."
</pre>

".($has_upsell ?
"
<br />
<h3>Upsell Page Code</h3>
<pre style=\"width:90%;overflow-x:auto;overflow-y:auto;\">
"
.htmlspecialchars(
"
<link href=\"http://www.proleroinc.com/css/mm-multi-form-style.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />
<script src=\"http://www.proleroinc.com/js/mm-multi-form-script.js\" type=\"text/javascript\"></script>
<form id=\"mm-order-form\" method=\"post\">
"
.$hidden_campaign.$hidden_user.$hidden_upsell.
"

<fieldset id=\"mm-billing-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_upsell('mm-order-form');\" /></td></tr>
</table></span>
</fieldset>
" : "")."
<fieldset id=\"mm-loading-pane\">
<span id=\"mm-form-span\"><img src=\"http://www.proleroinc.com/img/loader64.gif\" alt=\"\" style=\"border:0;margin:85px;\" /></span>
</fieldset>

<fieldset id=\"mm-response-pane\">
<div id=\"mm-spacer\">&nbsp;</div><span id=\"mm-form-span\"><span id=\"mm-response-message\"></span></span>
</fieldset>
</form>
")."
</pre>
" : "");

		if (eregi("MULTI",$t))
			return $MULTI_FORM_CODE;		
		
		$SINGLE_FORM_CODE =
"
<link href=\"http://www.proleroinc.com/css/mm-single-form-style.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />
<script src=\"http://www.proleroinc.com/js/mm-single-form-script.js\" type=\"text/javascript\"></script>
<form id=\"mm-order-form\" method=\"post\">
<input type=\"hidden\" id=\"mm-ordr_id\" name=\"mm-ordr_id\" value=\"\" />"
.$hidden_campaign.$hidden_user.
"

<fieldset id=\"mm-contact-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\" width=\"90\">First Name:</td><td><input id=\"mm-bill_first\" name=\"mm-bill_first\" type=\"text\" maxlength=\"24\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Last Name:</td><td><input id=\"mm-bill_last\" name=\"mm-bill_last\" type=\"text\" maxlength=\"24\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">St. Address:</td><td><input id=\"mm-bill_address\" name=\"mm-bill_address\" type=\"text\" maxlength=\"48\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Zip Code:</td><td><input id=\"mm-bill_zip\" name=\"mm-bill_zip\" type=\"text\" maxlength=\"8\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Phone:</td><td>
<input id=\"mm-bill_phone_2\" name=\"mm-bill_phone_2\" type=\"text\" maxlength=\"3\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,2,'mm-bill_phone_3');\" />
<input id=\"mm-bill_phone_3\" name=\"mm-bill_phone_3\" type=\"text\" maxlength=\"3\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,2,'mm-bill_phone_4');\"/>
<input id=\"mm-bill_phone_4\" name=\"mm-bill_phone_4\" type=\"text\" maxlength=\"4\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onkeyup=\"mm_keyup_focus(this,3,'mm-bill_email');\"/>
</td></tr>
<tr><td id=\"mm-label\">eMail:</td><td><input id=\"mm-bill_email\" name=\"mm-bill_email\" type=\"text\" maxlength=\"64\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_contact('mm-order-form');\" /></td></tr>
</table></span>
</fieldset>
".($has_billing ? "
<fieldset id=\"mm-billing-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\">Card Type:</td><td><select id=\"mm-card_type\" name=\"mm-card_type\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" onchange=\"mm_start_card(this)\">
<option name=\"Visa\">Visa</option><option name=\"Mastercard\">Mastercard</option><option name=\"Amex\">American Express</option><option name=\"Discover\">Discover Card</option></select></td></tr>
<tr><td id=\"mm-label\">Card Number:</td><td><input id=\"mm-card_number\" name=\"mm-card_number\" type=\"text\" maxlength=\"16\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\"\">Expiration Date:</td><td><select id=\"mm-expires_mm\" name=\"mm-expires_mm\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" />
<option value=\"\">MM</option><option value=\"01\">01</option><option value=\"02\">02</option><option value=\"03\">03</option><option value=\"04\">04</option><option value=\"05\">05</option><option value=\"06\">06</option><option value=\"07\">07</option><option value=\"08\">08</option><option value=\"09\">09</option><option value=\"10\">10</option><option value=\"11\">11</option><option value=\"12\">12</option>
</select><select id=\"mm-expires_yy\" name=\"mm-expires_yy\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" />
<option value=\"\">YYYY</option><option value=\"10\">2010</option><option value=\"11\">2011</option><option value=\"12\">2012</option><option value=\"13\">2013</option><option value=\"14\">2014</option><option value=\"15\">2015</option></select></td></tr>
<tr><td id=\"mm-label\">Security Code:</td><td><input id=\"mm-cvv_code\" name=\"mm-cvv_code\" type=\"text\" maxlength=\"4\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /> <a id=\"mm-what-is-it\" href=\"#\">what is this?</a></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" onclick=\"mm_validate_billing('mm-order-form');\" /></td></tr>
</table></span>
</fieldset>
" : "")
.($has_shipping ? "
<fieldset id=\"mm-shipping-pane\">
<span id=\"mm-form-span\"><table border=\"0\">
<tr><td id=\"mm-label\">Shipping Type:</td><td><select id=\"mm-ship_id\" name=\"mm-ship_id\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\"><option value=\"\"> -- select -- </option>$shipping_options</select></td></tr>
<tr><td id=\"mm-label\" colspan=\"2\">Shipping address same as billing? <input id=\"mm-same_as_billing\" name=\"mm-same_as_billing\" type=\"checkbox\" value=\"1\" /></td></tr>
<tr><td id=\"mm-label\" colspan=\"2\">If not, add your shipping address here:</td></tr>
<tr><td id=\"mm-label\">St. Address:</td><td><input id=\"mm-ship_address\" name=\"mm-ship_address\" type=\"text\" maxlength=\"48\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td id=\"mm-label\">Zip Code:</td><td><input id=\"mm-ship_zip\" name=\"mm-ship_zip\" type=\"text\" maxlength=\"5\" onfocus=\"mm_focus_bg(this);\" onblur=\"mm_blur_bg(this);\" /></td></tr>
<tr><td colspan=\"2\" id=\"mm-button-td\"><br /><img src=\"images/submit.gif\" alt=\"\" oclick=\"mm_validate_shipping('mm-order-form');\" /></td></tr>
</table></span>
</fieldset>
" : "")."
<fieldset id=\"mm-loading-pane\">
<span id=\"mm-form-span\"><img src=\"http://www.proleroinc.com/img/loader64.gif\" alt=\"\" style=\"border:0;margin:85px;\" /></span>
</fieldset>

<fieldset id=\"mm-response-pane\">
<div id=\"mm-spacer\">&nbsp;</div><span id=\"mm-form-span\"><span id=\"mm-response-message\"></span></span>
</fieldset>
</form>";

		if (eregi("SINGLE",$t))
			return $SINGLE_FORM_CODE;

	}
	
	
	
	
	
	
}

?>