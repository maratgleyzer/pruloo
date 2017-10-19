<?php
header("Pragma: no-cache");
header("Cache: no-cahce");
require 'dbconnect.php';

function datetime($syntax,$datetime) {
    $year = substr($datetime,0,4);
    $month = substr($datetime,5,2);
    $day = substr($datetime,8,2);
    $hour = substr($datetime,11,2);
    $min = substr($datetime,14,2);
    $sec = substr($datetime,17,2);
    
    return date($syntax,mktime($hour,$min,$sec,$month,$day,$year));
}


$sql = "SELECT `gender` FROM `member` WHERE `gender` ='m'";
$result = mysql_query($sql);
$numwomen = mysql_num_rows($result);
$sql = "SELECT `gender` FROM `member` WHERE `gender` ='h'";
$result = mysql_query($sql);
$nummen = mysql_num_rows($result);

$file= fopen("Data.xml" , "w");
$_xml = '<?xml version="1.0" encoding="UTF-8" ?>'; 
$_xml .= "<graph caption='Men Vs. Women' xAxisName='Gender' yAxisName='Number of Members' showNames='1' decimalPrecision='0' formatNumberScale='0'>";
$_xml .="<set name='Men' value='".$nummen."' color='444444' />
   <set name='Women' value='".$numwomen."' color='FF4400' />
</graph>";
fwrite($file, $_xml); //fwrite -- Binary-safe file write 
fclose($file); //fclose -- Closes an open file pointer 

$sql = "SELECT `lastlogin` FROM `member`";
$result = mysql_query($sql);
$total = mysql_num_rows($result);
//CREATE LOGGED ON TABLE DATA
$date = date('Y-m-');
$date .= date('d') - 30 . date(' G:i:s');
$today = date('Y-m-d G:i:s');
$lastmonth = mktime(date('G'),date('i'),date('s'), date("m")-1, date("d"), date("Y"));
$last = date('Y-m-d G:i:s',$lastmonth);
$sql = "SELECT `lastlogin` FROM `member` where `lastlogin` <= '$today' AND `lastlogin` >= '$last'";
$result = mysql_query($sql);
$nummembers = (mysql_num_rows($result) / $total) * 100;
$numnon = (($total - mysql_num_rows($result)) / $total) * 100;

$file= fopen("Logged.xml" , "w");
$_xml = '<?xml version="1.0" encoding="UTF-8" ?>'; 
$_xml .= "<graph caption='Logged on Last 30 days' xAxisName='Members' numberSuffix='%25' yAxisName='Number of Members' showNames='1' decimalPrecision='0' formatNumberScale='0'>";
$_xml .="<set name='Yes' value='".$nummembers."' color='444444' />
		<set name='No' value='".$numnon."' color='FF4400' />
</graph>";
fwrite($file, $_xml); //fwrite -- Binary-safe file write 
fclose($file); //fclose -- Closes an open file pointer 

$i = 0;
if ($handle = opendir('/members/photos')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
			$i++;
		}
	}
	closedir($handle);
}
$memberphotos = ($i / $total) * 100;
$membernophotos = (($total - $i)/ $total)* 100;
		
$file= fopen("Photos.xml" , "w");
$_xml = '<?xml version="1.0" encoding="UTF-8" ?>'; 
$_xml .= "<graph caption='Members with Photos' xAxisName='Members' numberSuffix='%25' yAxisName='Number of Members' showNames='1' decimalPrecision='0' formatNumberScale='0'>";
$_xml .="<set name='Yes' value='".$memberphotos."' color='444444' />
		<set name='No' value='".$membernophotos."' color='FF4400' />
</graph>";
fwrite($file, $_xml); //fwrite -- Binary-safe file write 
fclose($file); //fclose -- Closes an open file pointer 
	  ?>
<html>
   <head>
      <title>Charts</title>
   </head>
   <body bgcolor="#ffffff">
      <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="400" height="250" id="Column3D" >
         <param name="movie" value="Charts/FCF_Column3D.swf" />
         <param name="FlashVars" value="&dataURL=Data.xml&chartWidth=400&chartHeight=250">
         <param name="quality" value="high" />
         <embed src="Charts/FCF_Column3D.swf" flashVars="&dataURL=Data.xml&chartWidth=400&chartHeight=250" quality="high" width="400" height="250" name="Column3D" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
      </object>
      <br />
      <br />
      <br />
      <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="400" height="250" id="Pie3D" >
         <param name="movie" value="Charts/FCF_Pie3D.swf" />
         <param name="FlashVars" value="&dataURL=Logged.xml&chartWidth=400&chartHeight=250">
         <param name="quality" value="high" />
         <embed src="Charts/FCF_Pie3D.swf" flashVars="&dataURL=Logged.xml&chartWidth=400&chartHeight=250" quality="high" width="400" height="250" name="Pie3D" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
      </object>
      <br />
      <br />
      <br />
      <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="400" height="250" id="Pie3D" >
         <param name="movie" value="Charts/FCF_Pie3D.swf" />
         <param name="FlashVars" value="&dataURL=Photos.xml&chartWidth=400&chartHeight=250">
         <param name="quality" value="high" />
         <embed src="Charts/FCF_Pie3D.swf" flashVars="&dataURL=Photos.xml&chartWidth=400&chartHeight=250" quality="high" width="400" height="250" name="Pie3D" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
      </object>

</body>
</html>