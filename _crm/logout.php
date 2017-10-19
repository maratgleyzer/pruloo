<?

	session_destroy($_SESSION['usr']);
  unset($_SESSION['usr']['username']);
  unset($_SESSION['usr']['password']);
  $_SESSION['usr']['username'] = "";
  $_SESSION['usr']['password'] = "";
  header("Location: index.php?msg=You're%20now%20logged%20out...");

?>
