# Password decoding

The password that's passed to the Authentication Server is encoded and needs to be reconstructed by the Authentication server in order to verify the user's credentials. The encoding process on the CloudTrax end uses the Request Authenticator that's passed as part of the Login Request, as well as the shared secret, to encode the password. The Authentication Server likewise needs to use these values in order to decode it.

The encoded password is a string containing a sequence of hexadecimal digit pairs whose string-length will be an integer multiple of 32 bytes. The first part of the decoding process needs to convert those hex pairs back to the ASCII characters they represent; this new string will be half the length of the old.

Here's an example of an encoded password, using for the sake of clarity a string of only ten hex digits:

` "01626364"`

The converted string will look like this:

`"\x01abc"`

The first character is escaped in this sequence, since there's no ASCII representation for this character.

A piece of Python doing this decoding would look something like this:

````
p = passworde = encoded passwordra = request authenticatores[] = e' split in substrings of 16 characters eachc[-1] = rares = []for i in len(ps):    b[i] = md5(s + c[i - 1])    c[i] = es[i]    res += es[i] xor b[i]return res
````

Note the use of the MD5 cryptographic hash function ([RFC 1321](https://www.ietf.org/rfc/rfc1321.txt)), as well as an exclusive-or operation. 

Here's a piece of PHP code that does the same thing. This is taken from a code snippet, [decode_password.php](./example_code/php/decode_password.php), that was in turn part of a larger example:  [example_server.php](./example_code/php/example_server.php), which implements much of the core functionality of an Authentication Server backend.

````
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

#### Testing your decoding routine ####
As mentioned above, the password is encoded on the CloudTrax side using an MD5 hashing of the original password along with the Request Authenticator (RA) and the shared secret (SECRET), to produce an encoded password (ENCODED). The same RA and secret are also used on the Authentication Server side to decode the encrypted password. You can test the correctness of your decoding algorithm against the following inputs:

RA: `[0x25, 0x90, 0xcc, 0x8a, 0x39, 0x30, 0xdb, 0x22, 0x27, 0x81, 0x92, 0x1a, 0x8f, 0x8b, 0x88, 0xb1]`
SECRET: `verysecretstring`<br/>
ENCODED: `[0xbd, 0xfd, 0xea, 0xa3, 0xda, 0x57, 0x1f, 0x79, 0x76, 0x68, 0x42, 0x55, 0xfc, 0x73, 0xd0, 0x36, 0xf2, 0x79, 0x07, 0xe2, 0xe8, 0x5c, 0x2d, 0x93, 0x82, 0xd2, 0x96, 0xdb, 0xb6, 0xdf, 0x0a, 0x4c]`

The expected output is:

`ThisIsThePassword`

The [decode_password.php](./example_code/php/decode_password.php) file mentioned above shows a test of decoding against these inputs.

