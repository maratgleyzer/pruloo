<?

	require_once('_.php'); 
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Make Money Online!</title>


  <script type="text/javascript" src="http://o.aolcdn.com/dojo/1.1.1/dojo/gfx-dojo.xd.js"></script>
  <!--<script type="text/javascript" src="../o/dojotoolkit/dojo/dojo.js"></script>
  <script type="text/javascript" src="../o/dojotoolkit/dojox/gfx.js"></script>-->
  <script type="text/javascript" src="dialog.js"></script>

  
  
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) 
{ //v2.0
	window.open(theURL,winName,features);
}
//-->
</script> 


<script type="text/javascript">
var PrulooTimer = function ()
{
  this.init = function(name){
  this.minutes = 10;
  this.seconds = 0;
  this.alertMessage = 'TIMES UP, ORDER NOW!';
  this.name = name; };
  
  this.setLocation = function(url){
  this.redirect = true;
  this.url = url;
  };
  
  this.setMin = function(minutes){
  this.minutes = minutes;
  };
  
  this.setSec = function (seconds){
  this.seconds = seconds;
  };
  this.countSeconds = function(){

  if(this.seconds != 0){
    this.seconds--;
    if(this.seconds < 10){
    var message = "Hurry! ACT IN " + this.minutes + ':0' + this.seconds + " MINUTES!";
    	document.getElementById('Clock').innerHTML = message;
    } else{
    var message = "Hurry! ACT IN " + this.minutes + ':' + this.seconds + " MINUTES!";
    	document.getElementById('Clock').innerHTML = message;
    }
    	setTimeout(this.name+".countSeconds()",1000);
    } else{
      this.seconds = 60;
      this.countDown();
    }
  };
	this.countDown = function(){

  var target = document.getElementById('Clock');
  if(target.style.display == 'none'){
	return;
}
if(this.minutes != 0){
  this.minutes--;
  //reset seconds
  this.seconds = 60;
  this.countSeconds();
  //setTimeout("countSeconds",1000);
  } else{
  target.style.textDecoration = 'blink';
  if(this.redirect == true){
  	document.location = this.url;
  }
  	alert(this.alertMessage);
  }
};

  this.setAlertMessage = function(msg)
  {
  	this.alertMessage = msg;
  };

}
var timer = new PrulooTimer;
  timer.init('timer');
  timer.setMin(5);
  timer.setAlertMessage('Time is up, order now!');
//timer.setLocation('http://www.google.com');
</script>

<script type='text/javascript'>
		String.prototype.ltrim = function() {
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
  required_fields[0]='fields_fname';
  required_fields_label[0]='First Name';
  required_fields[1]='fields_lname';
  required_fields_label[1]='Last Name';
  required_fields[2]='fields_address1';
  required_fields_label[2]='Street Address';
  required_fields[3]='fields_zip';
  required_fields_label[3]='Zip Code';
  required_fields[4]='fields_phone';
  required_fields_label[4]='Phone';
  required_fields[5]='fields_email';
  required_fields_label[5]='Email';
  
function update_phone_field(field_name)
{
	phone1 = document.getElementById(field_name + "_phone1").value;phone2 = document.getElementById(field_name + "_phone2").value;phone3 = document.getElementById(field_name + "_phone3").value;document.getElementById(field_name).value = phone1 + phone2 + phone3;
}

function update_expire()
{
  var month = document.getElementById("fields_expmonth");
  var month_value = month.options[month.selectedIndex].value;
  var year = document.getElementById("fields_expyear");
  var year_value = year.options[year.selectedIndex].value;
  if ((month_value != '' ) && (year_value != ''))
  {	
  	document.getElementById('cc_expires').value = month_value +  year_value;
  }		
  else document.getElementById('cc_expires').value = '';
  }
  
  function form_validator()
  {  
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
  	{ 	
    	eval(funcName+'()'); 
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
    if(tm_check && tm_check.checked==false)
    {
    	alert('Please Agree with the Terms');
    	return;
  	}
  if (document.getElementById('cc_number')!=undefined) {
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
  			if((field_ref.length==0)||(field_ref.value==null) || (field_ref.value=='') || (field_ref.value.ltrim() == '') )
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
    if(isValidEmail(document.forms['opt_in_form'].fields_email.value))
    {
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
    else
    {
    	//alert('Invalid Email Address');
    }
  } 
}
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

	<link rel="stylesheet" href="style.css" type="text/css" />

</head>

<body onload="timer.countDown();">
	<div id="main">
  	<div id="nav">
  	  <div align=center id="Clock">
      	10:00
      </div>
      
      <form name='opt_in_form' id='opt_in_form' action='order.php' method='post'>
        <table cellpadding=2px cellspacing=2px style="width:400px">
          <input type='hidden' name = 'product_name' id='product_name' value= "Google Activation" />
          <tr>
            <td align="right">
            	<label for='fields_fname'>First Name:</label>
            </td>
        		<td align="left">
        			<input type='text' id='fields_fname' name='fields_fname' />
        		</td>
        	</tr>
        	<tr>
        		<td align="right">
        			<label for='fields_lname'>Last Name:</label>
        		</td>
        		<td align="left">
        			<input type='text' id='fields_lname' name='fields_lname' />
        		</td>
        	</tr>
        	<tr>
        		<td align="right">
        			<label for='fields_address1'>Street Address:</label>
        		</td>
        		<td align="left">
        			<input type='text' id='fields_address1' name='fields_address1' />
        		</td>
        	</tr>
        	<tr>
        		<td align="right">
        			<label for='fields_zip'>Zip Code:</label>
        		</td>
        		<td align="left">
        			<input type='text' id='fields_zip' name='fields_zip' />
        		</td>
        	</tr>
            <tr>
        		<td align="right">
        			<label for='fields_phone'>Phone:</label>
        		</td>
            <td align="left">
              <input name="phone1" id="fields_phone_phone1" onchange="update_phone_field('fields_phone')" size="2" maxlength="3" value="" type="text">
              <input name="phone2" id="fields_phone_phone2" onchange="update_phone_field('fields_phone')" size="2" maxlength="3" value="" type="text">
              <input name="phone3" id="fields_phone_phone3" onchange="update_phone_field('fields_phone')" size="3" maxlength="4" value="" type="text">
        			<input type="hidden" onkeydown="return onlyNumbers(event,'phone')" id="fields_phone" name="fields_phone" />
        		</td>
        	</tr>
        	<tr>
        		<td align="right">
        			<label for='fields_email'>Email:</label>
        		</td>
        		<td align="left">
        			<input type='text' id='fields_email' name='fields_email' />
        		</td>
        	</tr>
          <tr>
            <td>
              <input type='hidden' name = 'step' value='first' />
              <input type='hidden' name = 'campaign_id' value='48' />
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
              <img alt="Submit" src="images/submit.gif"  style="cursor:pointer;" onClick="return form_validator();" ></td><td>
            </td>
          </tr>
        </table>
      
      </form>
  	</div>
  </div>
  <div id="footer">
  	<a href="#" onClick="MM_openBrWindow('terms.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Terms and Conditions</a> | <a href="#" onClick="MM_openBrWindow('privacy.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Privacy Policy</a> | <a href="#" onClick="MM_openBrWindow('contact.html','','scrollbars=yes, resizable=yes,width=450,height=500')">Contact us</a>
  </div>
  <div style="display:none" id="butWait">
   	<img src="images/close.png" id="butWait_close">
	</div>
</body>
</html>