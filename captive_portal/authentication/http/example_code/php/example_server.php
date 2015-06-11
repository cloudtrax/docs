<?php
header("Content-Type: text/plain");

/**
 * secret - Shared secret between server and node
 */
$secret = "verysecretstring";

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

		if ($password == 'ThisIsThePassword' && $_GET['username'] == 'testuser') {
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

		if ($mac == '02:ba:de:af:fe:01') {
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
