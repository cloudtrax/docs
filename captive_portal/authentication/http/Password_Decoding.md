# Password decoding

The password that's passed to the Authentication Server is encoded and needs to be reconstructed by the Authentication server in order to verify the user's credentials. The encoding process on the CloudTrax end uses the Request Authenticator that's passed as part of the Login Request, as well as the shared secret, to encode the password. The Authentication Server likewise needs to use these values in order to decode it.

The encoded password is a string containing a sequence of hexadecimal digit pairs whose string-length will be an integer multiple of 32 bytes. The first part of the decoding process needs to convert those hex pairs back to the ASCII characters they represent; this new string will be half the length of the old.

Here's an example of an encoded password, using for the sake of clarity a string of only ten hex digits:

` "01626364"`

The converted string will look like this:

`"\x01abc"`

The first character is escaped in this sequence, since there's no ASCII representation for this character.

A piece of Python doing this decoding would look something like this:

````python
p = passworde = encoded passwordra = request authenticatores[] = e' split in substrings of 16 characters eachc[-1] = rares = []for i in len(ps):    b[i] = md5(s + c[i - 1])    c[i] = es[i]    res += es[i] xor b[i]return res
````

Note the use of the MD5 cryptographic hash function ([RFC 1321](https://www.ietf.org/rfc/rfc1321.txt)), as well as an exclusive-or operation. 

Here's a piece of PHP code that does the same thing.

````php
$bincoded = hex2bin($encoded);
$password = "";
$last_result = $ra;

for ($i = 0; $i < strlen($bincoded); $i += 16) {
	$key = hash('md5', $secret . $last_result, TRUE);
	for ($j = 0; $j < 16; $j++)
		$password .= $key[$j] ^ $bincoded[$i + $j];
	$last_result = substr($bincoded, $i, 16);
}
````

This is taken from a piece of code, [decode_password.php](./example_code/php/decode_password.php), (in turn taken from a larger example,  [example_server.php](./example_code/php/example_server.php)), which provides a fairly full-featured example of much of the core functionality of an Authentication Server backend.

The line

```` php
$password .= $key[$j] ^ $bincoded[$i + $j]
````
in this snippet shows an example of PHP's bitwise [exclusive-or operator](http://php.net/manual/en/language.operators.bitwise.php) (as opposed to logical), comparable to Python's "xor" operator in the Python code above.
 
 <a name="test-decoding"></a>
#### Testing your decoding routine ####
The function `test_decode_password()` at the bottom of  [decode_password.php](./example_code/php/decode_password.php) provides an example of successfully decoding and testing for an expected output (the password "123456abcdefghijklmnopqrs"), given a particular RA and encoded password passed via the Login Request, as well as a specific shared secret. Your own decoding routine needs to be able to recapitulate this particular result, given the same inputs.



