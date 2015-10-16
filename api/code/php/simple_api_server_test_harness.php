<?php
	
//  --------------------------
//  replace with the real thing
//  --------------------------

$key = '<key-provided-by-open-mesh>';
$secret = '<secret-provided-by-open-mesh>';

# a fellah needs enums, whatcha gonna do?!
class Method
{
    const GET = 0;
    const POST = 1;
    const PUT = 2;
    const DELETE = 3;
    
    public static function nameForEnumValue($value) {
        switch($value) {
            case 0: return "GET";
            case 1: return "POST";
            case 2: return "PUT";
            case 3: return "DELETE";
        }
    }
};

function print_debug_info($method, $endpoint, $headers) {
    print( "\n" );
    print( "Method: " . Method::nameForEnumValue($method) . "\n");
    print( "Endpoint: " . $endpoint . "\n" );
    print_r($headers);
}

function build_headers($auth, $sign) {
    $headers = array();
    $headers[] = "Authorization: " . $auth;
    $headers[] = "Signature: " . $sign;
    $headers[] = "Content-Type: application/json";
    $headers[] = "OpenMesh-API-Version: 1";
    return $headers;
}

function invoke_curl($method, $endpoint, $headers, $json) {

    $api_server = 'https://api.cloudtrax.com';

    try {
        // get a curl handle then go to town on it

        $ch = curl_init($api_server . $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        if ($method == Method::DELETE)
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        elseif ($method == Method::PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        else if ($method == Method::POST) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        
        $result = curl_exec($ch);
        if ($result == FALSE) {
            if (curl_errno($ch) == 0)
                echo "@@@@ NOTE @@@@: nil HTTP return: This API call appears to be broken" . "\n";
            else
                throw new Exception(curl_error($ch), curl_errno($ch));    
        }
        else
            echo "RESULT: \n" . $result . "\n";
    } 
    catch(Exception $e) {
        trigger_error( sprintf('Curl failed with error #%d: "%s"',
            $e->getCode(), $e->getMessage()), E_USER_ERROR);
    }
}

function call_api_server($method, $endpoint, $data) {

    global $key, $secret;
    
    $time = time();
    $nonce = rand();

    if ($method == Method::POST)
        assert( '$data != NULL /* @@@@ POST requires $data @@@@ */');
    elseif ($method == Method::GET || $method == Method::DELETE)
        assert( '$data == NULL /* @@@ GET and DELETE take no $data @@@ */');
        
    $path = $endpoint;
    // if present, concatenate encoded json to $endpoint for Signature:
    if ($data != NULL) {
    	$json = json_encode($data);
        $path .= $json;
    }

    $authorization = "key=" . $key . ",timestamp=" . $time . ",nonce=" . $nonce;
    $signature =  hash_hmac('sha256', $authorization . $path . $body, $secret);
    $headers = build_headers($authorization, $signature);
    
    print_debug_info($method, $endpoint, $headers);
    
    invoke_curl($method, $endpoint, $headers, $json);
}


// 	=====================================================
//	EXAMPLE API ENDPOINT CALLS
// 	=====================================================

// -----------------
// list all networks
// -----------------

call_api_server(Method::GET, "/network/list", NULL);

// ----------------------------
// create a new minimal network
// ----------------------------

$data = array(
            'name' => 'Howards-test-network-#10',
            'password' => 'my-beloved-dead-dog-rufus',
            'timezone' => 'America/Los_Angeles',
            'country_code' => 'US'
        );
call_api_server(Method::POST, "/network", $data);

// ------------------
// add a minimal node
// ------------------

$data = array(
            'mac' => 'AC:86:74:00:22:44',
            'name' => 'Test-Node-#3'
        );

//	initially commented out until we've had an opportunity to 
// 	visually inspect the output of the preceding call to pick 
//	out a valid <network-id> to use

// call_api_server(Method::POST, "/node/network/<network-id>", $data);

// 	----------------------------
//	delete the node just created
//	----------------------------

//	similarly commented out until we've had an opportunity, after
//	running the prior API, to determine the <node-id> of the new node
//	so we can delete it

//call_api_server(Method::DELETE, "/node/<node-id>", NULL);

//	------------------------------------------------------------------
//	get a list of nodes to verify that the one you deleted is gone.

//	(if you're really thorough, you'll run list nodes @before@ you
//	delete it as well to insure it was really there in the first place)
// 	------------------------------------------------------------------

//call_api_server(Method::GET, "/node/network/<network-id>/list", NULL);

// 	----------------------------------------------------------------------
//	update the captive portal 'block' message for SSID 3, network <network-id>
//  ----------------------------------------------------------------------
$data = array( 'ssids' => 
            array( '3' => 
                array( 'captive_portal' => 
                    array( 'block_message' => "Stop in the name of love!" )
                )
            )
        );

//call_api_server(Method::PUT, "/network/<network-id>/settings", $data);

?>
