<?php
	$DEBUG = false;
?>

<!doctype html>
<html>
<body>

<?php
	if ($DEBUG) {
		echo "Welcome to \"" . $_SERVER["SCRIPT_NAME"] . "\"<br/><br/>";
		echo "res: {\"" . $_GET["res"] . "\"}<br/>";
		echo "request_uri: {\"" . $_SERVER["REQUEST_URI"] . "\"}<br/><br/>";
	
		echo "SERVER data:";
		echo "<pre>";
		print_r($_SERVER);
		echo "</pre>";
	}
?>

<?php 
	$res = $_GET["res"];
	if ($res === "notyet")
	{ 
?>
	 <h2>Please Log in</h2> 
	<form method="POST" action="uam_handle_form.php">
  	<label>User Name: <input type="text" name="username"></label><br/>
  	<label>Password: <input type="text" name="password"></label><br/>
  	<input type="hidden" name="challenge" value="<?php echo $_GET["challenge"] ?>">
  	<input type="hidden" name="uamip" value="<?php echo $_GET["uamip"] ?>">
  	<input type="hidden" name="uamport" value="<?php echo $_GET["uamport"] ?>">
  	<input type="hidden" name="userurl" value="<?php echo $_GET["userurl"] ?>">
  	<input type="submit">
</form>
<?php 
	} 
	else if ($res === "success") {
		$redir = $_SESSION["userurl"];
	  	if(isset($redir)) {
	  		echo "<head>";
    		echo '<meta http-equiv="refresh" content="3;URL=\'' . $redir . '\'">';
    		echo "</head>";
    	}
    	else {
    		echo "<h2>Log-in successful!</h2>";
    	}
	}
	else if ($res === "failed") {
		echo "<h2>Whoops, failed to authenticate</h2>";
	}
	else if ($res === "logoff") {
		echo "<h2>Logging off ...</h2>";
	}
	else {
		echo "<h2>Oops!, bad 'res' parameter</h2>";
	}
?>

</body>
</html>