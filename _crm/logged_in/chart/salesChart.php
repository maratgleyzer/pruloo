<?php
	$select_date = date('F Y');
	$xml = '<graph caption="Sales Volume" subcaption="For the month of '.$select_date.'" bgColor="0d0d0d" baseFontColor="c5c5c5" canvasBgColor="0d0d0d" divlinecolor="F47E00" numdivlines="4" showAreaBorder="1" areaBorderColor="000000" numberPrefix="$" showNames="1" numVDivLines="29" vDivLineAlpha="30" formatNumberScale="1" rotateNames="1" decimalPrecision="0">';
	
	$xml .= '<categories>';
	//Get it for this month.
	$days = date('t');
	$month = date('m');
	$i = 1;
	while ( $i <= $days){
		$xml .='<category name="'.$month.'/'.$days.'"/>';
		$i++;
	}
	$xml .= '</categories>';
	
	$xml .= '<dataset seriesname="Approved Orders" color="FF5904" showValues="0" areaAlpha="50" showAreaBorder="1" areaBorderThickness="2" areaBorderColor="FF0000">';
	$days = date('t');
	$month = date('m');
	$year = date('Y');
	$i = 1;
	while ( $i <= $days){
		$num = 0;
		$date = date('Y-m-d', strtotime($month . '/' . $i . '/' . $year));
		$sql = "SELECT COUNT(*) as 'num' FROM `orders` WHERE `Date_Of_Sale` ='$date' AND `Order_Status` !='DECLINED'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$num = $row['num'];
		}
		$xml .='<set value="'.$num.'"/>';
		$i++;
	}
	$xml .= '</dataset>';
	
	$xml .= '<dataset seriesname="Product B" color="f1f1f1" showValues="0" areaAlpha="50" showAreaBorder="1" areaBorderThickness="2" areaBorderColor="ffffff">';
	$days = date('t');
	$month = date('m');
	$year = date('Y');
	$i = 1;
	while ( $i <= $days){
		$num = 0;
		$date = date('Y-m-d', strtotime($month . '/' . $i . '/' . $year));
		$sql = "SELECT COUNT(*) as 'num' FROM `orders` WHERE `Date_Of_Sale` ='$date' AND `Order_Status` ='DECLINED'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$num = $row['num'];
		}
		$xml .='<set value="'.$num.'"/>';
		$i++;
	}
	$xml .= '</dataset>
</graph>';

	$testXml = 'Sales.xml';
	
	$fh = fopen($testXml, 'w+');
	fwrite($fh, $xml);
	fclose($fh);
?>