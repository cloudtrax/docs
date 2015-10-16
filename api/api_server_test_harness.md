# An API Server test harness

The accompanying PHP script, [simple_api_server_test_harness.php] (code/php/simple_api_server_test_harness.php) can be invoked from the command line thusly:

````
php simple_api_server_test_harness.php
```


The script will run through a number of endpoint calls, writing the results of those calls to the console, along with any JSON packages returned. The endpoints currently invoked in the script are:

* `GET /network` (list all networks)
* `POST /network` (create a new network)
* `POST /node` (create a new node)
* `DELETE /node` (delete a node), and
* `PUT /network/<network-id>/settings` (update a particular network attribute)

The call to the `GET /network` endpoint (list all networks) is invoked as:
```` php
call_api_server(Method::GET, "/network", NULL);
````
and the call to `POST /network` (create a new network) is invoked thusly:
```` php
$data = array(
			'mac' => 'AC:86:74:00:11:22',
			'name' => 'Test-Node-#1'
		);
		
call_api_server(Method::POST, "/node/network/<network-id>", $data);
````

where `<network-id>` is a placeholder for the actual ID of the network you're deleting. The third parameter, `$data`, is a piece of JSON required by this endpoint as a POST method.

Most of the calls are initially commented out, because the list of calls is "sequenced" in this example, and determining a parameter for one call in most cases depends on being able to visually inspect the output of a prior call, in order to replace a placeholder with an actual &lt;network-id&gt; or &lt;node-id&gt; value. Comments in the code explain this.

When the script is run the first time, you'll initially get several authentication errors, because you need to replace the placeholder key and secret strings at the top of the file with the real thing. Once you're done that, `GET /network/list` should provide a JSON summary of your current network configuration, and `POST /network` will create a new minimal network. The rest of the calls await your uncommenting, one after another.

Feel free to use and expand the test harness as you explore the API.
