# Switch Management endpoints

Create, list, update, delete, and test for characteristics of switches.

functionality | method | endpoint
--- | --- | ---
[list switches](#list-switches) | GET | `/switch/network/<network-id>/list`
[create switch](#create-switch) | POST | `/switch/network/<network-id>`
[get switch](#get-switch) | GET | `/switch/<switch-id>`
[update switch](#update-switch) | PUT | `/switch/<switch-id>`
[delete switch](#delete-switch) | DELETE | `/switch/<switch-id>`
[reboot switch](#reboot-switch) | GET | `/switch/<switch-id>/reboot`
[reset port](#reset-port) | GET | `/switch/<switch-id>/port/<port-number>/reset`
[enable pairing for switch](#enable-pairing-for-switch) | GET | `/switch/<switch-id>/enable_pairing`
[expedite upgrade for switch](#expedite-upgrade-for-switch) | GET | `/switch/<switch-id>/expedite_upgrade`
[list switch-related network settings](#list-switch-related-settings) | GET |  `/switch/network/<network-id>/settings`
[update switch-related network settings](#update-switch-related-settings) | PUT | `/switch/network/<network-id>/settings`
[list allowed firmware](#list-allowed-firmware) | GET | `/switch/network/<network-id>/allowed_firmware`

 <a name="list-switches"></a>
### list switches

`GET /switch/network/<network-id>/list`

Retrieve a list of all switches belonging to the given network, with detailed information.

##### example request
`GET https://api.cloudtrax.com/switch/network/12345/list`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure. On success, the API returns a JSON package with a list of the switches.

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

Create a new switch entry for the specified network, with characteristics defined by the JSON package in the body of the HTTP Request.

##### example request
`POST https://api.cloudtrax.com/switch/network/12345`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure. On success, the API returns a JSON package containing the id of the created switch.

##### MAC and special error handling
If you have "master" access, the MAC field ("mac") can be used to transfer the switch to a different network. This makes handling MAC-related errors slightly more complicated. To wit:

* an error will be returned if the switch you're creating has the same MAC as an existing switch in the specified network.
* if you have "master" access and  the switch has the same MAC as an existing switch in *another* of your networks and "confirm_transfer" is true, the existing switch will be transferred to the network specified in the URL.
* otherwise an error will be returned.


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

Allowed values for `ports[n].status`:

* `"up"`
* `"down"`

Allowed values for `poe.status`:

* `"disabled"`
* `"searching"`
* `"delivering power"`
* `"test mode"`
* `"fault"`
* `"other fault"`
* `"requesting power"`


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

* `"low"`
* `"medium"`
* `"high"`
* `"critical"`

Allowed values for `poe.limit_type`:

* `"auto"`
* `"manual"`


<a name="delete-switch"></a>
### delete switch
`DELETE /switch/<switch-id>`

Delete an existing switch.

##### example request
`DELETE https://api.cloudtrax.com/switch/123456`

##### output

The API returns either an HTTP status code 200 on success or an HTTP error and JSON describing the error(s) in the case of a failure.

 <a name="reboot-switch"></a>
### reboot switch
`GET /switch/<switch-id>/reboot`

Reboot a switch.

##### example request
`GET https://api.cloudtrax.com/switch/123456/reboot`

##### output

The API returns either an HTTP status code 200 on success or 4xx in the case of a failure.

 <a name="reset-port"></a>
### reset port
`GET /switch/<switch-id>/port/<port-number>/reset`

Reset a port.

##### example request
`GET https://api.cloudtrax.com/switch/123456/port/1/reset`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure.

 <a name="enable-pairing-for-switch"></a>
### enable pairing for switch
`GET /switch/<switch-id>/enable_pairing`

##### example request
`GET https://api.cloudtrax.com/switch/123456/enable_pairing`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure.

 <a name="expedite-upgrade-for-switch"></a>
### expedite upgrade for switch
`GET /switch/<switch-id>/expedite_upgrade`

If upgrades are not disabled, this flag forces an update outside the normally scheduled maintenance window.

##### example request
`GET https://api.cloudtrax.com/switch/123456/expedite_upgrade`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure.

<a name="list-switch-related-settings"></a>
### list switch-related network settings
`GET /switch/network/<network_id>/settings`

##### example request
`GET https://api.cloudtrax.com/switch/network/123456/settings`

##### output

The API either returns HTTP status code 200 (success) if the request is successful, along with a JSON package of the settings, otherwise an error explaining what prevented the operation in the case of failure.

##### example output
````json
{
  "disable_upgrade": false,
  "enable_snmp": true,
  "community":"blabla",
  "firmware": [
    {
      "OMS24": {
        "build": "IMG-0.00.03",
        "tag": "v3"
      }
    },
    {
      "OMS8": {
        "build": "IMG-0.00.02",
        "tag": "phase1"
      }
    },
    {
      "OMS48": {
        "build": "IMG-0.00.02",
        "tag": "phase1"
      }
    }
  ]
}
````

###### Top level properties
field | description
--- | ---
`disable_upgrade` | indicates whether the switches on this network will automatically upgrade their firmware.
`enable_snmp` | indicates whether or not this network allows snmp communities.
`community` | the name assigned to this community.
`firmware` | lists the firmware currently running on each switch model on this network.

###### Firmware
This section is organized as a map of model name to properties.

field | description
----- | -----
`tag` | the firmware tag running on the relevant model on this network.
`build` | the firmware build running on the relevant model on this network.

##### output
On success the API responds with a status code 200. In the case of an error, the API responds with an explanation in JSON.

Note that when a community is updated, its id is changed. This reflects the way communities are handled at the switch level: the original community is deleted and a new one created in its place. Consequently the original id is no longer valid.

<a name="update-switch-related-settings"></a>
### update switch-related network settings
`PUT /switch/network/<network_id>/settings`

##### example request
`PUT https://api.cloudtrax.com/switch/network/123456/settings`

##### example input

````json
{
  "disable_upgrade": false,
  "enable_snmp": true,
  "communities": [
    {
      "name": "work_comm",
      "id": 11,
      "action": "update",
      "access": "write"
    }
  ],
  "firmware":
    {
      "OMS24":
        {
          "tag": "v3"
        },
      "OMS8":
        {
          "tag": "v4"
        }
    }
}
````

###### JSON detail
fields | type | description | required
----- | ----- | ----- | -----
`disable_upgrade` | bool | If true, this network's switches will not automatically upgrade their firmware. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | optional
`enable_snmp` | bool | This network allows the use of snmp communities. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | required
`community` | string | Desired name of this community.<br>:small_orange_diamond:Example value: `"comm1"` <br/>:small_orange_diamond:Allowed chars: `A-Z, a-z, 0-9` | required
`firmware` | string | Indicates any firmware changes being made on this network, described by a single JSON map with model names as keys.
`tag` | string | Which firmware should run on the associated model of switch. <br>:small_orange_diamond:Example value: `"phase1"` <br/>:small_orange_diamond:Allowed chars: `a-z, 0-9` | required

<a name="list_allowed-firmware"></a>
### list allowed firmware
`GET /switch/network/<network_id>/allowed_firmware`

##### example request
`GET https://api.cloudtrax.com/switch/network/123456/allowed_firmware`

##### output

The API returns a list of allowed firmware for the network, organized by model name.

##### example output
````json
{
  "allowed_firmware": {
    "OMS24": [
      {
        "tag": "phase1",
        "build": "IMG-0.00.02"
      },
      {
        "tag": "v3",
        "build": "IMG-0.00.03"
      },
      {
        "tag": "v4",
        "build": "IMG-0.00.04"
      },
      {
        "tag": "v6",
        "build": "IMG-0.00.06"
      },
      {
        "tag": "v7",
        "build": "IMG-0.00.07"
      }
    ],
    "OMS8": [
      {
        "tag": "phase1",
        "build": "IMG-0.00.02"
      },
      {
        "tag": "v3",
        "build": "IMG-0.00.03"
      },
      {
        "tag": "v4",
        "build": "IMG-0.00.04"
      },
      {
        "tag": "v5",
        "build": "IMG-0.00.05"
      },
      {
        "tag": "v6",
        "build": "IMG-0.00.06"
      },
      {
        "tag": "v7",
        "build": "IMG-0.00.07"
      }
    ],
    "OMS48": [
      {
        "tag": "phase1",
        "build": "IMG-0.00.02"
      },
      {
        "tag": "v3",
        "build": "IMG-0.00.03"
      },
      {
        "tag": "v4",
        "build": "IMG-0.00.04"
      },
      {
        "tag": "v6",
        "build": "IMG-0.00.06"
      },
      {
        "tag": "v7",
        "build": "IMG-0.00.07"
      }
    ]
  }
}
````
