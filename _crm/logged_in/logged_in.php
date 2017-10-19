<?
	include "_.php";

	if(!empty($_SESSION['usr']['username'])) {
    $sql = "SELECT * FROM users WHERE username='".$_SESSION['usr']['username']."' AND password=AES_ENCRYPT('".$_SESSION['usr']['password']."', '".$_SESSION['usr']['username']."') LIMIT 1";
    $qry = mysql_query($sql);
    if(mysql_num_rows($qry) == 0) {
  		header('Location: ../index.php?msg=You%20Must%20Login%20First!');
    }
  } else {
  	header('Location: ../index.php?msg=You%20Must%20Login%20First!');
  }

?>
