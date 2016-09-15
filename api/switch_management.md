# Switch Management endpoints

Create, list, update, delete, and test for characteristics of switches.

functionality | method | endpoint
--- | --- | ---
[list switches](#list-switches) | GET | `/switch/network/<network-id>/list`
[create switch](#create-switch) | POST | `/switch/network/<network-id>`
[update switch](#update-switch) | PUT | `/switch/<switch-id>`
[get switch](#get-node) | GET | `/switch/<switch-id>`
[delete switch](#delete-switch) | DELETE | `/switch/<switch-id>`

[reboot switch](#reboot-node) | GET | `/node/<node-id>/reboot`
[reset switch](#get-allowed-channels-for-node) | GET | `/node/<node-id>/allowed-channels`
[enable pairing for switch](#enable-pairing-for-node) | GET | `/node/<node-id>/enable_pairing`
[expedite upgrade for switch](#expedite-upgrade-for-node) | GET | `/node/<node-id>/expedite_upgrade`
[update switch-related network settings](#does-ap-mac-already-exist) | GET |  `/node/does_mac_exist?mac=<mac>`
[list allowed firmware](#list-allowed-firmware)


 <a name="list-switches"></a>
### list switches

`GET /switch/network/<network-id>/list`

Retrieve all switch-related information on switches in the given network.

##### example request
`GET https://api.cloudtrax.com/switch/network/12345/list`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error in case of failure. On success, the API returns a JSON package with a list of the switches.

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

Create a new switch entry in the specified network, with characteristics defined by the JSON package comprising the body of the HTTP Request. 

[@@@ redmine docs says "If the MAC already exists, the following will happen", without specifying the following. @@@]


##### example request
`POST https://api.cloudtrax.com/switch/network/12345`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error in case of failure. On success, the API returns a JSON package containing the id of the created switch.

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

##### example output
````json
{
 	"switch_id" : 123456
}
````

 <a name="update-switch"></a>
### update switch
`PUT /switch/<switch-id>`

Change the settings for an existing switch.

##### example request
`PUT https://api.cloudtrax.com/switch/12345`

##### example input

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

The API tries to create ranges for both tagged and untagged vlans. In this example the API changes tagged vlans to "1-4,8-10,12,991-994,4000-4094".

Allowed values for `poe.priority`:

* "low"
* "medium"
* high"
* critical"

Allowed values for `poe.limit_type`: 

* "auto"
* "manual"


 <a name="get-switch"></a>
### get switch
`GET /switch/<switch-id>`

Retrieve a switch. 

##### example request

`GET https://api.cloudtrax.com/switch/123456`

##### example output


````json
{
    "firmware": {
        "active_partition": 2,
        "flags": [],
        "version_partition_1": "IMG-0.00.04",
        "version_partition_2": "IMG-0.00.03" 
    },
    "id": 66,
    "poe": {
        "available_power_w": 64,
        "total_power_w": 150
    },
    "ports": [
        {
            "enable": false,
            "id": "trunk8",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk6",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk5",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk4",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk2",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "F1",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "sfp",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk1",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "6",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "8",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk7",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "5",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "3",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "4",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": false,
            "id": "trunk3",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "trunk",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "7",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "F2",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "sfp",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "2",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "10",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "eth",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "9",
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "eth",
            "untagged_vlans": "1",
            "vlan_id": 1
        },
        {
            "enable": true,
            "id": "1",
            "poe": {
                "enable": true,
                "power_limit_type": "manual",
                "power_limit_user_w": 30,
                "priority": "low",
                "status": "disabled" 
            },
            "status": "down",
            "tagged_vlans": "991-994",
            "type": "poe",
            "untagged_vlans": "1",
            "vlan_id": 1
        }
    ],
    "summary_info": {
        "active_ports": 2,
        "cloud_status": "up",
        "description": "Copy of a switch that already exists",
        "firmware_version": "v0.00.03",
        "gateway_ip": "10.0.0.1",
        "ip": "10.0.1.64",
        "last_checkin": "2016-07-06T23:11:48Z",
        "mac": "88:DC:96:10:FE:4A",
        "management_vlan": 1,
        "model": "OMS8",
        "name": "CP_JVS-PDX-OMS8",
        "total_ports": 12,
        "uptime_seconds": 16562,
        "connection_keeper_status": "disconnected" 
    }
}
````

port `status`, one of:

* "up"
* "down"

poe `status`, one of:

* "disabled"
* "searching"
* "delivering power"
* "test mode"
* "fault"
* "other fault"
* "requesting power"

 <a name="delete-switch"></a>
### delete switch
`DELETE /switch/<switch-id>`

Delete an existing switch.

##### example request
`DELETE https://api.cloudtrax.com/switch/123456`

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

