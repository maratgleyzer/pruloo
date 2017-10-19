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

function mm_validate_contact(f,c) {
	f=document.getElementById(f);
	if (!mm_check_bill_first(f)) return false;
	if (!mm_check_bill_last(f)) return false;
	if (!mm_check_bill_address(f)) return false;
	if (!mm_check_bill_zip(f)) return false;
	if (!mm_check_bill_phone(f)) return false;
	if (!mm_check_bill_email(f)) return false;
	mm_toggle_pane('mm-contact-pane','none');
	mm_toggle_pane('mm-loading-pane','block');
	mm_POSTOrder(f,1,c);
}

function mm_validate_billing(f,c) {
	f=document.getElementById(f);
	if (!mm_check_card_number(f)) return false;
	if (!mm_check_expires_mm(f)) return false;
	if (!mm_check_expires_yy(f)) return false;
	if (!mm_check_cvv_code(f)) return false;
	if (!mm_verify_valid_card(f)) return false;
	mm_toggle_pane('mm-billing-pane','none');
	if (document.getElementById('mm-shipping-pane')) {
		mm_toggle_pane('mm-shipping-pane','block');
	}
	else {
		mm_toggle_pane('mm-loading-pane','block');
		mm_POSTOrder(f,2,c);
	}
}

function mm_validate_shipping(f) {
	f=document.getElementById(f);
	if (!mm_check_ship_id(f)) return false;
	if (!mm_check_shipping(f)) return false;
	mm_toggle_pane('mm-shipping-pane','none');
	mm_toggle_pane('mm-loading-pane','block');
	mm_POSTOrder(f,2,false);
}

function mm_validate_upsell(f) {
	f=document.getElementById(f);
	mm_toggle_pane('mm-upsell-pane','none');
	mm_toggle_pane('mm-loading-pane','block');
	mm_POSTOrder(f,3,false);
}

function mm_kill_cookie() {
	document.cookie='mm-ordr_id=; expires=Fri, 31 Dec 1999 00:00:00 UTC; path=/';
}

//checks on input values

//checks on contact fields
function mm_check_bill_first(f) {
	var e=f.elements['mm-bill_first'];
	if (mm_test_empty(e,'First Name')) return false;
	if (mm_test_ereg(e,'First Name',/^[A-Za-z\s\'\`\.\,\-]+$/,'"A" to "Z", space, hyphen, period, tilda, and apostrophe.'))
		return false; return true;
}

function mm_check_bill_last(f) {
	var e=f.elements['mm-bill_last'];
	if (mm_test_empty(e,'Last Name')) return false;
	if (mm_test_ereg(e,'Last Name',/^[A-Za-z\s\'\`\.\,\-]+$/,'"A" to "Z", space, hyphen, period, tilda, and apostrophe.'))
		return false; return true;
}

function mm_check_bill_address(f) {
	var e=f.elements['mm-bill_address'];
	if (mm_test_empty(e,'Home Address')) return false;
	if (mm_test_ereg(e,'Home Address',/^[0-9A-Za-z\s\'\`\.\,\/\#\-]+$/,'"0" to "9", "A" to "Z", space, hyphen, period, tilda, pound, and apostrophe.'))
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
	if (mm_test_length(e,'Card Number',15))
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

function mm_verify_valid_card(f) {
	var t=f.elements['mm-card_type'];
	var n=f.elements['mm-card_number'];
	var c=f.elements['mm-cvv_code'];

	switch (t.selectedIndex) {
	case 0:
		var p=/^4[0-9]{15}$/;
		if (n.value.length!=16 || !p.test(n.value)) {
			alert('You have entered an invalid "VISA" card number. Please verify the number and try again, or try with different card.');
			n.focus(); return false;
		} break;
	case 1:
		var p=/^5[1-5][0-9]{14}$/;
		if (n.value.length!=16 || !p.test(n.value)) {
			alert('You have entered an invalid "MASTERCARD" card number. Please verify the number and try again, or try with different card.');
			n.focus(); return false;
		} break;
	case 2:
		var p=/^3[47][0-9]{13}$/;
		if (n.value.length!=15 || !p.test(n.value)) {
			alert('You have entered an invalid "AMERICAN EXPRESS" card number. Please verify the number and try again, or try with different card.');
			n.focus(); return false;
		} break;
	case 3:
		var p=/^6(?:011|5[0-9]{2})[0-9]{12}$/;
		if (n.value.length!=16 || !p.test(n.value)) {
			alert('You have entered an invalid "DISCOVER CARD" card number. Please verify the number and try again, or try with different card.');
			n.focus(); return false;
		} break;
	}
	
	switch (t.selectedIndex) {
	case 2:
		if (c.value.length!=4) {
			alert('You have entered an invalid "SECURITY CODE" for this card number. Please verify the code and try again, or try with different card.');
			c.focus(); return false;
		} break;
	default:
		if (c.value.length!=3) {
			alert('You have entered an invalid "SECURITY CODE" for this card number. Please verify the code and try again, or try with different card.');
			c.focus(); return false;
		} break;
	}
	
	return true;
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

function mm_POSTOrder(f,s,c) {
	var post=''; var get=''; var i;
	var xmlhttp=mm_GetXmlHttpObject();
	if (xmlhttp==null) {
		alert ("Your browser does not support XMLHTTP!");
		return;
	}

	var oid = 0;
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var a = ca[i];
		oid=a.substring(a.indexOf('mm-ordr_id')+11,a.length);
	}

	if (oid > 0) {
		post+="mm-ordr_id="+oid+"&";
		get+="mm-ordr_id="+oid+"&";
	}
	
	var uri=window.location.toString();
	
	var query=uri.match(/\?(.+)/);

	if (query) {
		post+=query[1];
		get+=query[1];
	}

	for (i=0;i<f.elements.length;++i) {
		if (f.elements[i].type=='button') continue;
		if (f.elements[i].type==undefined) continue;
		post+=f.elements[i].name+"="+f.elements[i].value+"&";
	}

	var url='/proxy.php';
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var response=xmlhttp.responseText;
			mm_toggle_pane('mm-loading-pane','none');
			if (s == 1) {
				var p = /^[0-9]{7,8}$/;
				if (p.test(response)) {
					document.cookie='mm-ordr_id='+response+'; expires=Fri, 31 Dec 2010 00:00:00 UTC; path=/';
					if (c > 0) { window.location="order.php?mm-ordr_id="+response+"&"+get; }
					else { window.location="thanks.php?mm-ordr_id="+response+"&"+get; }
				}
				else {
					mm_toggle_pane('mm-response-pane','block');
					document.getElementById('mm-response-message').innerHTML=response;
				}
				return true;
			}
			if (s == 2) {
				switch (response) {
					case 'DECLINE':
					document.getElementById('mm-response-message').innerHTML=
						'<a onclick="mm_back_to_billing();" href="javascript:">Your credit card has been declined. Please click here, to go back and make sure you\'ve entered the correct card information, or use a different card to complete your purchase.</a>';
					break;
					case 'ERROR':
					document.getElementById('mm-response-message').innerHTML=
						'<a onclick="mm_back_to_billing();" href="javascript:">An error has occured with the Payment Processor. Please click here, to go back and try again.</a>';				
					break;
					case 'SALE':
					window.location="thanks.php?"+get;
					return true;
					break;
					case 'UPSELL':
					window.location="thanks.php?"+get;
					return true;
					break;
					default:
					document.getElementById('mm-response-message').innerHTML=response;
					break;
				}
				mm_toggle_pane('mm-response-pane','block');
				return true;
			}
			if (s == 3) {
				switch (response) {
					case 'DECLINE':
					document.getElementById('mm-response-message').innerHTML=
						'Your credit card has been declined for this other transaction.';
					break;
					case 'ERROR':
					document.getElementById('mm-response-message').innerHTML=
						'An error has occured with the Payment Processor.';				
					break;
					case 'SALE':
					window.location="thanks.php?"+get;
					return true;
					break;
					case 'UPSELL':
					window.location="upsell.php?"+get;
					return true;
					break;
					default:
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

function mm_start_card(t) {
	switch (t.selectedIndex) {
	case 0: document.getElementById('mm-card_number').value='4'; break;
	case 1: document.getElementById('mm-card_number').value='5'; break;
	case 2: document.getElementById('mm-card_number').value='3'; break;
	case 3: document.getElementById('mm-card_number').value='6'; break;
	}
}

function mm_back_to_billing() {
	mm_toggle_pane('mm-billing-pane','block');
	mm_toggle_pane('mm-response-pane','none');
}

function mm_back_to_contact() {
	mm_toggle_pane('mm-contact-pane','block');
	mm_toggle_pane('mm-response-pane','none');
}

function mm_kill_cookie() {
	document.cookie='mm-ordr_id=; expires=Fri, 31 Dec 1999 00:00:00 UTC; path=/';	
}

-->