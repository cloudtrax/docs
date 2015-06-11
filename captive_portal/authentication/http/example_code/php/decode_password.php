<?php

/**
 * decode_password - decode encoded password to ascii string
 * @RA: Request Authenticator passed in from the Access Point
 * @encoded: The encoded password
 * @secret: Shared secret between node and server
 *
 * Returns decoded password or FALSE on error
 */
 
function decode_password($RA, $encoded, $secret)
{
	if (strlen($RA) != 32)
		return FALSE;

	$ra = hex2bin($RA);
	
	echo 'RA = ' . $RA . "<br/>"; # @@@@@@@
	echo 'ra = ' . $ra . "<br/>"; # @@@@@@@
	echo 'strlen($encoded) = ' . strlen($encoded) . "<br/>"; # @@@@@@
	
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

 # passed in the login request from the Access Point:
 $RA = "2590CC8A3930DB222781921A8F8B88B1";
 $encoded_pw = "D8A7B0E4A6122A73705C4640E86CD62EA499201D98C5F436103448C39A537B07";
 
 # the shared secret
 $secret = "verysecretstring";

echo '$RA = ' . $RA . "<br/>";
echo '$encoded_pw = ' . $encoded_pw . "<br/>";
echo '$secret = ' . $secret . "<br/>";

$password = decode_password($RA, $encoded_pw, $secret);
echo "decoded password = " . $password;

# the decoded password for the above params
# should equal "123456abcdefghijklmnopqrs" 
?>
