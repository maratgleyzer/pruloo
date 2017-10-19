<?php

class Orders
{
	
	public function __construct() {

		$rows = "";
		$sql = "";
		
		ini_set('error_reporting','~E_NOTICE');
		
		$filelines = file('active.csv');

		for ($i=1;$i<count($filelines);++$i) {
			$fixed_columns = array();
			$columns = explode(",",$filelines[$i]);
			$columns[0] = "\"1234568\"";
			for ($j=0;$j<count($columns);++$j) {
				$columns[$j] = substr($columns[$j],1,-1);
				if ($j == 7 || $j == 16) $columns[$j] = 'US';
				if (($j == 22 || $j == 23 || $j == 39 || $j == 40 || $j == 41) && (eregi('NO',$columns[$j]))) $columns[$j] = '0';
				if (($j == 22 || $j == 23 || $j == 39 || $j == 40 || $j == 41) && (eregi('YES',$columns[$j]))) $columns[$j] = '1';
				if ($j == 1 || $j == 2 || $j == 10 || $j == 11) $columns[$j] = ucwords(strtolower($columns[$j]));
				if ($j == 3 || $j == 12 || $j == 4 || $j == 13) $columns[$j] = ucwords(strtolower($columns[$j]));
				if ($j == 25) {
					list($month, $day, $year) = explode("/",$columns[25]);
					$DATE = "$year/$month/$day";
					$columns[25] = $DATE;
				} 
				if ($j == 9 || $j == 18) $columns[$j] = strtolower($columns[$j]);
				if ($j == 59 && $columns[$j] > 3) { $columns[0] = "\"1234569\""; }
				$fixed_columns[] = '"'.ltrim(rtrim($columns[$j])).'"';
			}
			$phone_parts = $this->PARSEPHONE($columns[8]);
			$fixed_columns[63] = '"'.$phone_parts[1].'"';
			$fixed_columns[64] = '"'.$phone_parts[2].'"';
			$fixed_columns[65] = '"'.$phone_parts[3].'"';
			$fixed_columns[66] = '"'.$phone_parts[4].'"';
			$fixed_columns[67] = '"'.$phone_parts[5].'"';
			$fixed_columns[8] = '"'.$phone_parts[2]."-".$phone_parts[3]."-".$phone_parts[4].'"';
			$phone_parts = $this->PARSEPHONE($columns[17]);
			$fixed_columns[68] = '"'.$phone_parts[1].'"';
			$fixed_columns[69] = '"'.$phone_parts[2].'"';
			$fixed_columns[70] = '"'.$phone_parts[3].'"';
			$fixed_columns[71] = '"'.$phone_parts[4].'"';
			$fixed_columns[72] = '"'.$phone_parts[5].'"';
			$fixed_columns[17] = '"'.$phone_parts[2]."-".$phone_parts[3]."-".$phone_parts[4].'"';
			$expires = $this->PARSEDATE($columns[32]);
			$fixed_columns[73] = '"'.$expires['mm'].'"';
			$fixed_columns[74] = '"'.$expires['yy'].'"';
			
			$rows[] = $fixed_columns;
		}
	
		
		$cnx = mysql_pconnect('localhost', 'kramer', 'p8jk6Hi4V') or die(mysql_error());
		mysql_select_db('moneymachine') or die(mysql_error());
		
//		$sql = "delete from `mm_user_order`";
//		mysql_query($sql);

//		$sql = "delete from `mm_user_order_item`";
//		mysql_query($sql);

		foreach ($rows as $row) {
			$sql =
"
insert into `mm_user_order` (`user_id`,`camp_id`,`plan_id`,`affi_id`,`subs_id`,`bill_first`,`bill_last`,`bill_address`,`bill_city`,`bill_state`,`bill_zip`,`bill_country`,`bill_phone`,`bill_email`,`ship_first`,`ship_last`,`ship_address`,`ship_city`,`ship_state`,`ship_zip`,`ship_country`,`ship_phone`,`ship_email`,`shipper_sale`,`weight`,`delivery`,`signature`,`tracking`,`total_sale`,`card_type`,`card_number`,`expiration`,`ip_address`,`ip_lookup`,`order_status`,`decline_reason`,`fraud`,`chargeback`,`rma_number`,`rma_reason`,`transaction`,`auth_number`,`sale_date`,`sale_time`,`lead_date`,`lead_time`,`rebill_date`,`bill_phone_1`,`bill_phone_2`,`bill_phone_3`,`bill_phone_4`,`bill_phone_5`,`ship_phone_1`,`ship_phone_2`,`ship_phone_3`,`ship_phone_4`,`ship_phone_5`,`expires_mm`,`expires_yy`)
					 values (12345    ,$row[0]  ,$row[33] ,$row[50] ,$row[49] ,$row[1]     ,$row[2]    ,$row[3]       ,$row[4]    ,$row[5]     ,$row[6]   ,$row[7]       ,$row[8]     ,$row[9]     ,$row[10]    ,$row[11]   ,$row[12]      ,$row[13]   ,$row[14]    ,$row[15]  ,$row[16]      ,$row[17]    ,$row[18]    ,$row[20]      ,$row[21],$row[22]  ,$row[23]   ,$row[27]  ,$row[24]    ,$row[28]   ,$row[31]     ,$row[32]    ,$row[35]    ,$row[36]   ,'SALE'        ,$row[38]        ,$row[39],$row[40]   ,$row[42]    ,$row[43]    ,$row[46]     ,$row[47]     ,$row[25]   ,$row[26]  ,$row[25]   ,$row[26]	  ,$row[45], $row[63], $row[64], $row[65], $row[66], $row[67], $row[68], $row[69], $row[70], $row[71], $row[72], $row[73], $row[74])
";
//echo $sql;exit;
			if (!mysql_query($sql)) {
			   echo mysql_error();
			   exit;
			}
			
			$oid = mysql_insert_id($cnx);
		}
		
	}
	
	
	
	
	
	
	public function PARSEPHONE($phone) {
		
		$phone = eregi_replace("[^0-9]+","",$phone);
			
		if (strlen($phone) < 8) {
			$phone_part[1] = '000';
			$phone_part[2] = '000';
			$phone_part[3] = substr($phone,0,3);
			$phone_part[4] = substr($phone,3,4);
			$phone_part[5] = '000';
			return $phone_part;
		}
		
		if (strlen($phone) < 11) {
			$phone_part[1] = '000';
			$phone_part[2] = substr($phone,0,3);
			$phone_part[3] = substr($phone,3,3);
			$phone_part[4] = substr($phone,6,4);
			$phone_part[5] = '000';
			return $phone_part;
		}

		if (strlen($phone) < 14) {
			$phone_part[1] = '000';
			$phone_part[2] = substr($phone,0,3);
			$phone_part[3] = substr($phone,3,3);
			$phone_part[4] = substr($phone,6,4);
			$phone_part[5] = substr($phone,10,3);
			return $phone_part;
		}
		
	}

	
	
	public function PARSEDATE($exp) {
		
		$expires['mm'] = substr($exp,0,2);
		$expires['yy'] = substr($exp,2,2);

		return $expires;
	}
	
	
}

?>