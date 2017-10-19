<?
	include_once "_.php";
  
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
  $sql = "SELECT * FROM users WHERE username='$username' AND password=AES_ENCRYPT('$password', '$username')";
  $qry = mysql_query($sql);
  
  if($usr = mysql_fetch_assoc($qry)) {
  	$_SESSION['usr'] = $usr;
    $_SESSION['usr']['password'] = $password;
    
    $sql = "SELECT * FROM users_to_groups WHERE users_id = '{$usr['id']}' & groups_id = '-2600' ";
    $qry = mysql_query($sql);
    
    if($qry) {
  		header('Location: logged_in/index.php');
    } else {
  		header('Location: _logged_in/index.php');
    }
  } else {
  	header('Location: index.php?msg=Invalid%20Account%20Info');
  }
  
?>
