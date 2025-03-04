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
[expedite upgrade for node](#expedite-upgrade-for-node) | GET | `/node/<node-id>/expedite_upgrade`
[does an AP with address MAC already exist?](#does-ap-mac-already-exist) | GET |  `/node/does_mac_exist?mac=<mac>`


 <a name="list-nodes"></a>
### list nodes

`GET /node/network/<network-id>/list`

Retrieve all nodes belonging to the given network. The returned JSON is a dictionary, where each key is a node id, and detailed information for the node is contained in its corresponding value object. That information will differ slightly, depending on whether the node is a gateway (an Access Point connected directly to the Internet) or a repeater.

##### Lifecycle
Lifecycle information about devices can be found when retrieving a device or a list of devices. We currently return this information for devices that can be added to Datto Network Manager. Learn more at [Datto Networking: End of Life policy](https://networkinghelp.datto.com/help/Content/kb/Networking/General%20Information/KB370000000038.htm).

The lifecycle property has the following possible values:

- **null**: Indicates that the device model does not have end-of-sale and end-of-life dates.
- **Object with null values**: Indicates that the device model is ambiguous and the customer needs to visit the Datto Networking Article to determine the end-of-sale and/or end-of-life dates.
- **Object with Date values**: Indicates the device model's end-of-sale and/or end-of-life dates, where the date is a string type in RFC 3339 format. Available properties: *end_of_life*, *end_of_sale*.

##### Subscription

Subscription information is provided when retrieving a list of devices or information for a specific device (except of summary).

The `subscription` property has the following possible values:

- **null**: Indicates that the device does not have any subscription information.

- **Object**
In case when an object is returned, it always contains the following fields:

Field name | Type | Can be null? | Description
--- | --- | --- | ---
is_active | boolean | No | Subscription status for a particular device
start_date | string | No | Start date of subscription, Format: Y-m-d\TH:i:s\Z, e.g. “2023-05-05T00:00:00Z”
end_date | string | Yes | End date of subscription. Format: Y-m-d\TH:i:s\Z, e.g. “2026-05-05T00:00:00Z”
term | string | No | Indicates the term of renewal of subscription. Can be either “monthly” for evergreen subscriptions or “yearly” for fixed term subscriptions
term_length | integer | No | Indicates the number of months the devices has subscription for. E.g. 36 for a fixed term subscription or 1 (one) for an evergreen subscription.
is_evergreen | boolean | No | Indicates if the subscription if evergreen, or in other words, never-ending, renewing every month and not having an end date

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
			"firmware_version": "6.0.0",
			"firmware_version_full": 60000005, 
			"firmware_version_id": "56b1193bbbb0dd6ce3db169349d00cc412e771fc", 
			"firmware_version_release": "beta 5", 
			"custom_sh_errors": [ 
			  "error1" 
			], 
			"custom_sh_names": [ 
			  "ng6-0-wifi-upgrade" 
			],
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
			"lifecycle": null,
			"subscription": {
				"is_active": false,
				"start_date": "2021-01-01T00:00:00Z",
				"end_date": null,
				"term": "monthly",
				"term_length": 1,
				"is_evergreen": true
			},
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
			"lifecycle": {
				"end_of_sale": "2021-05-31T00:00:00Z",
				"end_of_life": "2026-05-31T00:00:00Z",
			},
			"subscription": {
				"is_active": true,
				"start_date": "2024-03-01T00:00:00Z",
				"end_date": "2025-03-01T14:50:12Z",
				"term": "yearly",
				"term_length": 12,
				"is_evergreen": false
			},
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
    "latitude": 49.00112233,
    "longitude": -123.00112233,
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

````json
{
	"active_clients": 0,
	"alerts": {
		"enabled": True
	},
	"allow_dfs": True,
	"anonymous_ip": False,
	"antenna_type": None,
	"autotx_tx_powers": None,
	"channel_overrides": {
		"2_4GHz": 2,
		"5GHz": 36
	},
	"channel_utilization": {
		"2_4GHz": [],
		"5GHz": []
	},
	"channels": {},
	"config_seqno": 73,
	"connection_keeper_status": "disconnected",
	"country_code": "US",
	"current_seqno": 72,
	"current_seqno_used_since": "2025-03-03T22:47:09Z",
	"custom_sh_approved": True,
	"description": "",
	"disable_led": False,
	"down": True,
	"enable_dfs": False,
	"enable_dhcp": False,
	"enable_troubleshooting_until": None,
	"expedite_upgrade": False,
	"firmware_flags": [],
	"firmware_version": "7.0.22-cdb46dd42ccbc8f108b94c1d8",
	"firmware_version_full": 70000022,
	"firmware_version_id": "cdb46dd42ccbc8f108b94c1d8ee4d9e13f88a81b",
	"firmware_version_release": "",
	"firmware_version_semantic": {
		"build": "cdb46dd42ccbc8f108b94c1d8ee4d9e13f88a81b",
		"major": 7,
		"minor": 0,
		"patch": 22
	},
	"flags": "0x0000",
	"hardware": "A42",
	"hardware_revision": "",
	"has_scanning_support": False,
	"ht_mode_overrides": {
		"2_4GHz": "HT20",
		"5GHz": "HT20"
	},
	"ht_mode_overrides_autorf": {
		"2_4GHz": "HT20",
		"5GHz": "HT20"
	},
	"ht_modes": {
		"2_4GHz": "?",
		"5GHz": "?"
	},
	"ip": "10.20.40.104",
	"is_triband": False,
	"lan_info": {
		"lan_ip": "192.168.10.20",
		"wan_ip": "1.2.3.4"
	},
	"last_checkin": "2025-03-03T22:47:37Z",
	"latitude": 45.522700,
	"lifecycle": {
		"end_of_life": "2026-05-31T00:00:00Z",
		"end_of_sale": "2021-05-31T00:00:00Z"
	},
	"lldp_negotiation_delay": False,
	"load": 1.06,
	"locate_mode": False,
	"longitude": -122.67862,
	"mac": "ac:86:74:aa:aa:aa",
	"memfree": 162916,
	"mesh_version": "batman-adv",
	"name": "AP42_upstairs",
	"network_first_add": "2025-01-28T01:55:35Z",
	"outdoor": False,
	"role": "gateway",
	"serial_no": "A1234567890",
	"spare": False,
	"subscription": None,
	"supported_country": True,
	"supports_dfs": True,
	"underpowered": False,
	"upgrade_status": "none",
	"uptime": "2m",
	"uptime_seconds": 155,
	"warranty_first_checkin": "2018-10-24T18:33:19Z"
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
`GET /node/does_mac_exist?mac=<mac>`

Call this endpoint as a double-check when a user is entering the MAC of an Access Point into the system, prior to the node actually being physically attached and checked in.

##### example request

`GET https://api.cloudtrax.com/node/does_mac_exist?mac=ac:86:74:aa:aa:aa`

##### example output
````json
{ 
	"node_exists" : false 
}
````

