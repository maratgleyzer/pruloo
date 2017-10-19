function viewRecord(a,id) {
   xmlhttp=GetXmlHttpObject();
   if (xmlhttp==null) {
      alert ("Your browser does not support XMLHTTP!");
      return;
   }

   var url="/console/"+a+"/view/"+id;
   var e = a+'_'+id;
   xmlhttp.onreadystatechange=function() {
	   if (xmlhttp.readyState==4) {
		   document.getElementById(e).innerHTML=xmlhttp.responseText;
	   }
	   else { document.getElementById(e).innerHTML = 'Fail'; }
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