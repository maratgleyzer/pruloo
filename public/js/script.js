<!--

function center(f){
	var clientWidth = 0, clientHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
		clientWidth = window.innerWidth;
		clientHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		clientWidth = document.documentElement.clientWidth;
		clientHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		clientWidth = document.body.clientWidth;
		clientHeight = document.body.clientHeight;
	}

	document.getElementById(f).style.left = ((clientWidth/2 -300)) + 'px';
	document.getElementsByTagName('body')[0].style.overflowX = 'hidden';
	document.getElementById(f).style.top = '0px';
}

function showForm(f){
	document.body.style.overflow='hidden';
	document.getElementById(f).style.top = window.pageYOffset+'px';
	document.getElementById(f).style.display = 'block';
	document.getElementById('modal').style.top = window.pageYOffset+'px';
	document.getElementById('modal').style.display = 'block';
}

function hideForm(f){
	document.body.style.overflow='auto';
	document.getElementById(f).style.display = 'none';
	document.getElementById('modal').style.display = 'none';
}

function init(){
	if (document.getElementById('save-pane')) center('save-pane');
	if (document.getElementById('save-pane-channel')) center('save-pane-gateway');
	if (document.getElementById('save-pane-channel')) center('save-pane-shipping');
}

function addOpt(t,v,s){
	if (v == '0') return false;
	
	var i;
	var l = s.options.length;
	
	var sv = document.getElementById('variety_'+s.name.substr(0,(s.name.length -2)));
	
	for (i = 0; i < sv.options.length; i++)
		sv.options[i].selected = false;
	
	for (i = 0; i < l; i++){
		if (s.options[i].value == v)
			return false;
	}

	var opt = new Option(t,v);
	s.options[l] = opt;
}

function selAll(s){
	var i;
	
	var l = s.options.length;

	for (i = 0; i < l; i++)
		s.options[i].selected = true;
}

function $()
{
	var elements = new Array();
	for (var i = 0; i < arguments.length; i++)
	{
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1)
			return element;
		elements.push(element);
	}
	return elements;
}

function viewRecord(a,id) {

   var e = document.getElementById(a+"_"+id);
   if (e.innerHTML != '') { e.innerHTML = ''; return true; }
   
   e.innerHTML = '<img src="/img/dots32.gif" alt="" style="border:0;margin-left:3px;" />';

   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
      alert ("Your browser does not support XMLHTTP!");
      return;
   }

   var url="/console/"+a+"/view/"+id;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   e.innerHTML=xmlhttp.responseText;
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function AffiliateReport(id) {

   var e = document.getElementById("user_"+id);
   if (e.innerHTML != '') { e.innerHTML = ''; return true; }
   
   e.innerHTML = '<img src="/img/dots32.gif" alt="" style="border:0;margin-left:3px;" />';

   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
      alert ("Your browser does not support XMLHTTP!");
      return;
   }

   var url="/console/report/user/"+id;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   e.innerHTML=xmlhttp.responseText;
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}


function addNote(id) {

   var e = document.getElementById('save-pane');
   e.innerHTML = '<img src="/img/loader64.gif" alt="" style="border:0;margin-left:3px;" />';

   showForm('save-pane');

   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
      alert ("Your browser does not support XMLHTTP!");
      return;
   }

   var url="/console/order/notate/"+id;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   e.innerHTML=xmlhttp.responseText;
		   return false;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doRefund(oid,pid) {
   
   document.getElementById('modal').style.display='block';
	
   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/refund/oid/"+oid;
   if (pid > 0) url+="/pid/"+pid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doCancel(oid) {
   
   document.getElementById('modal').style.display='block';
	
   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/cancel/oid/"+oid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doActivate(oid) {
	   
   document.getElementById('modal').style.display='block';
		
   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/activate/oid/"+oid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doResend(oid) {
	   
   document.getElementById('modal').style.display='block';
		
   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/resend/oid/"+oid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doRetry(oid) {

   document.getElementById('modal').style.display='block';

   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/retry/oid/"+oid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function doFraud(oid) {

   document.getElementById('modal').style.display='block';

   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
	   alert ("Your browser does not support XMLHTTP!");
	   return;
   }

   var url="/order/fraud/oid/"+oid;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   alert(xmlhttp.responseText);
		   window.location='/console/order';
		   return true;
	   }
   }

   xmlhttp.open("GET",url,true);
   xmlhttp.send(null);
}

function GetXmlHttpObject() {
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

function show_per_page(i, v) {
         for (i = 0; i < f.length; ++i) {
          if (f.elements[i].name == 'esrs_select_limit') {
            f.elements[i].value = v;}}
         f.esrs_page_limit.value = v;
         f.submit();}

function go_to_page(v) {
         for (i = 0; i < f.length; ++i) {
          if (f.elements[i].name == 'esrs_select_start') {
            f.elements[i].value = v;}}
         f.esrs_page_start.value = v;
         f.submit();}

-->