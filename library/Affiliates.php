<?php

class Affiliates
{
	
	public function __construct() {

		$rows = "";
		$sql = "";
		
		ini_set('error_reporting','~E_NOTICE');

		$filelines = file('affiliate.csv');
		for ($i=1;$i<count($filelines);++$i) {
			if (!eregi("[A-Z]+",$filelines[$i]))
				continue;
			$fixed_columns = array();
			$columns = explode(";",$filelines[$i]);
			for ($j=0;$j<count($columns);++$j) {
				$columns[$j] = eregi_replace("[\"\n]+",'',$columns[$j]);
				$columns[0] = ucwords(strtolower($columns[0])); 
				$columns[1] = ucwords(strtolower($columns[1]));
				$columns[2] = strtolower($columns[2]);
				$columns[5] = strtolower($columns[5]);
				$fixed_columns[] = '"'.ltrim(rtrim($columns[$j])).'"';
			}
			
			$rows[] = $fixed_columns;
		}
		
		$cnx = mysql_pconnect('localhost', 'kramer', 'p8jk6Hi4V') or die(mysql_error());
		mysql_select_db('moneymachine') or die(mysql_error());

		foreach ($rows as $row) {
			$sql =
"
insert into `mm_user` (`referer`,`old_affi_id`,`affiliate`,`user_first`,`user_last`,`username`,`password`,`user_phone`,`user_email`,`messenger`,`wire_info`)
			   values (12345     ,$row[12]     ,1          ,$row[0]     ,$row[1]    ,$row[2]   ,$row[3]   ,$row[4]     ,$row[5]     ,$row[6]    ,$row[7])
";
//echo $sql;
			if (!mysql_query($sql)) {
			   echo mysql_error();
			   exit;
			}
			
//			$oid = mysql_insert_id($cnx);
		}
		
	}	
	
	
	
}

?>