<?

	$cnx_prolero_waynardblog = mysql_connect("futile.pruloo-studios.com", "prolero", "p8jk6Hi4V") or die(mysql_error());
	
  $cnx = mysql_pconnect('localhost', 'proleroinc_com', 'getrichquickscheme') or die(mysql_error());
  mysql_select_db('proleroinc_com') or die(mysql_error());
  

function save_record($arr = false, $table = false) { return record_save($arr, $table); }
function record_save($arr = false, $table = false)
{

		if (empty($arr['id']) && $table != 'orders') {
				return record_insert($arr, $table);
    } elseif (empty($arr['Order_Id']) && $table == 'orders') {
				return record_insert($arr, $table);
		} elseif (!empty($arr['purge'])) {
				return purge_record($arr, $table);
    } else {
				record_update($arr, $table);
        if($table == 'orders') return $arr['Order_Id'];
        elseif($table != 'orders') return $arr['id'];
    }
}

function record_insert($arr = false, $table = false)
{

	unset($arr['id']); //safety measure incase $arr[id] = 0 -- or anything else (afterall, this is a new record without an id)
	unset($arr['Order_Id']); //safety measure incase $arr[id] = 0 -- or anything else (afterall, this is a new record without an id)

	$sql = "INSERT INTO '$table' (";
	foreach ($arr as $key => $val) {
		$sql.= "`$key`, ";
	}
	$sql = rtrim(trim($sql), ',');
	$sql.= ") VALUES (";
	foreach ($arr as $key => $val) {
		if ($key == 'password') $sql.= "AES_ENCRYPT('$val', username), ";
		else $sql.= "'$val', ";
	}
	$sql = rtrim(trim($sql), ',');
	$sql.= ")";

	if (mysql_query($sql)) return mysql_insert_id();
	else {
		$sql_ = ($table != 'images') ? $sql : 'SQL HIDDEN!';
		return false;
	}
}

function record_update($arr = false, $table = false)
{
	$sql = "UPDATE '$table' SET ";
	foreach ($arr as $field => $val) {
		$sql.= " `$field` = '$val' , ";
	}

	$sql = rtrim(trim($sql), ',');
  
  if($table == 'orders') $sql.= " WHERE 'Order_Id' = '".$arr['Order_Id']."' ";
	else $sql.= " WHERE 'id' = '".$arr['id']."' ";
  
	$qry = mysql_query($sql);

 	if($table == 'orders') $return = $arr['Order_Id'];
  else $return = $arr['id'];
  
	return $return;
}

function purge_record($arr = false, $table = false) { return record_rmrf($arr, $table); }
function rmrf_record($arr = false, $table = false) { return record_rmrf($arr, $table); }
function record_rmrf($arr = false, $table = false)
{
	if (empty($arr)) {
		return false;
	} elseif (empty($table)) {
		return false;
	}

	if (!is_array($arr) && is_int($arr)) $arr = array('id' => $arr);

 	if (empty($arr['id'])) {
		return false;
	}

	$sql = "DELETE FROM {$table} WHERE id = '{$arr['id']}' LIMIT 1";

	return mysql_query($sql);
}



function formatPhone($phone = '', $convert = false, $cell=false, $trim = true)
{
	// If we have not entered a phone number just return empty
	if (empty($phone)) {
		return '';
	}
	
	// Strip out any extra characters that we do not need only keep letters and numbers
	$phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
	
	// Do we want to convert phone numbers with letters to their number equivalent?
	// Samples are: 1-800-TERMINIX, 1-800-FLOWERS, 1-800-Petmeds
	if ($convert == true) {
		$replace = array('2'=>array('a','b','c'),
				 						 '3'=>array('d','e','f'),
			         			 '4'=>array('g','h','i'),
				 						 '5'=>array('j','k','l'),
                     '6'=>array('m','n','o'),
				 						 '7'=>array('p','q','r','s'),
				 						 '8'=>array('t','u','v'),
                     '9'=>array('w','x','y','z'));
		
		// Replace each letter with a number
		// Notice this is case insensitive with the str_ireplace instead of str_replace 
		foreach($replace as $digit=>$letters) {
			$phone = str_ireplace($letters, $digit, $phone);
		}
	}
	
	// If we have a number longer than 11 digits cut the string down to only 11
	// This is also only ran if we want to limit only to 11 characters
	if ($trim == true && strlen($phone)>11) {
		$phone = substr($phone, 0, 11);
	}						 
	
	// Perform phone number formatting here
	if (strlen($phone) == 7) {
      return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1-$2", $phone);
  } elseif (strlen($phone) == 10 && !$cell) {
      return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $phone);
  } elseif (strlen($phone) == 10 && $cell) {
      return preg_replace("/([0-9a-zA-Z]{4})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})/", "$1 $2 $3", $phone);
  } elseif (strlen($phone) == 11) {
      return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1($2) $3-$4", $phone);
  }

	
	// Return original phone if not 7, 10 or 11 digits long
	return $phone;
}
