<?php
/**
 * secret - Shared secret between server and node
 */
$secret = "toosecretstring";

/**
 * sigsecret - Secret used to sign the username
 */
$sigsecret = "evenmoresecretstring";


/**
 * encode_password_challenge - encode plain text password to ascii string
 * @plain: plain password
 * @challenge: challenge from the node
 * @secret: Shared secret between node and server
 *
 * Returns encoded password or FALSE on error
 */
function encode_password_challenge($plain, $challenge, $secret)
{
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

	$extra_bytes = rand(0, 16);
	for ($i = 0; $i < $extra_bytes; $i++)
		$crypted .= chr(rand(0, 255));

	return bin2hex($crypted);
}

/* WARNING better use http_redirect from pecl_http for this */
function redirect($url, $statusCode = 302)
{
	header('Location: ' . $url, true, $statusCode);
}

function main()
{
	global $secret;
	global $sigsecret;

	foreach  (array('res', 'uamip', 'uamport', 'mac') as $req) {
		if (!array_key_exists($req, $_GET)) {
			header("Content-Type: text/plain");
			print("No ". $req ." found\n");
			return;
		}
	}

	switch ($_GET['res']) {
	case 'notyet':
	case 'failed':
		/* continue after this block */
		break;
	case 'logoff':
		header("Content-Type: text/plain");
		print("Bye\n");
		return;
	case 'success':
		header("Content-Type: text/plain");
		print("Congrats. You are now logged in\n");
		return;
	default:
		header("Content-Type: text/plain");
		print("Sorry, I don't understand you\n");
		return;
	}

	/* autogenerate a username and password */
	$username = $_GET['mac'] . '_' . time();
	$password = base64_encode(hash_hmac('sha256', $username, $sigsecret, true));

	if (!array_key_exists('challenge', $_GET)) {
		/* this should not happen and is not part of the specification */
		$pw_crypt = urlencode($password);
	} else {
		$pw_crypt = encode_password_challenge($password,
						      $_GET['challenge'],
						      $secret);
	}

	if ($pw_crypt === FALSE) {
		header("Content-Type: text/plain");
		print("Failed to encode the password\n");
		return;
	}


	/* WARNING this check should use some extra token which changes often
	 * to avoid users to automate the form submission with zero effort
	 */
	if (array_key_exists('accept', $_GET) && $_GET['accept'] == 'accept') {
		redirect('http://'. $_GET['uamip'] . ':' . $_GET['uamport'] . '/logon?username=' . urlencode($username) . '&password=' . $pw_crypt);
	} else {
		render_page();
	}
}

/* WARNING better use proper template system - this is just a quick hack for this example */
function render_page()
{
?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Splashpage</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
	<body>
		<h1>TOS</h1>

		<p>Lorem ipsum dolor sit amet, reque tation ad duo, illud
		aperiam labores eam ad, legere ignota nostrum ea duo. In sea
		etiam congue decore. Illud tantas corrumpit est in, sale prima
		error id mei. Ex his fugit aeterno, an natum tincidunt
		abhorreant sea, et eos modus convenire signiferumque. Ei eum
		iuvaret reprehendunt. Aliquid tacimates quo at, vix dicta
		scribentur consequuntur eu.</p>

		<p>Munere omnesque ei ius. Aliquam nominavi mei te, eum zril
		facete qualisque at, dicta hendrerit consequuntur ei nec. Odio
		integre intellegebat ius ea, agam diceret nominati eu nec, no
		menandri persecuti quaerendum eos. Eu latine explicari
		voluptaria vel. Qui mucius luptatum id. Vis in volumus
		scribentur, ad eam vulputate definitiones, ei nec exerci
		moderatius posidonium.</p>

		<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div>
			<input type="hidden" name="res" id="res" value="<?php echo htmlspecialchars($_GET['res']) ?>" />
			<input type="hidden" name="uamip" id="uamip" value="<?php echo htmlspecialchars($_GET['uamip']) ?>" />
			<input type="hidden" name="uamport" id="uamport" value="<?php echo htmlspecialchars($_GET['uamport']) ?>" />
			<input type="hidden" name="mac" id="mac" value="<?php echo htmlspecialchars($_GET['mac']) ?>" />
			<?php
				if (array_key_exists('challenge', $_GET)) {
			?>
				<input type="hidden" name="challenge" id="challenge" value="<?php echo htmlspecialchars($_GET['challenge']) ?>" />
			<?php
				}
			?>

			<input type="checkbox" name="accept" id="accept" value="accept" /><label for="accept">Accept TOS</label>
		</div>
		<div>
			<input type="submit" name="submit" value="Enter" />
		</div>
		</form>
	</body>
</html>

<?php
}

main();

?>

