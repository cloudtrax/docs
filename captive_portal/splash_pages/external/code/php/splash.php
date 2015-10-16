<?php
$uam_secret = "verysecretstring";

function encode_password($plain, $challenge, $secret) {
	if ((strlen($challenge) % 2) != 0 ||
	    strlen($challenge) == 0)
	    return FALSE;

	$hexchall = hex2bin($challenge);
	if ($hexchall === FALSE)
		return FALSE;

	if (strlen($secret) > 0) {
		$crypt_secret = md5($hexchall . $secret, TRUE);
		$len_secret = 16;
	} else {
		$crypt_secret = $hexchall;
		$len_secret = strlen($hexchall);
	}

	/* simulate C style \0 terminated string */
	$plain .= "\x00";
	$crypted = '';
	for ($i = 0; $i < strlen($plain); $i++)
		$crypted .= $plain[$i] ^ $crypt_secret[$i % $len_secret];

	$extra_bytes = 0;//rand(0, 16);
	for ($i = 0; $i < $extra_bytes; $i++)
		$crypted .= chr(rand(0, 255));

	return bin2hex($crypted);
}

function print_logon_form() {
?>
<!doctype html>
<html>
<head><title>Example Remote Splash Page</title></head>
<body>
<form method="post">
  <label>User Name: <input type="text" name="username"></label><br/>
  <label>Password: <input type="text" name="password"></label><br/>
  <input type="hidden" name="challenge" value="<?php echo $_GET["challenge"] ?>">
  <input type="hidden" name="uamip" value="<?php echo $_GET["uamip"] ?>">
  <input type="hidden" name="uamport" value="<?php echo $_GET["uamport"] ?>">
  <input type="hidden" name="userurl" value="<?php echo $_GET["userurl"] ?>">
  <input type="submit">
</form>
</body>
</html>
<?php
}

function print_success() {
  session_start();
?>
<!doctype html>
<html>
<head>
<title>Example Remote Splash Page</title>
<?php
  if(isset($_SESSION["userurl"])) {
    echo '<meta http-equiv="refresh" content="3;URL=\'' . $_SESSION["userurl"] . '\'">';
  }
?>
</head>
<body>
<?php
  if(isset($_SESSION["userurl"])) {
    echo '<h1>Welcome! You will be redirected to your destination momentarily</h1>';
  } else {
    echo '<h1>Welcome!</h1>';
  }
?>
</body>
</html>
<?php
}

function print_failed() {
?>
<!doctype html>
<html>
<head><title>Example Remote Splash Page</title></head>
<body><h1>Authentication Failed</h1></body>
</html>
<?php
}

function print_logoff() {
?>
<!doctype html>
<html>
<head><title>Example Remote Splash Page</title></head>
<body><h1>GoodBye</h1></body>
</html>

<?php
}

if($_SERVER['REQUEST_METHOD'] === 'GET') {
  switch($_GET["res"]) {
  case "logoff":
    print_logoff();
    break;
  case "success":
    print_success();
    break;
  case "failed":
    print_failed();
    break;
  case "notyet":
    print_logon_form();
    break;
  default:
    http_response_code(400);
    exit();
  }
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uamip = $_POST['uamip'];
  $uamport = $_POST['uamport'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $challenge = $_POST['challenge'];
  $encoded_password = encode_password($password, $challenge, $uam_secret);

  $redirect_url = "http://$uamip:$uamport/logon" .
    "?username=" . urlencode($username) .
    "&password=" . urlencode($encoded_password);

  // If you want to redirect the user to a specific location, you may set it here
  // $redirect_url .= "&redir=" . urlencode("http://myportal.example.com");

  session_start();
  if(isset($_POST["userurl"])) {
    $_SESSION["userurl"] = $_POST["userurl"];
  } else {
    unset($_SESSION["userurl"]);
  }
  session_write_close();
 
  header("Location: $redirect_url", TRUE, 302);
  exit();
} else {
  http_response_code(400);
  exit();
}

