<?php
header("Content-Type: text/plain");

/**
 * secret - Shared secret between server and node
 */
$secret = "verysecretstring";

/**
 * sigsecret - Secret used to sign the username
 */
$sigsecret = "evenmoresecretstring";

/**
 * token_ttl - How long a token should be acceptable in seconds
 */
$token_ttl = 120;

/**
 * response - Standard response (is modified depending on the result
 */
$response = array(
	'CODE' => 'REJECT',
	'RA' => '0123456789abcdef0123456789abcdef',
	'BLOCKED_MSG' => 'Rejected! This doesnt look like a valid request',
);


/**
 * print_dictionary - Print dictionary as encoded key-value pairs
 * @dict: Dictionary to print
 */
function print_dictionary($dict)
{
	foreach ($dict as $key => $value) {
		echo '"', rawurlencode($key), '" "', rawurlencode($value), "\"\n";
	}
}

/**
 * calculate_new_ra - calculate new request authenticator based on old ra, code
 *  and secret
 * @dict: Dictionary containing old ra and code. new ra is directly stored in it
 * @secret: Shared secret between node and server
 */
function calculate_new_ra(&$dict, $secret)
{
	if (!array_key_exists('CODE', $dict))
		return;

	$code = $dict['CODE'];

	if (!array_key_exists('RA', $dict))
		return;

	if (strlen($dict['RA']) != 32)
		return;

	$ra = hex2bin($dict['RA']);
	if ($ra === FALSE)
		return;
	
	$dict['RA'] = hash('md5', $code . $ra . $secret);
}

/**
 * decode_password - decode encoded password to ascii string
 * @dict: dictionary containing request RA
 * @encoded: The encoded password
 * @secret: Shared secret between node and server
 *
 * Returns decoded password or FALSE on error
 */
function decode_password($dict, $encoded, $secret)
{
	if (!array_key_exists('RA', $dict))
		return FALSE;

	if (strlen($dict['RA']) != 32)
		return FALSE;

	$ra = hex2bin($dict['RA']);
	if ($ra === FALSE)
		return FALSE;

	if ((strlen($encoded) % 32) != 0)
		return FALSE;

	$bincoded = hex2bin($encoded);

	$password = "";
	$last_result = $ra;

	for ($i = 0; $i < strlen($bincoded); $i += 16) {
		$key = hash('md5', $secret . $last_result, TRUE);
		for ($j = 0; $j < 16; $j++)
			$password .= $key[$j] ^ $bincoded[$i + $j];
		$last_result = substr($bincoded, $i, 16);
	}

	$j = 0;
	for ($i = strlen($password); $i > 0; $i--) {
		if ($password[$i - 1] != "\x00")
			break;
		else
			$j++;
	}

	if ($j > 0) {
		$password = substr($password, 0, strlen($password) - $j);
	}
 
	return $password;
}

/**
 * validate_login - check if the login was valid for tos page
 *
 * Returns TRUE for valid logins, FALSE on errors
 */
function validate_login($username, $password, $mac)
{
	global $token_ttl;
	global $sigsecret;

	/* split into mac and timestamp */
	$ret = preg_match('/^(?P<mac>([A-F0-9]{2}\-){5}[A-F0-9]{2})_(?P<timestamp>[0-9]+)$/',
			  $username, $matches);
	if (!$ret)
		return FALSE;

	/* check if mac is the same */
	if (str_replace(':', '-', $mac) != $matches['mac'])
		return FALSE;

	/* check if token comes from the future */
	$now = time();
	if ($now < $matches['timestamp'])
		return FALSE;

	/* check if token is too old */
	if ($token_ttl < ($now - $matches['timestamp']))
		return FALSE;

	/* generate signature and compare with the password */
	$signature = base64_encode(hash_hmac('sha256', $username, $sigsecret, true));
	if ($signature != $password)
		return FALSE;

	return TRUE;
}

/* copy request authenticator */
if (array_key_exists('ra', $_GET) && strlen($_GET['ra']) == 32 && ($ra = hex2bin($_GET['ra'])) !== FALSE && strlen($ra) == 16) {
	$response['RA'] = $_GET['ra'];
}

/* decode password when available */
$password = FALSE;
if (array_key_exists('username', $_GET) && array_key_exists('password', $_GET))
	$password = decode_password($response, $_GET['password'], $secret);

/* store mac when available */
$mac = FALSE;
if (array_key_exists('mac', $_GET))
	$mac = $_GET['mac'];

/* decode request */
if (array_key_exists('type', $_GET)) {
	$type = $_GET['type'];

	switch ($type) {
	case 'login':
		if ($password === FALSE)
			break;

		if ($mac === FALSE)
			break;

		if (validate_login($_GET['username'], $password, $mac) === TRUE) {
			unset($response['BLOCKED_MSG']);
			$response['CODE'] = "ACCEPT";
			$response['SECONDS'] = 3600;
			$response['DOWNLOAD'] = 2000;
			$response['UPLOAD'] = 800;
		} else {
			$response['BLOCKED_MSG'] = "Invalid username or password";
		}
		break;
	case 'status':
		if ($mac === FALSE)
			break;

		if ($mac == '5C:0A:5B:4E:6A:C5') {
			unset($response['BLOCKED_MSG']);
			$response['CODE'] = "ACCEPT";
			$response['SECONDS'] = 120;
			$response['DOWNLOAD'] = 3000;
			$response['UPLOAD'] = 400;
		} else {
			$response['BLOCKED_MSG'] = "Unknown Client";
		}
		break;
	case 'acct':
	case 'logout':
		if ($mac === FALSE)
			break;
		unset($response['BLOCKED_MSG']);
		$response['CODE'] = "OK";
		break;
	};
}

/* calculate new request authenticator based on answer and request -> send it out */
calculate_new_ra($response, $secret);
print_dictionary($response);

?>
