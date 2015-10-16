# Node Management endpoints

Create, list, update, delete, and test for characteristics of nodes.

functionality | method | endpoint
--- | --- | ---
[list nodes](#list-nodes) | GET | `/node/network/<network-id>/list`
[create node](#create-node) | POST | `/node/network/<network-id>`
[update node](#update-node) | PUT | `/node/<node-id>`
[get node](#get-node) | GET | `/node/<node-id>`
[delete node](#delete-node) | DELETE | `/node/<node-id>`
[reboot node](#reboot-node) | GET | `/node/<node-id>/reboot`
[get allowed channels for node](#get-allowed-channels-for-node) | GET | `/node/<node-id>/allowed-channels`
[reset encryption key for node](#reset-encryption-key-for-node) | GET | `/node/<node-id>/reset_encrypt_key`
[enable pairing for node](#enable-pairing-for-node) | GET | `/node/<node-id>/enable_pairing`
[expedite upgrade for node](#expedite-upgrade-for-node) | GET | `node/<node-id>/expedite_upgrade`
[does an AP with address MAC already exist?](#does-ap-mac-already-exist) | GET |  `node/does_mac_exist?mac=<mac>`


 <a name="list-nodes"></a>
### list nodes

`GET /node/network/<network-id>/list`

Retrieve all nodes belonging to the given network. The returned JSON is a dictionary, where each key is a node id, and detailed information for the node is contained in its corresponding value object. That information will differ slightly, depending on whether the node is a gateway (an Access Point connected directly to the Internet) or a repeater.

##### example request
`GET https://api.cloudtrax.com/node/network/12345/list`

##### example output

The example shows a two-node network with nodes 529813 and 525849. The former is a repeater, while the latter is a gateway.

````json
{
	"nodes": {
		"529813": {
			"mac": "ac:86:74:3b:7a:82",
			"name": "Front office",
			"ip": "6.59.122.128",
			"description": "deleted & re-added",
			"role": "repeater",
			"firmware_version": "fw-ng-r573",
			"mesh_version": "batman-adv",
			"last_checkin": "2015-05-19T16:20:59Z",
			"uptime": "2h 22m",
			"hardware": "OM5P_AN",
			"memfree": 34840,
			"load": 0.1000000000000000056,
			"spare": false,
			"flags": "0x0000",
			"latitude": 49.53025200000000098,
			"longitude": -123.0024159999999984,
			"down": true,
			"selected_gateway": {
				"name": "Back office",
				"ip": "6.59.122.192",
				"mac": "ac:86:74:3b:7a:c0",
				"hops": 1,
				"latency": 0.9699999999999999734
			},
			"channels": {
				"2_4GHz": 5,
				"5GHz": 44
			},
			"ht_modes": {
				"2_4GHz": "HT20",
				"5GHz": "HT40+"
			},
			"neighbors": [
				{
					"nid": 525849,
					"rssi": {
						"2_4GHz": -47,
						"5GHz": -57
					}
				}
			],
			"gateway_path": [
				525849
			]
		},
		"525849": {
			"mac": "ac:86:74:33:7a:c0",
			"name": "Back office",
			"ip": "6.59.122.192",
			"description": "main AP",
			"role": "gateway",
			"firmware_version": "fw-ng-r575",
			"mesh_version": "batman-adv",
			"last_checkin": "2015-06-17T13:35:43Z",
			"uptime": "21d 15h 23m",
			"hardware": "OM5P_AN",
			"memfree": 29004,
			"load": 0.02999999999999999889,
			"spare": false,
			"flags": "0x0000",
			"latitude": 49.53026900000000069,
			"longitude": -123.002387999999998,
			"down": false,
			"lan_info": {
				"lan_ip": "192.168.0.9",
				"wan_ip": "24.211.105.169"
			},
			"channels": {
				"2_4GHz": 5,
				"5GHz": 44
			},
			"ht_modes": {
				"2_4GHz": "HT20",
				"5GHz": "HT40+"
			}
		}
	}
}
````

 <a name="create-node"></a>
### create node
`POST /node/network/<network-id>`

Create a new node in the specified network, with characteristics defined by the JSON package that comprises the body of the HTTP Request. The node-id assigned to the node by CloudTrax will be returned in the case of a successful request.

There's a special case if you have "master" access where this endpoint can be used to transfer an existing node from one of your networks to another. To do so, construct the URL using the `network-id` of the destination network, but set the MAC (the "mac" field) of the "new" node to the MAC of the existing node you're moving from its original network. You also need to set "confirm_transfer" to true. This special case makes error handling slightly trickier; see [below](#errors).

A  new node needs to contain, at minimum, a MAC ("mac") and a "name".

<a name="errors"> </a>
##### error handling
* an error will be returned if the node you're creating has the same MAC as an existing node in the specified network.
* if you've "master" access and  the node has the same MAC as an existing node in *another* of your networks and "confirm_transfer" is true, the existing node will be transferred to the network specified in the URL.
* otherwise an error will be returned.

##### example request
`POST https://api.cloudtrax.com/node/network/12345`

##### example input

````json
{
    "mac": "ac:86:74:aa:cc:ee",
    "name": "new test node #1",
    "description": "added for test",
    "latitude": "49.00112233",
    "longitude": "-123.00112233",
    "confirm_transfer": "true"
}
````

##### example output
````json
{
  "node_id" : 548430
}
````

 <a name="update-node"></a>
### update node
`PUT /node/<node-id>`

Update an existing node, with characteristics defined by the JSON package in the body of the HTTP Request. Only those fields specified in the JSON package will be updated; all other node state will remain unchanged.

##### example request
`PUT https://api.cloudtrax.com/node/548430`

##### example input

Change a single field ("description") for node 54830
````json
{
	"description": "UPDATE: changing description only"
}
````

##### example output

Note the use of error code 1009 to indicate success.

````json
{
	"code": 1009,
	"message": "Success.",
	"context": "update_node",
	"values": {

	}
}
````

 <a name="delete-node"></a>
### delete node
`DELETE /node/<node-id>`

Delete an existing node.

##### example request
`DELETE https://api.cloudtrax.com/node/123456`

##### example output

Note the use of error code 1009 to indicate success.

````json
{
	"code": 1009,
	"message": "Success.",
	"context": "update_node",
	"values": {

	}
}
````
 <a name="get-node"></a>
### get node
`GET /node/<node-id>`

Retrieve a node. 

##### example request

`GET https://api.cloudtrax.com/node/549365`

##### example output

You might note, given the number of unset fields in the JSON below, that this node has been added to the network programmatically via [create node](#create-node), but not yet physically.

````json
{
	"mac": "ac:86:74:aa:aa:aa",
	"name": "TEST NODE #2",
	"ip": "",
	"description": "added for TEST #2",
	"role": "orphan",
	"firmware_version": "",
	"mesh_version": "",
	"last_checkin": "0000-00-00T00:00:00Z",
	"uptime": "",
	"hardware": "",
	"memfree": 0,
	"load": 0,
	"spare": false,
	"flags": "",
	"latitude": 49.00112200000000229,
	"longitude": -123.0011219999999952,
	"down": false
}
````

 <a name="reboot-node"></a>
### reboot node
`GET /node/<node-id>/reboot`

Reboot an Access Point.

##### example request
`GET https://api.cloudtrax.com/node/123456/reboot`

##### output

*API in flux at this point.*

 <a name="get-allowed-channels-for-node"></a>
### get allowed channels for node
`GET /node/<node-id>/allowed-channels`

Returns all allowed channels for a node for a particular country, defaulting to the country code for the node that's stored in the database. You can specify a different country with the query-string "country" parameter, using one of the two-letter country codes specified by [ISO 3166](http://www.iso.org/iso/home/standards/country_codes.htm).

##### example request
`GET https://api.cloudtrax.com/node/12345/allowed_channels`

##### example output
````json
{
    "channels": {
        "2_4GHz": [
            {
                "channel": 10,
                "ht_modes": [
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 9,
                "ht_modes": [
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 8,
                "ht_modes": [
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 7,
                "ht_modes": [
                    "HT40+",
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 6,
                "ht_modes": [
                    "HT40+",
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 5,
                "ht_modes": [
                    "HT40+",
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 4,
                "ht_modes": [
                    "HT40+",
                    "HT20" 
                ]
            },
            {
                "channel": 3,
                "ht_modes": [
                    "HT40+",
                    "HT20" 
                ]
            },
            {
                "channel": 11,
                "ht_modes": [
                    "HT40-",
                    "HT20" 
                ]
            },
            {
                "channel": 2,
                "ht_modes": [
                    "HT40+",
                    "HT20" 
                ]
            },
            {
                "channel": 1,
                "ht_modes": [
                    "HT40+",
                    "HT20" 
                ]
            }
        ]
    }
}
````

 <a name="reset-encryption-key-for-node"></a>
### reset encryption key for node
`GET /node/<node-id>/reset_encrypt_key`

Resets the encryption key used by an Access Point. This should be done when a Access Point need to be re-paired.

##### example request
`GET https://api.cloudtrax.com/node/123456/reset_encrypt_key`

 <a name="enable-pairing-for-node"></a>
### enable pairing for node
`GET /node/<node-id>/enable_pairing`

Newly added Access Points need to be paired with the network. Normally CloudTrax does this automatically, but in case an Access Point has been "re-flashed", it might be necessary to call this endpoint.

##### example request
`GET https://api.cloudtrax.com/node/123456/enable_pairing`

 <a name="expedite-upgrade-for-node"></a>
### expedite upgrade for node
`GET /node/<node-id>/expedite_upgrade`

Assuming that firmware upgrades are enabled for a network, CloudTrax will attempt to upgrade any Access Points needing firmware upgrades during the specified maintenance window. Setting the `expedite_upgrade` flag asks CloudTrax to attempt the upgrade as soon as possible, disregarding the settings for the maintenance window.

<a name="does-ap-mac-already-exist"></a>
### does an AP with address MAC already exist?
`GET node/does_mac_exist?mac=<mac>`

Call this endpoint as a double-check when a user is entering the MAC of an Access Point into the system, prior to the node actually being physically attached and checked in.

##### example request

`GET https://api.cloudtrax.com/node/does_mac_exist?mac=ac:86:74:aa:aa:aa`

##### example output
````json
{ 
	"node_exists" : false 
}
````

