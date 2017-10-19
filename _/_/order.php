<?

	require_once('_.php'); 
  
	if(!empty($_POST['fields_fname'])) {
  	$sql = "INSERT INTO contacts ";
    $sql.= "(first_name, last_name, address, zip, phone, email, created_time) ";
    $sql.= "VALUES ('".mysql_escape_string($_POST['fields_fname'])."','".mysql_escape_string($_POST['fields_lname'])."','".mysql_escape_string($_POST['fields_address1'])."','";
    $sql.= mysql_escape_string($_POST['fields_zip'])."','".mysql_escape_string($_POST['fields_phone'])."','".mysql_escape_string($_POST['fields_email'])."','".date('Y-m-d H:m:s')."' ) ";
    
    mysql_query($sql);
  } elseif(!empty($_POST['TRANSTYPE'])) {
  
  	$x['Product'] = "819YRB23";
    $x['UserName'] = "Dram1446";
  	$x['TRANSTYPE'] = mysql_escape_string($_POST['TRANSTYPE']);
  	$x['TYPE'] = 'CC';
  	$x['FNAME'] = mysql_escape_string($_POST['FNAME']);
  	$x['LNAME'] = mysql_escape_string($_POST['LNAME']);
  	$x['recurring'] = 0;
  	$x['ADDRESS'] = mysql_escape_string($_POST['ADDRESS']);
  	$x['ZIP'] = mysql_escape_string($_POST['ZIP']);
  	$x['CITY'] = mysql_escape_string("");
  	$x['STATE'] = mysql_escape_string("");
  	$x['COUNTRY'] = mysql_escape_string("US");
  	$x['IPADDR'] = mysql_escape_string($_SERVER['REMOTE_ADDR']);
    $x['BankName'] = "NA";
    $x['BankPhone'] = "1-800-503-4165";
  	$x['CARDNUMBER'] = mysql_escape_string($_POST['cc_number']);
  	$x['EXPDATE'] = mysql_escape_string($_POST['fields_expmonth']).mysql_escape_string($_POST['fields_expyear']);
  	$x['CVV2'] = mysql_escape_string($_POST['cc_cvv']);
  	$x['AMOUNT'] = 1.97;
  	$x['EMAIL'] = mysql_escape_string($_POST['EMAIL']);
        		
      $query_string = '';
    	foreach ($x as $key => $value) {
    		if (!is_array($value)) $query_string.= "$key=$value&";
    		else {
    			foreach ($value as $key_ => $value_) {
    				if (!is_array($value_)) $query_string.= "$key=$value_&";
    				else $query_string.= "$key_=$value_&";
    			}
    		}
    	}
			$query_string = rtrim($query_string, '&');
      
      
    	$ch = curl_init("https://panamapayments.com/dramatech/spost/securepost.aspx/post") or die($php_errormsg);
    	#$ch = curl_init("https://panamapayments.com/dramatech/spost/securepost.aspx/reconcile") or die($php_errormsg);
      curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($ch, CURLOPT_POSTFIELDS, trim($query_string)); // use HTTP POST to send form data
      #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
      $raw_response = curl_exec($ch); //execute post and get results
      curl_close ($ch);
      
  	$sql = "INSERT INTO invoices ";
    $sql.= "(fname, lname, cc_number, cc_cvv2, expdate, address, zip, city, state, ipaddr, email, created_time, recent_charge) ";
    $sql.= "VALUES ('".mysql_escape_string($x['FNAME'])."','".mysql_escape_string($x['LNAME'])."','".mysql_escape_string($x['CARDNUMBER'])."','";
    $sql.= mysql_escape_string($x['CVV2'])."','".mysql_escape_string($x['EXPDATE'])."','".mysql_escape_string($x['ADDRESS'])."','".mysql_escape_string($x['ZIP'])."','";
    $sql.= mysql_escape_string($x['CITY'])."','".mysql_escape_string($x['STATE'])."','".mysql_escape_string($x['IPADDR'])."','".mysql_escape_string($x['EMAIL'])."','".date('Y-m-d H:m:s')."','".date('Y-m-d H:m:s')."' ) ";
    
    mysql_query($sql);
      
		#header('Location: thanks.php');
    #print $raw_response;
    	
  } else {
  	header('Location: index.php');
  }

  printr($GLOBALS['_']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Make Money Online!</title>
<script language="JavaScript" type="text/JavaScript">
<!--
  function MM_openBrWindow(theURL,winName,features) 
  { //v2.0
  	window.open(theURL,winName,features);
	}
//-->
</script>  

<link rel="stylesheet" href="style.css" type="text/css" />
<!--<script type="text/javascript" src="http://o.aolcdn.com/dojo/1.1.1/dojo/gfx-dojo.xd.js"></script>-->
<!--<script type="text/javascript" src="../o/dojotoolkit/dojo/dojo.js"></script>
<script type="text/javascript" src="../o/dojotoolkit/dojox/gfx.js"></script>-->
<!--<script type="text/javascript" src="dialog.js"></script>-->
<script type="text/javascript" src="../o/dojotoolkit/dojo/dojo.js" djConfig="parseOnLoad: true, isDebug: false"></script>

<script type="text/javascript">
	dojo.require('dojo.parser');

</script>

<script type="text/javascript">

	var c_gotoUrl = '<?php echo str_replace('/p/','/p/s/',$_SERVER['REQUEST_URI']); ?>';
  
</script>

<script type="text/javascript" src="https://pruloo-studios.com/crm/chat/js/991802461e906b318f36fbbf72a82d7b.js"></script>
<style type="text/css">
	.messageWindow{

   	width:317px;
  
    height:300px;
    
    padding:10px;
    
    overflow:auto;
    
    margin-left:20px;
    
    margin-top:70px;
    
    float:left;
    
    text-align:left;
  
  }
    
  .agentMessage{
    
    padding:10px;
    
    width:290px;
    
    background-color:#f1f1f1;
    
    font-family:Arial, Helvetica, sans-serif;
    
    font-size:14px;
    
    float:left;
    
    margin-bottom:1px;
    
    text-align:left;
    
    overflow:hidden;
  
   	}
  
    .userMessage{
  
      padding:10px;
      
      width:290px;
      
      border-top:1px solid #c1c1c1;
      
      border-bottom:1px solid #c1c1c1;
      
      font-family:Arial, Helvetica, sans-serif;
      
      font-size:14px;
      
      float:left;
      
      overflow:hidden;
      
      text-align:left;

  }

  .status{

    width:500px;
    
    height:12px;
    
    padding:10px;
    
    font-size:10px;
    
    font-family:Arial, Helvetica, sans-serif;
    
    float:left;
    
    padding-top:45px;
    
    text-align:left;

  }

  .userInput{

    padding:10px;
    
    width:550px;
    
    float:left;
    
    text-align:left;

  }

 	.who{

    font-weight:bold;
    
    text-align:left;

                }

	#chatMainb2890 {

  	width:600px;

    height:500px;

    background-image:url('images/chatbg.jpg');

    display:none;

    position:absolute;

    left:10px;

    top:10px;

    z-index:66;

    text-align:left;

}

#test {

    width:370px;

    text-align:left;

}

#underlay{

    width:100%;

    position:absolute;

    left:-50px;

    top:-50px;

    z-index:55;

    background-image:url('https://pruloo.com/crm/chat/images/black.png');

}

</style>

<script type='text/javascript'>
String.prototype.ltrim = function() 
{
	return this.replace(/^\s+/,"");
	} 
	/* This function adds or subtracts the item from the total.
	But it is generic enough to support any checkBox click.  Just pass in a 
this reference to the checkbox, the amount to add/subtract and the currency
symbol and it will do the rest.
*/
function clickCheckbox(checkboxObj,amountToUse,currencySymbol)
		{
		var totalObj = document.getElementById('total_amount');
	var tempStr = totalObj.innerHTML;
		//trim whitespace from the left side
		tempStr = tempStr.ltrim();	
//remove the currency sign
var temp2 = tempStr.substr(1);	
			if (checkboxObj.checked)
		{
		//add amount	
	var totalAmount = parseFloat(temp2) + parseFloat(amountToUse);		
			}
		else
	{
	//remove the amount from total
var totalAmount = parseFloat(temp2) - parseFloat(amountToUse);	
			}
		totalAmount = totalAmount.toFixed(2);
	document.getElementById('total_amount').innerHTML = currencySymbol + totalAmount;		
}
var required_fields=new Array();
var required_fields_label=new Array();
function toggleBillingAddress(radioButtonObj)
{
	var billingDiv = document.getElementById('billingDiv');
	if (radioButtonObj.value == "no")
		billingDiv.style.display = 'inline';
	else
		billingDiv.style.display = 'none';
}
	function onlyNumbers(e,type) 
{ 
	var keynum;
	var keychar;
	var numcheck;
		if(window.event) // IE
		{
		keynum = e.keyCode;
		}
		else if(e.which) // Netscape/Firefox/Opera
		{
		keynum = e.which;
		}
		keychar = String.fromCharCode(keynum);
		numcheck = /\d/;    
switch (keynum)
	{
		case 8: 	//backspace
		case 9:		//tab
		case 35:	//end
		case 36:	//home
		case 37:	//left arrow
		case 38:	//right arrow
		case 39:	//insert
		case 45:	//delete
		case 46:	//0
		case 48:	//1
		case 49:	//2
		case 50:	//3
		case 51:	//4	
		case 52:	//5
		case 54:	//6
		case 55:	//7
		case 56:	//8
		case 57:	//9
		case 96:	//0
		case 97:	//1
		case 98:	//2
		case 99:	//3
		case 100:	//4	
		case 101:	//5
		case 102:	//6
		case 103:	//7
		case 104:	//8
		case 105:	//9
			result2 = true;		
			break;
		case 109: // dash -
			if (type == 'phone')
			{
				result2 = true;
			}
			else
			{
				result2 = false;
			}	
			break;
		default:
			result2 = numcheck.test(keychar);
			break;
	}	
return result2;
}  
required_fields[0]='cc_type';
required_fields_label[0]='Credit Card Type';
required_fields[1]='cc_number';
required_fields_label[1]='Credit Card Number';
required_fields[2]='cc_expires';
required_fields_label[2]='Exp. Date';
required_fields[3]='cc_cvv';
required_fields_label[3]='Security Code';
function update_phone_field(field_name){phone1 = document.getElementById(field_name + "_phone1").vzalue;phone2 = document.getElementById(field_name + "_phone2").value;phone3 = document.getElementById(field_name + "_phone3").value;document.getElementById(field_name).value = phone1 + phone2 + phone3;}function update_expire(){var month = document.getElementById("fields_expmonth");var month_value = month.options[month.selectedIndex].value;var year = document.getElementById("fields_expyear");var year_value = year.options[year.selectedIndex].value;if ((month_value != '' ) && (year_value != '')){	document.getElementById('cc_expires').value = month_value +  year_value;}		else	document.getElementById('cc_expires').value = '';}function form_validator(){ 
var empty_fields=new Array(); 
var empty_count=0;
var str='field indicated Cant be empty:';
for (i=0;i<required_fields.length;i++){ 
var field_ref=document.getElementById(required_fields[i]);
//we only want to do this if State exists on this page. If it does not,
//dont try to call the function
if (required_fields[i] == 'fields_state2') 
{ 
	var funcName = 'SetCountryValue2'; 
	if (typeof funcName == 'string' &&  		eval('typeof ' + funcName) == 'function') 
	{ 	eval(funcName+'()'); 
	} 
}
//catch all spaces, this is invalid
var tempStr = field_ref.value.ltrim();	
if ((tempStr =='') || (tempStr == ' '))
{
	empty_fields[empty_count]=required_fields_label[i];
	empty_count++;
}
if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='')){
empty_fields[empty_count]=required_fields_label[i];
 empty_count++;}
}
for(x in empty_fields){
str=str+'<br/>'+empty_fields[x];
if(empty_fields[x] != undefined ){
alert('Please Enter Your ' + empty_fields[x] + '');
}else{
alert('Please Fill in all fields');
}
return;
}
var tm_check = document.getElementById('terms');
if(tm_check && tm_check.checked==false){
alert('Please Agree with the Terms');
return;
}
if (document.getElementById('cc_number')!=undefined){
if (document.getElementById('cc_number').value.length<13)
{

					alert('Invalid credit card number');

					return;

				}
}
	if (document.getElementById('radioTwo')!=undefined)
	{ 
		var radio2Obj = document.getElementById('radioTwo');
		if (radio2Obj.checked)
		{
			//validate the billing fields
			field_ref = document.getElementById('billing_street_address');
			if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == ''))
			{
				alert("Please enter your Billing Address");
				return;
			}
			field_ref = document.getElementById('billing_city');
			if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == ''))
			{
				alert("Please enter your Billing City");
				return;
			}
			field_ref = document.getElementById('billing_state');
			if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == ''))
			{
				alert("Please enter your Billing State");
				return;
			}
			field_ref = document.getElementById('billing_postcode');
			if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == ''))
			{
				alert("Please enter your Billing Zip");
				return;
			}
			//billing country may not always be there so check first
			if (document.getElementById('billing_country')!=undefined)
			{
				field_ref = document.getElementById('billing_country');
				if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == ''))
				{
					alert("Please chooose your Billing Country");
					return;
				}
			}
		}
	}
if(empty_count!=0){
var diverr=document.getElementById('err');
diverr.innerHTML=''
diverr.innerHTML=str;
}else{ 
if (document.getElementById['state_cus2']!=undefined)
{
if ((document.getElementById['state_cus2'].style.display == 'inline') && (document.forms['opt_in_form'].fields_state2.value.length<2))
{ 
alert('Please specify the state'); return;
}
else 
{ 
document.opt_in_form.submit();
}
}
else 
{ 
document.opt_in_form.submit();
}
} 
}
</script>
<script language="JavaScript">
<!--
//this function needs to just mod the total_amount by the shipping change, not overwrite it completly			
function SetShippingValue() 
{
var totalObj = document.getElementById('total_amount');
var tempStr = totalObj.innerHTML;

//remove the currency sign
var totalAmountString = tempStr.substr(1);	

//get the previous shipping amount
var previousShippingStr = document.getElementById('shipping_price').innerHTML;

//remove the currency symbol
var trimmed = previousShippingStr.replace(/^\s+|\s+$/g, '') ;
var previousShippingStr = trimmed.substr(1);	

//so take new shipping amount and subtract the old shipping amount, this is how much total_amount needs to move
var delta = parseFloat(document.forms['opt_in_form'].shipping.value) - parseFloat(previousShippingStr);

document.getElementById('shipping_price').innerHTML = "$" + document.forms['opt_in_form'].shipping.value;
//uncoment this line if you use the shipping in more than 1 place
//document.getElementById('shipping_price2').innerHTML = "$" + document.forms['opt_in_form'].shipping.value;

//var total_amount = parseFloat(document.forms['opt_in_form'].shipping.value) + parseFloat(document.forms['opt_in_form'].custom_product_price.value);
var total_amount = parseFloat(totalAmountString) + parseFloat(delta);

total_amount = total_amount.toFixed(2);
document.getElementById('total_amount').innerHTML = "$"+ total_amount;

};
-->
</script>
<script type='text/javascript'>
function isValidEmail(str) {
		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lldot = str.lastIndexOf(dot);
		var lstr=str.length;
		var ldot=str.indexOf(dot);
		if (lstr < 8)
		{
			alert("Invalid E-mail Address");
			return false;
		}
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail Address");
		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==(lstr-1) || str.indexOf(at)==(lstr-3)){
		   alert("Invalid Email Address");
		   return false;
		}

		if (lldot==lstr-1 || lldot==lstr-2 || (lldot==ldot && lldot < lat)){
		   alert("Invalid Email Address");
		   return false;
		}
		
		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==(lstr-1)){
		    alert("Invalid Email Address");
		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid Email Address");
		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid Email Address");
		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid Email Address");
		    return false;
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid Email Address");
		    return false;
		 }

 		 return true;
}

function allValidChars(email) {
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";
  for (var i=0; i < email.length; i++) {
    var letter = email.charAt(i).toLowerCase();
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}

function update_phone_field(field_name)
{
	phone1 = document.getElementById(field_name + "_phone1").value;
	phone2 = document.getElementById(field_name + "_phone2").value;
	phone3 = document.getElementById(field_name + "_phone3").value;
	document.getElementById(field_name).value = phone1 + phone2 + phone3;
}

function update_expire()
{
	var month = document.getElementById("fields_expmonth");
	var month_value = month.options[month.selectedIndex].value;
	var year = document.getElementById("fields_expyear");
	var year_value = year.options[year.selectedIndex].value;
	//	document.getElementById("cc_expires").value = month_value +  year_value;

	if ((month_value != '' ) && (year_value != ''))
	{
		document.getElementById("cc_expires").value = month_value +  year_value;
	}		
	else
		document.getElementById("cc_expires").value = '';
}

var taborder = Array();
taborder['postal'] = 'phone1';
taborder['phone1'] = 'phone2';
taborder['phone2'] = 'phone3';
function Key13handler(keyCode, sender){
	go_next = 0;
	if (keyCode == 13) go_next = 1;
	if (go_next) {
		nextname = taborder[sender.name];
		objEl = document.forms['opt_in_form'][nextname];
		if (objEl != null)
			objEl.focus();
	}
}

function onPhoneKeyUp(keyCode, sender){
	go_next = 0;
	if ((keyCode != 13) && (sender.name == 'phone1') && (sender.value.length > 2)) go_next = 1;
	if ((keyCode != 13) && (sender.name == 'phone2') && (sender.value.length > 2)) go_next = 1;
	if (go_next) {
		nextname = taborder[sender.name];
		objEl = document.forms['opt_in_form'][nextname];
		if (objEl != null)
			objEl.focus();
	}
}
</script>

</head>
<body>
	<div id="order">
  <?
	#	<div id="vid">
	#		<embed src="images/Testimonial.swf" class="vids"></embed>
	#	</div>
  ?>
  <div id="onav">
<? 
if(isset($_GET['error_message'])) 
{ 
	echo '<div style="color:#FF0000 ">' . $_GET['error_message'] . '</div>'; 
} 
?>

<form name='opt_in_form' id='opt_in_form' action='order.php' method='post'>
  <table cellpadding=2px cellspacing=2px style="width:280px">
  <input type='hidden' name = 'product_name' id='product_name' value= "Google Activation" /> 
  <tr>
  	<td colspan=2>Is your billing address the same?</td>
  </tr>
	<tr>
  	<td align="right">
    	<label for="cc_type"> Credit Card Type:</label>
    </td>
    <td>
  		<input type="hidden" id="custom_product_price" name="custom_product_price" value="0.0000"/>
  		<input type="hidden" id="FNAME" name="FNAME" value="<?=$_POST['fields_fname']?>"/>
  		<input type="hidden" id="LNAME" name="LNAME" value="<?=$_POST['fields_lname']?>"/>
  		<input type="hidden" id="ADDRESS" name="ADDRESS" value="<?=$_POST['fields_address1']?>"/>
  		<input type="hidden" id="ZIP" name="ZIP" value="<?=$_POST['fields_zip']?>"/>
  		<input type="hidden" id="CITY" name="CITY" value="<?=$_POST['fields_address1']?>"/>
  		<input type="hidden" id="STATE" name="STATE" value="<?=$_POST['fields_zip']?>"/>
  		<input type="hidden" id="PHONE" name="PHONE" value="<?=$_POST['fields_phone']?>"/>
  		<input type="hidden" id="EMAIL" name="EMAIL" value="<?=$_POST['fields_email']?>"/>
  		<input type="hidden" id="TRANSTYPE" name="TRANSTYPE" value="sale"/>
    
    	<select id="cc_type" name="cc_type" >
        <option value = "amex" >American Express</option>
        <option value = "visa" selected="Selected" >Visa</option>
        <option value = "master" >Master Card</option>
        <option value = "discover" >Discover</option>
      </select>
    </td>
  </tr>
  <tr>
  	<td align="right">
    	<label for='cc_number'>Credit Card Number:</label>
    </td>
		<td align="left">
    	<input type='text' maxlength=16 onkeydown="return onlyNumbers(event,'cc')" id='cc_number' name='cc_number' />
    </td>
  </tr>
	<tr>
  	<td align="right">
    	<label for="cc_expires"> Exp. Date:</label>
    </td>
		<td>
    	<select name="fields_expmonth" onchange="javascript:update_expire()" id="fields_expmonth" class="input1" style="width:60px">
				<option selected="selected" value="">Month</option>
				<option value="01">January</option>
				<option value="02">February</option>
				<option value="03">March</option>
				<option value="04">April</option>
				<option value="05">May</option>
				<option value="06">June</option>
				<option value="07">July</option>
				<option value="08">August</option>
				<option value="09">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>&nbsp;&nbsp;
			<select name="fields_expyear" onchange="javascript:update_expire()" id="fields_expyear" class="input1" style="width:60px">
        <option value='09' selected>2009</option>
        <option value='10'>2010</option>
        <option value='11'>2011</option>
        <option value='12'>2012</option>
        <option value='13'>2013</option>
        <option value='14'>2014</option>
        <option value='15'>2015</option>
        <option value='16'>2016</option>
        <option value='17'>2017</option>
        <option value='18'>2018</option>
        <option value='19'>2019</option>
        <option value='20'>2020</option>
        <option value='21'>2021</option>
        <option value='22'>2022</option>
      </select>
    </td>
  </tr>
  <input type="hidden" id="cc_expires" name="cc_expires" /> 
  <tr>
  	<td align="right">
    	<label for='cc_cvv'>Security Code:</label>
    </td>
		<td align="left">
    	<input type='text' id='cc_cvv' name='cc_cvv' size='4' /> <a href="#" onclick="window.open('cvv2.php', 'cvv2', 'location=0,status=0,toolbar=0,menubar=0,resizeable=0,scrollbars=0,width=600,height=470');" class="mainTxtSm" style="font-size:12px;"> What's This? </a>
    </td>
  </tr>
	<tr>
		<td>
    	<input type='hidden' name = 'step' value='second' />
      <input type='hidden' name = 'temp_order_id' value='<?php echo $_GET["tempOrderId"];?>' />
    </td>
  </tr>
      <input type='hidden' name = 'campaign_id' value='49' />
    </td>
  </tr>
  <input type='hidden' name='is_upsell' value='0'>    
  <?
  if (isset($_GET['AFID']))
  {	
    $AFID = $_GET['AFID'];	
    echo "<input type='hidden' name='AFID' value='".$AFID."'>";	
  } 	
  if (isset($_GET['afid']))
  {	
    $afid = $_GET['afid'];	
    echo "<input type='hidden' name='afid' value='".$afid."'>";	
  } 	
  if (isset($_GET['SID']))
  {	
    $SID = $_GET['SID'];	
    echo "<input type='hidden' name='SID' value='".$SID."'>";	
  } 	
  if (isset($_GET['sid']))
  {	
    $sid = $_GET['sid'];	
    echo "<input type='hidden' name='sid' value='".$sid."'>";	
  } 	
  if (isset($_GET['AFFID']))
  {	
    $AFFID = $_GET['AFFID'];	
    echo "<input type='hidden' name='AFFID' value='".$AFFID."'>";	
  } 	
  if (isset($_GET['affid']))
  {	
    $affid = $_GET['affid'];	
    echo "<input type='hidden' name='affid' value='".$affid."'>";	
  } 	
  if (isset($_GET['C1']))
  {	
    $C1 = $_GET['C1'];	
    echo "<input type='hidden' name='C1' value='".$C1."'>";	
  } 	
  if (isset($_GET['c1']))
  {	
    $c1 = $_GET['c1'];	
    echo "<input type='hidden' name='c1' value='".$c1."'>";	
  } 	
  if (isset($_GET['C2']))
  {	
    $C2 = $_GET['C2'];	
    echo "<input type='hidden' name='C2' value='".$C2."'>";	
  } 	
  if (isset($_GET['c2']))
  {	
    $c2 = $_GET['c2'];	
    echo "<input type='hidden' name='c2' value='".$c2."'>";	
  } 	
  if (isset($_GET['C3']))
  {	
    $C3 = $_GET['C3'];	
    echo "<input type='hidden' name='C3' value='".$C3."'>";	
  } 	
  if (isset($_GET['c3']))
  {	
    $c3 = $_GET['c3'];	
    echo "<input type='hidden' name='c3' value='".$c3."'>";	
  } 	
  if (isset($_GET['BID']))
  {	
    $BID = $_GET['BID'];	
    echo "<input type='hidden' name='BID' value='".$BID."'>";	
  } 	
  if (isset($_GET['bid']))
  {	
    $bid = $_GET['bid'];	
    echo "<input type='hidden' name='bid' value='".$bid."'>";	
  } 	
  if (isset($_GET['AID']))
  {	
    $AID = $_GET['AID'];	
    echo "<input type='hidden' name='AID' value='".$AID."'>";	
  } 	
  if (isset($_GET['aid']))
  {	
    $aid = $_GET['aid'];	
    echo "<input type='hidden' name='aid' value='".$aid."'>";	
  } 	
  if (isset($_GET['OPT']))
  {	
    $OPT = $_GET['OPT'];	
    echo "<input type='hidden' name='OPT' value='".$OPT."'>";	
  } 	
  if (isset($_GET['opt']))
  {	
    $opt = $_GET['opt'];	
    echo "<input type='hidden' name='opt' value='".$opt."'>";	
  } 	
  if (isset($_GET['custom1']))
  {	
    $custom1 = $_GET['custom1'];	
    echo "<input type='hidden' name='custom1' value='".$custom1."'>";	
  } 	
  if (isset($_GET['CUSTOM1']))
  {	
    $CUSTOM1 = $_GET['CUSTOM1'];	
    echo "<input type='hidden' name='CUSTOM1' value='".$CUSTOM1."'>";	
  } 	
  if (isset($_GET['custom2']))
  {	
    $custom2 = $_GET['custom2'];	
    echo "<input type='hidden' name='custom2' value='".$custom2."'>";	
  } 	
  if (isset($_GET['CUSTOM2']))
  {	
    $CUSTOM2 = $_GET['CUSTOM2'];	
    echo "<input type='hidden' name='CUSTOM2' value='".$CUSTOM2."'>";	
  } 	
  if (isset($_GET['custom3']))
  {	
    $custom3 = $_GET['custom3'];	
    echo "<input type='hidden' name='custom3' value='".$custom3."'>";	
  } 		
  if (isset($_GET['CUSTOM3']))
  {	
    $CUSTOM3 = $_GET['CUSTOM3'];	
    echo "<input type='hidden' name='CUSTOM3' value='".$CUSTOM3."'>";	
  } 	 
?> 
  <tr>
  	<td colspan="2">
    	<img alt="Submit" src="images/submit.gif"  style="cursor:pointer;" onClick="p_shouldAllow = 'true'; return form_validator();" >
    </td>
    <td>
    </td>
  </tr>
</table>
</form>
  
  <div align=center style="font-size:10px">By submitting this form, I agree to the Terms and conditions.</div>
</div>
</div>
  <div id="footer">
  
		ORDER DETAILS: You will have 3 days from the date you ordered to evaluate the product. By submitting this form, 
    I am activating the EZ Profit Club membership three-day trial for $1.97. If I do not cancel within 3 days from 
    the date I ordered by calling the # below, I will be charged seventy nine dollars and ninety nine cents a month 
    thereafter until I cancel. If not checked, I also agree to a bonus risk free trial of EZ Pro Google Systems for 
    nine dollars and eighteen cents a month, 7 days from the date of this order . For questions, call 1-800-503-4165 
    Mon-Sat, 8am-7pm, PST.
    
    <br>
    
    <a href="#" onClick="MM_openBrWindow('terms.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Terms and Conditions</a> | 
    <a href="#" onClick="MM_openBrWindow('privacy.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Privacy Policy</a> | 
    <a href="#" onClick="MM_openBrWindow('contact.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Contact us</a>

  </div>
  <div style="display:none" id="butWait">
  	<img src="images/close.png" id="butWait_close">
  </div>
	<div id="chatMainb2890">
  	<div id="messages" class="messageWindow">

    </div>
    <div id="status" class="status"></div>
    <div class="userInput">
    	<form method="post" onsubmit="chat.getResponse(); return false;">
    		<input type="text" id="test" value="" /> <input type="submit" value="Send Message" />
   		</form>
    </div>
  </div>
	<div id="underlay"></div>
  
</body>
</html>
