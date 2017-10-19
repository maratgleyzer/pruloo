<?
	include "_.php";
  
  if(!empty($_REQUEST['msg'])) $msg = $_REQUEST['msg'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login baby</title>
<style>
	body {
		background-color:#2b2b2b;
		background-image:url('/img/bg.jpg');
		background-repeat:repeat-x;
		margin:0;
		text-align:center;
	}
	#main {
		margin:0 auto;
		width:900px;
		height:595px;
		background-repeat:no-repeat;
		background-image:url('/img/main.jpg');

	}
	#content {
		width:400px;
		height:400px;
		float:left;
		padding-left:280px;
		padding-top:272px;
		color:#fff;
		text-align:left;
		font-family:Arial, Helvetica, sans-serif;
	}
	#content input {
		width:330px;
		height:20px;
		margin-bottom:2px;
	}
</style>
</head>
<body>
	<div id="main">
    	<div id="content">
      	<? if(isset($msg)) print "<font style='color:red;'>".$msg."</font>"; ?>
        <form action="login.php" method="post">
          Username: <input type="text" name="username" value="" /><br />
          Password: <input type="password" name="password" value="" /><br />
          <input type="image" name="submit" src="/img/login.png" border=0 style="float:right;margin-right:60px;width:67px;" />
      	</form>
     	</div>
    </div>
</body>
</html>
