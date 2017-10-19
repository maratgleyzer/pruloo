<?php
require 'dbconnect.php';
include "charts.php";


$sql = "SELECT * FROM `member` WHERE `gender` ='m'";
$result = mysql_query($sql);
$m = mysql_num_rows($result);
$sql = "SELECT * FROM `member` WHERE `gender` ='h'";
$result = mysql_query($sql);
$h = mysql_num_rows($result);
$total = $h + $m;

$chart [ 'chart_data' ][ 0 ][ 0 ] = "";
$chart [ 'chart_data' ][ 0 ][ 1 ] = "";
$chart [ 'chart_data' ][ 0 ][ 2 ] = "";
$chart [ 'chart_data' ][ 1 ][ 0 ] = "Men";
$chart [ 'chart_data' ][ 2 ][ 0 ] = "Women";
$sql = "SELECT * FROM `member`";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){
	if($row['gender'] == 'm'){
		$m++;
	} else if($row['gender'] == 'h') {
		$h++;
	}
	
}
$chart [ 'series_switch' ] = true;

$chart [ 'chart_data' ][ 1 ][ 1 ] = $h;
$chart [ 'chart_data' ][ 2 ][ 1 ] = $m;

$chart [ 'series_color' ] = array ( "444444", "FF4400");

 $chart['chart_type'] = 'parallel 3d column';
 
 $chart [ 'chart_transition' ] = array ( 'type'      =>  "drop",
                                        'delay'     =>  1, 
                                        'duration'  =>  2, 
                                        'order'     =>  "series"                                 
                                      );

//send the new data to charts.swf
SendChartData ( $chart );
?>