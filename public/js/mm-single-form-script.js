<!--

function mm_focus_bg(e) {
	e.style.backgroundColor='#ff0';
}

function mm_blur_bg(e) {
	e.style.backgroundColor='#fff';
}

function mm_keyup_focus(e,i,n) {
	if(e.value.length>i) document.getElementById(n).focus();
}

function mm_toggle_pane(e,d) {
	document.getElementById(e).style.display=d;
}

//validation function blocks

function mm_validate_contact(f) {
	f=document.getElementById(f);
	if (!mm_check_bill_first(f)) return false;
	if (!mm_check_bill_last(f)) return false;
	if (!mm_check_bill_address(f)) return false;
	if (!mm_check_bill_zip(f)) return false;
	if (!mm_check_bill_phone(f)) return false;
	if (!mm_check_bill_email(f)) return false;
	mm_toggle_pane('mm-contact-pane','none');
	mm_toggle_pane('mm-loading-pane','block');
	mm_POSTOrder(f,1);
}

function mm_validate_billing(f) {
	f=document.getElementById(f);
	if (!mm_check_card_number(f)) return false;
	if (!mm_check_expires_mm(f)) return false;
	if (!mm_check_expires_yy(f)) return false;
	if (!mm_check_cvv_code(f)) return false;
	mm_toggle_pane('mm-billing-pane','none');
	if (document.getElementById('mm-shipping-pane')) {
		mm_toggle_pane('mm-shipping-pane','block');
	}
	else {
		mm_toggle_pane('mm-loading-pane','block');
		mm_POSTOrder(f,2);
	}
}

function mm_validate_shipping(f) {
	f=document.getElementById(f);
	if (!mm_check_ship_id(f)) return false;
	if (!mm_check_shipping(f)) return false;
	mm_toggle_pane('mm-shipping-pane','none');
	mm_toggle_pane('mm-loading-pane','block');
	mm_POSTOrder(f,2);
}

//checks on input values

//checks on contact fields
function mm_check_bill_first(f) {
	var e=f.elements['mm-bill_first'];
	if (mm_test_empty(e,'First Name')) return false;
	if (mm_test_ereg(e,'First Name',/^[A-Za-z\s\'\`\.\-]+$/,'"A" to "Z", space, hyphen, period, tilda, and apostrophe.'))
		return false; return true;
}

function mm_check_bill_last(f) {
	var e=f.elements['mm-bill_last'];
	if (mm_test_empty(e,'Last Name')) return false;
	if (mm_test_ereg(e,'Last Name',/^[A-Za-z\s\'\`\.\-]+$/,'"A" to "Z", space, hyphen, period, tilda, and apostrophe.'))
		return false; return true;
}

function mm_check_bill_address(f) {
	var e=f.elements['mm-bill_address'];
	if (mm_test_empty(e,'Home Address')) return false;
	if (mm_test_ereg(e,'Home Address',/^[0-9A-Za-z\s\'\`\.\#\-]+$/,'"0" to "9", "A" to "Z", space, hyphen, period, tilda, pound, and apostrophe.'))
		return false; return true;
}

function mm_check_bill_zip(f) {
	var e=f.elements['mm-bill_zip'];
	if (mm_test_empty(e,'Zip Code')) return false;
	if (mm_test_ereg(e,'Zip Code',/^[0-9A-Za-z\s\-]+$/,'"0" to "9", "A" to "Z", space, and hyphen.'))
		return false; return true;
}

function mm_check_bill_phone(f) {
	var e;
	e=f.elements['mm-bill_phone_2'];
	if (mm_test_empty(e,'Phone')) return false;
	if (mm_test_length(e,'Phone',3)) return false;
	if (mm_test_ereg(e,'Phone',/^[0-9]+$/,'"0" to "9".')) return false;
	e=f.elements['mm-bill_phone_3'];
	if (mm_test_empty(e,'Phone')) return false;
	if (mm_test_length(e,'Phone',3)) return false;
	if (mm_test_ereg(e,'Phone',/^[0-9]+$/,'"0" to "9".')) return false;
	e=f.elements['mm-bill_phone_4'];
	if (mm_test_empty(e,'Phone')) return false;
	if (mm_test_length(e,'Phone',4)) return false;
	if (mm_test_ereg(e,'Phone',/^[0-9]+$/,'"0" to "9".')) return false;
	return true;
}

function mm_check_bill_email(f) {
	var e=f.elements['mm-bill_email'];
	if (mm_test_empty(e,'eMail'))
		return false;
	if (mm_test_email(e))
		return false; return true;
}


//checks on billing fields
function mm_check_card_number(f) {
	var e=f.elements['mm-card_number'];
	if (mm_test_empty(e,'Card Number'))
		return false;
	if (mm_test_length(e,'Card Number',12))
		return false;
	if (mm_test_ereg(e,'Card Number',/^[0-9]+$/,'"0" to "9".'))
		return false; return true;
}

function mm_check_expires_mm(f) {
	var e=f.elements['mm-expires_mm'];
	if (mm_test_empty(e,'Card Expiration (Month)'))
		return false; return true;
}

function mm_check_expires_yy(f) {
	var e=f.elements['mm-expires_yy'];
	if (mm_test_empty(e,'Card Expiration (Year)'))
		return false; return true;
}

function mm_check_cvv_code(f) {
	var e=f.elements['mm-cvv_code'];
	if (mm_test_empty(e,'Security Code'))
		return false;
	if (mm_test_length(e,'Security Code',3))
		return false;
	if (mm_test_ereg(e,'Security Code',/^[0-9]+$/,'"0" to "9".'))
		return false; return true;
}


//checks on shipping fields
function mm_check_ship_id(f) {
	var e=f.elements['mm-ship_id'];
	if (mm_test_empty(e,'Shipping Type'))
		return false; return true;
}

function mm_check_shipping(f) {
	var e=f.elements['mm-same_as_billing'];
	if (e.checked==false ) {
		e=f.elements['mm-ship_address'];
		if (mm_test_empty(e,'Shipping Address')) return false;
		if (mm_test_ereg(e,'Shipping Address',/^[0-9A-Za-z\s\'\`\.\#\-]+$/,'"0" to "9", "A" to "Z", space, hyphen, period, tilda, pound, and apostrophe.')) return false;
		e=f.elements['mm-ship_zip'];
		if (mm_test_empty(e,'Shipping Zip')) return false;
		if (mm_test_ereg(e,'Shipping Zip',/^[0-9A-Za-z]+$/,'"0" to "9", and "A" to "Z".')) return false;
		return true;
	}
}


//tests run on checks
function mm_test_empty(e,n) {
	if (e.value=='') {
		alert('Please enter your "'+n+'".');
		e.focus(); return true;
	} return false;
}

function mm_test_length(e,n,c) {
	if (e.value.length<c) {
		alert('Please enter your full "'+n+'".');
		e.focus(); return true;
	} return false;
}

function mm_test_ereg(e,n,p,a) {
	if (p.test(e.value)) return false;
	alert('You have entered invalid characters for your "'+n+'". Valid characters are '+a);
	e.focus(); return true;
}

function mm_test_email(e) {
	var p=/^[0-9A-Za-z\_\#\.\-]+@[0-9A-Za-z\.\-]+\.[A-Za-z]{2,4}$/; 
	if (p.test(e.value)) return false;
	alert('Your "eMail" address is invalid. Please enter a valid email.');
	e.focus(); return true;
}


//AJAX

function mm_GetXmlHttpObject() {
   if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      return new XMLHttpRequest();
   }
   if (window.ActiveXObject) {
      // code for IE6, IE5
      return new ActiveXObject("Microsoft.XMLHTTP");
   }
   return null;
}

function mm_POSTOrder(f,s) {
	var xmlhttp=mm_GetXmlHttpObject();
	if (xmlhttp==null) {
		alert ("Your browser does not support XMLHTTP!");
		return;
	}

	var post=''; var i;
	for (i=0;i<f.elements.length;++i) {
		if (f.elements[i].type == 'button') continue;
		if (f.elements[i].type == undefined) continue;
		post += f.elements[i].name+"="+f.elements[i].value+"&\n";
	}
	
	var url='http://www.proleroinc.com/order';
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var response=xmlhttp.responseText;
			mm_toggle_pane('mm-loading-pane','none');
			if (s == 1) {
				if (document.getElementById('mm-billing-pane')) {
					var p = /^[0-9]{7,8}$/;
					if (p.test(response)) {
						document.getElementById('mm-ordr_id').value=response;
						mm_toggle_pane('mm-billing-pane','block');
					}
					else {
						mm_toggle_pane('mm-response-pane','block');
						document.getElementById('mm-response').innerHTML='ERROR';
						document.getElementById('mm-response-message').innerHTML=response;
					}
				}
				else {
					mm_toggle_pane('mm-response-pane','block');
					document.getElementById('mm-response').innerHTML='Thank You!';
					document.getElementById('mm-response-message').innerHTML=
					'Your reference number is '+response+'.';
				}
				return true;
			}
			if (s == 2) {
				switch (response) {
					case 'DECLINE':
					document.getElementById('mm-response').innerHTML='DECLINED';
					document.getElementById('mm-response-message').innerHTML=
						'<a onclick="mm_back_to_billing();" href="javascript:">Your credit card has been declined. Please click here, to go back and make sure you\'ve entered the correct card information, or use a different card to complete your purchase.</a>';
					break;
					case 'ERROR':
					document.getElementById('mm-response').innerHTML='ERROR';
					document.getElementById('mm-response-message').innerHTML=
						'<a onclick="mm_back_to_billing();" href="javascript:">An error has occured with the Payment Processor. Please click here, to go back and try again.</a>';				
					break;
					case 'SALE':
					document.getElementById('mm-response').innerHTML='Thank You!';
					document.getElementById('mm-response-message').innerHTML=
						'Your transaction was successful, and the reference number for your purchase is '+document.getElementById('mm-ordr_id').value+'.';
					break;
					default:
					document.getElementById('mm-response').innerHTML='ERROR';
					document.getElementById('mm-response-message').innerHTML=response;
					break;
				}
				mm_toggle_pane('mm-response-pane','block');
				return true;
			}
		}
	}

	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", post.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(post);
}

function mm_back_to_billing() {
	mm_toggle_pane('mm-billing-pane','block');
	mm_toggle_pane('mm-response-pane','none');
}

function mm_back_to_contact() {
	mm_toggle_pane('mm-contact-pane','block');
	mm_toggle_pane('mm-response-pane','none');
}

-->