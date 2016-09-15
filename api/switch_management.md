# Switch Management endpoints

Create, list, update, delete, and test for characteristics of switches.

functionality | method | endpoint
--- | --- | ---
[list switches](#list-switches) | GET | `/switch/network/<network-id>/list`
[create switch](#create-switch) | POST | `/switch/network/<network-id>`
[update switch](#update-switch) | PUT | `/switch/<node-id>`
[get node](#get-node) | GET | `/node/<node-id>`
[delete node](#delete-node) | DELETE | `/node/<node-id>`
[reboot node](#reboot-node) | GET | `/node/<node-id>/reboot`
[get allowed channels for node](#get-allowed-channels-for-node) | GET | `/node/<node-id>/allowed-channels`
[reset encryption key for node](#reset-encryption-key-for-node) | GET | `/node/<node-id>/reset_encrypt_key`
[enable pairing for node](#enable-pairing-for-node) | GET | `/node/<node-id>/enable_pairing`
[expedite upgrade for node](#expedite-upgrade-for-node) | GET | `/node/<node-id>/expedite_upgrade`
[does an AP with address MAC already exist?](#does-ap-mac-already-exist) | GET |  `/node/does_mac_exist?mac=<mac>`


 <a name="list-switches"></a>
### list switches

`GET /switch/network/<network-id>/list`

Retrieve all switch-related information on switches belonging to the given network.

##### example request
`GET https://api.cloudtrax.com/switch/network/12345/list`

##### example output

````json
{
    "switches": [
        {
            "id": 18,
            "poe": {
                "available_power_w": 0,
                "total_power_w": 0
            },
            "summary_info": {
                "active_ports": 1,
                "cloud_status": "up",
                "description": "",
                "firmware_version": "v0.00.04",
                "gateway_ip": "10.0.0.1",
                "ip": "10.0.1.131",
                "last_checkin": "2016-07-06T23:15:37Z",
                "mac": "88:DC:96:1E:04:49",
                "model": "OMS24",
                "name": "switch john",
                "total_ports": 28,
                "uptime_seconds": 16109,
                "connection_keeper_status": "disconnected" 
            }
        },
        {
            "id": 19,
            "poe": {
                "available_power_w": 22,
                "total_power_w": 740
            },
            "summary_info": {
                "active_ports": 3,
                "cloud_status": "up",
                "description": "",
                "firmware_version": "v0.00.03",
                "gateway_ip": "10.0.0.1",
                "ip": "10.0.1.67",
                "last_checkin": "2016-07-06T23:14:41Z",
                "mac": "88:DC:96:16:B4:E3",
                "model": "OMS48",
                "name": "switch john1",
                "total_ports": 52,
                "uptime_seconds": 7836,
                "connection_keeper_status": "disconnected" 
            }
        },
        {
            "id": 23,
            "poe": {
                "available_power_w": 39,
                "total_power_w": 150
            },
            "summary_info": {
                "active_ports": 2,
                "cloud_status": "down",
                "description": "",
                "firmware_version": "v0.00.03",
                "gateway_ip": "10.0.0.1",
                "ip": "10.0.1.64",
                "last_checkin": "2016-07-06T22:49:22Z",
                "mac": "AC:86:74:00:00:02",
                "model": "OMS8",
                "name": "andreas test",
                "total_ports": 12,
                "uptime_seconds": 12420,
                "connection_keeper_status": "disconnected" 
            }
        },
        {
            "id": 63,
            "poe": {
                "available_power_w": 0,
                "total_power_w": 0
            },
            "summary_info": {
                "active_ports": 0,
                "cloud_status": "unknown",
                "description": "",
                "firmware_version": "",
                "gateway_ip": "",
                "ip": "",
                "last_checkin": "",
                "mac": "AC:86:74:00:00:99",
                "model": "OMS8",
                "name": "andreas test",
                "total_ports": 0,
                "uptime_seconds": 0,
                "connection_keeper_status": "disconnected" 
            }
        },
        {
            "id": 66,
            "poe": {
                "available_power_w": 64,
                "total_power_w": 150
            },
            "summary_info": {
                "active_ports": 2,
                "cloud_status": "up",
                "description": "Copy of a switch that already exists",
                "firmware_version": "v0.00.03",
                "gateway_ip": "10.0.0.1",
                "ip": "10.0.1.64",
                "last_checkin": "2016-07-06T23:11:48Z",
                "mac": "88:DC:96:10:FE:4A",
                "model": "OMS8",
                "name": "CP_JVS-PDX-OMS8",
                "total_ports": 12,
                "uptime_seconds": 16562,
                "connection_keeper_status": "disconnected" 
            }
        }
    ]
}
````

 <a name="create-switch"></a>
### create switch
`POST /switch/network/<network-id>`

Create a new switch entry in the specified network, with characteristics defined by the JSON package that comprises the body of the HTTP Request. 

A  new switch needs to contain, at minimum, a MAC ("mac") and a "name". [@@@ HKATZ: LIFTED VERBATIM FROM THE node DOCUMENTATION. IS THIS TRUE FOR SWITCHES AS WELL?? @@@]

<a name="errors"> </a>
##### error handling
[@@@ THE node DOCS SAY THE FOLLOWING. 
An error will be returned if the node you're creating has the same MAC as an existing node in the specified network.

##### example request
`POST https://api.cloudtrax.com/switch/network/12345`

##### example input

````json
{
    "mac": "ac:86:74:00:00:01",
    "name": "garage",
    "description" : "some description",
    "model" : "model",
    "confirm_transfer":true
}
````

[@@@ HKATZ: ANDREAS' DOC SAYS:]
Output:
The API either returns http statuscode 200 on success or an http error and json describing the [[dashboard:API_errors|errors]] in case of a failure.

On success, the API returns the id of the newly created switch.
[@@@ HKATZ: FIND MATCHING FORMAT FOR THIS OUTSIDE THE NODE DOCS]

##### example output
````json
{
 	"switch_id" : 123456
}
````

 <a name="update-switch"></a>
### update switch
`PUT /switch/<switch-id>`

Change the settings of an existing switch.

##### example request
`PUT https://api.cloudtrax.com/switch/12345`

##### example input

[@@@ HKATZ: NO EXPLANATORY TEXT ACCOMPANYING THIS SNIPPET. DO WE NEED? ]

````json
{
    "firmware": {
        "active_partition": 1
    },
    "ports": [
        {
            "enable": true,
            "id": "9",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low" 
            },
            "tagged_vlans": "1,2,4,3,9,8,8,10,10,4000-4094,12,991-994",
            "untagged_vlans": "1",
            "vlan_id": 1
        }
    ],
    "summary_info": {
        "description": "",
        "name": "switch john" 
    }
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

