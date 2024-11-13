# Power Strip Management endpoints

Create, list, update, delete, and test for characteristics of power strips.

functionality | method | endpoint
--- | --- | ---
[list power strips](#list-powerstrips) | GET | `/powerstrip/network/<network-id>/list`
[create power strip](#create-powerstrip) | POST | `/powerstrip/network/<network-id>`
[get power strip](#get-powerstrip) | GET | `/powerstrip/<powerstrip-id>`
[update power strip](#update-powerstrip) | PUT | `/powerstrip/<powerstrip-id>`
[delete power strip](#delete-powerstrip) | DELETE | `/powerstrip/<powerstrip-id>`
[get power strip usage](#get-powerstrip-usage) | GET | `/powerstrip/<powerstrip-id>/usage`
[checkin power strip](#checkin-powerstrip) | GET | `/powerstrip/<powerstrip-id>/checkin`
[reboot power strip](#reboot-powerstrip) | GET | `/powerstrip/<powerstrip-id>/reboot`
[pair power strip](#pair-powerstrip) | GET | `/powerstrip/<powerstrip-id>/enable_pairing`
[reset port](#reset-port) | GET | `/powerstrip/<powerstrip-id>/port/<port-number>/reset`
[list power-strip-related network settings](#list-powerstrip-related-settings) | GET |  `/powerstrip/network/<network-id>/settings`
[update power-strip-related network settings](#update-powerstrip-related-settings) | PUT | `/powerstrip/network/<network-id>/settings`
[expedite upgrade for network power strip](#expedite-upgrade-for-powerstrip) | GET | `/powerstrip/network/<networkid-id>/expedite_upgrade`
[list allowed firmware](#list-allowed-firmware) | GET | `/powerstrip/network/<network-id>/firmware_versions`




 <a name="list-powerstrips"></a>
### list power strips

`GET /powerstrip/network/<network-id>/list`

Retrieve a list of all power strips belonging to the given network, with detailed information.

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
`GET https://api-v3.cloudtrax.com/powerstrip/network/12345/list`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure. On success, the API returns a JSON package with a list of the switches.

##### example output

````json
{
  "powerstrips": [
    {
      "powerstrip_id": 1,
      "mac": "ac:86:74:00:00:00",
      "name": "Pat's Desk MP60",
      "model": "P60",
      "ip": "",
      "created": "2018-03-23T17:12:16Z",
      "firmware_version": "6.4.6-c3b6f768496b73473b45a06b478bf2e75c6cdd5e",
      "enabled_ports": 6,
      "port_count": 6,
      "last_checkin": "2018-04-30T19:29:11.232Z",
      "need_pairing": false,
      "cloud_status": "up",
      "role": "repeater",
      "mesh_ip": "1.1.1.1",
      "lifecycle": {
        "end_of_sale": null,
        "end_of_life": null,
      },
      "subscription": null,
      "surge_protection_active": true,
      "voltage_v": 116,
      "frequency_hz": 60,
      "uptime_seconds": 1024671,
      "active_ports": 3
    },
    {
      "powerstrip_id": 2,
      "mac": "ac:86:74:00:00:01",
      "name": "PS test 1",
      "model": "MP60",
      "ip": null,
      "created": "2018-04-23T23:08:41Z",
      "firmware_version": "",
      "enabled_ports": 6,
      "port_count": 6,
      "last_checkin": "",
      "need_pairing": false,
      "cloud_status": "down",
      "role": "repeater",
      "mesh_ip": "",
      "lifecycle": {
        "end_of_sale": "2021-04-30T00:00:00Z",
        "end_of_life": "2026-04-30T00:00:00Z",
      },
      "subscription": {
        "is_active": false,
        "start_date": "2021-01-01T00:00:00Z",
        "end_date": null,
        "term": "monthly",
        "term_length": 1,
        "is_evergreen": true
      },
      "surge_protection_active": false,
      "voltage_v": 0,
      "frequency_hz": 0,
      "uptime_seconds": 0,
      "active_ports": 0
    }
  ]
}
````

 <a name="create-powerstrip"></a>
### create power strip
`POST /powerstrip/network/<network-id>`

Create a new power strip entry for the specified network, with characteristics defined by the JSON package in the body of the HTTP Request.

##### example request
`POST https://api-v3.cloudtrax.com/powerstrip/network/12345`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure. On success, the API returns a JSON package containing the id of the created switch.

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
 	"powerstrip_id" : 123456
}
````

<a name="get-powerstrip"></a>
### get power strip
`GET /powerstrip/<powerstrip-id>`

Retrieve a power strip.

##### example request

`GET https://api-v3.cloudtrax.com/powerstrip/123456`

##### example output


````json
{
  "powerstrip_id": 1,
  "network_id": 123,
  "mac": "ac:86:74:00:00:00",
  "name": "Pat's Desk MP60",
  "description": "",
  "model": "P60",
  "created": "2018-03-23T17:12:16Z",
  "firmware_version": "6.4.6-c3b6f768496b73473b45a06b478bf2e75c6cdd5e",
  "enable_pairing": false,
  "enable_power_switch": false,
  "enable_connection_check": false,
  "enable_connection_check_during_maintenance": false,
  "connection_check_max_retries": 0,
  "connection_check_time_between_retries": 60,
  "lifecycle": null,
  "subscription": null,
  "enable_mesh": false,
  "last_modified": "2018-04-30T20:29:17Z",
  "ip": "",
  "last_checkin": "2018-04-30T20:29:17.26Z",
  "need_pairing": false,
  "cloud_status": "up",
  "role": "repeater",
  "mesh_ip": "1.1.1.1",
  "surge_protection_active": true,
  "voltage_v": 115,
  "frequency_hz": 60,
  "uptime_seconds": 1028276,
  "active_ports": 3,
  "ports": [
    {
      "port_id": 1,
      "label": "Device A",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0.1,
      "apparent_power_va": 16.4,
      "active_power_w": 8.5,
      "reactive_power_var": 1.7,
      "power_factor": 0.51
    },
    {
      "port_id": 2,
      "label": "2",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0,
      "apparent_power_va": 0,
      "active_power_w": 0,
      "reactive_power_var": 0,
      "power_factor": 0
    },
    {
      "port_id": 3,
      "label": "Laptop Hub",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0.2,
      "apparent_power_va": 31.4,
      "active_power_w": 19.3,
      "reactive_power_var": 2.4,
      "power_factor": 0.61
    },
    {
      "port_id": 4,
      "label": "Monitor ",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0.3,
      "apparent_power_va": 35.9,
      "active_power_w": 25.5,
      "reactive_power_var": 3.1,
      "power_factor": 0.71
    },
    {
      "port_id": 5,
      "label": "More",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0,
      "apparent_power_va": 0,
      "active_power_w": 0,
      "reactive_power_var": 0,
      "power_factor": 0
    },
    {
      "port_id": 6,
      "label": "6",
      "enable": true,
      "connection_check_name": null,
      "current_a": 0,
      "apparent_power_va": 0,
      "active_power_w": 0,
      "reactive_power_var": 0,
      "power_factor": 0
    }
  ],
  "schedule": [
    {
      "powerstrip_port_schedule_id": 67523,
      "port_ids": [
        3,
        4
      ],
      "days_of_week": [
        "fri"
      ],
      "hour": 18,
      "minute": 0,
      "action": "port_disable"
    },
    {
      "powerstrip_port_schedule_id": 349834,
      "port_ids": [
        3,
        4
      ],
      "days_of_week": [
        "mon"
      ],
      "hour": 7,
      "minute": 30,
      "action": "port_enable"
    },
    {
      "powerstrip_port_schedule_id": 876324234,
      "port_ids": [
        3
      ],
      "days_of_week": [
        "mon",
        "tue",
        "wed",
        "thu",
        "fri"
      ],
      "hour": 7,
      "minute": 45,
      "action": "port_reset"
    }
  ],
  "connection_checks": [
    {
      "connection_check_name": "  test",
      "hostname_1": "nist.gov",
      "hostname_2": null,
      "hostname_3": null,
      "hostname_4": null
    },
    {
      "connection_check_name": "Default List",
      "hostname_1": "google.com",
      "hostname_2": "amazon.com",
      "hostname_3": "datto.com",
      "hostname_4": "facebook.com"
    }
  ]
}
````

<a name="update-powerstrip"></a>
### update power strip
`PUT /powerstrip/<powerstrip-id>`

Change the settings for an existing power strip.

##### example request
`PUT https://api-v3.cloudtrax.com/powerstrip/12345`

##### example input

````json
{
  "name": "Pat's Desk MP60",
  "description": "",
  "enable_pairing": false,
  "enable_power_switch": false,
  "enable_connection_check": false,
  "enable_connection_check_during_maintenance": false,
  "connection_check_max_retries": 0,
  "connection_check_time_between_retries": 60,
  "enable_mesh": false,
  "ports": [
    {
      "port_id": 1,
      "label": "Device A",
      "enable": true,
      "connection_check_name": "test"
    },
    {
      "port_id": 2,
      "label": "2",
      "enable": true,
      "connection_check_name": "Default List"
    },
    {
      "port_id": 3,
      "label": "Laptop Hub",
      "enable": true,
      "connection_check_name": null
    },
    {
      "port_id": 4,
      "label": "Monitor ",
      "enable": true,
      "connection_check_name": null
    },
    {
      "port_id": 5,
      "label": "More",
      "enable": true,
      "connection_check_name": null
    },
    {
      "port_id": 6,
      "label": "6",
      "enable": true,
      "connection_check_name": null
    }
  ],
  "schedule": [
    {
      "port_ids": [
        3,
        4
      ],
      "days_of_week": [
        "fri"
      ],
      "hour": 18,
      "minute": 0,
      "action": "port_disable"
    }
  ],
  "connection_checks": [
    {
      "connection_check_name": "  test",
      "hostname_1": "nist.gov",
      "hostname_2": null,
      "hostname_3": null,
      "hostname_4": null
    },
    {
      "connection_check_name": "Default List",
      "hostname_1": "google.com",
      "hostname_2": "amazon.com",
      "hostname_3": "datto.com",
      "hostname_4": "facebook.com"
    }
  ]
}
````

<a name="delete-powerstrip"></a>
### delete power strip
`DELETE /powerstrip/<powerstrip-id>`

Delete an existing power strip.

##### example request
`DELETE https://api-v3.cloudtrax.com/powerstrip/123456`

##### output

The API returns either an HTTP status code 200 on success or an HTTP error and JSON describing the error(s) in the case of a failure.

<a name="get-powerstrip-usage"></a>
### get power strip usage
`GET /powerstrip/<powerstrip-id>/usage?period=<period>`

Retrieve a power strip usage data.

##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`

##### example request

`GET https://api-v3.cloudtrax.com/powerstrip/123456/usage?period=day`

##### example output


````json
{
  "usage": [
    {
      "time": "2018-04-29T23:00:00Z",
      "ports": {
        "1": {
          "active_power_w": 8,
          "voltage_v": 118,
          "current_a": 0.1
        },
        "2": {
          "active_power_w": 0,
          "voltage_v": 118,
          "current_a": 0
        },
        "3": {
          "active_power_w": 0,
          "voltage_v": 118,
          "current_a": 0
        },
        "4": {
          "active_power_w": 0,
          "voltage_v": 118,
          "current_a": 0
        },
        "5": {
          "active_power_w": 0,
          "voltage_v": 118,
          "current_a": 0
        },
        "6": {
          "active_power_w": 0,
          "voltage_v": 118,
          "current_a": 0
        }
      },
      "memory_available": 0,
      "role": ""
    },
    ...
  ]
}
````

 <a name="checkin-powerstrip"></a>
### checkin power strip
`GET /powerstrip/<powerstrip-id>/checkin`

Check in a power strip to the cloud.

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/123456/checkin`

##### output

The API returns either an HTTP status code 200 on success or 4xx in the case of a failure.

 <a name="reboot-powerstrip"></a>
### reboot power strip
`GET /powerstrip/<powerstrip-id>/reboot`

Reboot a power strip.

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/123456/reboot`

##### output

The API returns either an HTTP status code 200 on success or 4xx in the case of a failure.

 <a name="pair-powerstrip"></a>
### pair power strip
`GET /powerstrip/<powerstrip-id>/checkin`

Pair a power strip to the cloud.

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/123456/enable_pairing`

##### output

The API returns either an HTTP status code 200 on success or 4xx in the case of a failure.

 <a name="reset-port"></a>
### reset port
`GET /powerstrip/<powerstrip-id>/port/<port-number>/reset`

Reset a port.

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/123456/port/1/reset`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure.

<a name="list-powerstrip-related-settings"></a>
### list power-strip-related network settings
`GET /powerstrip/network/<network_id>/settings`

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/network/123456/settings`

##### output

The API either returns HTTP status code 200 (success) if the request is successful, along with a JSON package of the settings, otherwise an error explaining what prevented the operation in the case of failure.

##### example output
````json
{
  "enable_power_switch": false,
  "enable_mesh": false,
  "enable_upgrade": false,
  "firmware_tag": "stable"
}
````

###### Top level properties
field | description
--- | ---
`enable_power_switch` | indicates whether the power switches on the power strips will function.
`enable_mesh` | indicates whether the power strips on this network will mesh.
`enable_upgrade` | indicates whether the power strips on this network will automatically upgrade their firmware.
`firmware_tag` | lists the firmware currently running on each switch model on this network.

##### output
On success the API responds with a status code 200. In the case of an error, the API responds with an explanation in JSON.

<a name="update-powerstrip-related-settings"></a>
### update power-strip-related network settings
`PUT /powerstrip/network/<network_id>/settings`

##### example request
`PUT https://api-v3.cloudtrax.com/powerstrip/network/123456/settings`

##### example input

````json
{
  "enable_power_switch": false,
  "enable_mesh": false,
  "enable_upgrade": false,
  "firmware_tag": "stable"
}
````

###### JSON detail
fields | type | description | required
----- | ----- | ----- | -----
`enable_power_switch` | bool | If true, this network's power strips power switches will do something when pressed. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | optional
`enable_mesh` | bool | If true, this network's power strips will mesh. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | optional
`enable_upgrade` | bool | If true, this network's switches will automatically upgrade their firmware.<br>:small_orange_diamond:Example value: `"comm1"` <br/>:small_orange_diamond:Allowed entries: `true/false` | optional
`firmware_tag` | string | Which firmware should run on the associated model of switch. <br>:small_orange_diamond:Example value: `"phase1"` <br/>:small_orange_diamond:Allowed chars: `a-z, 0-9` | optional

 <a name="expedite-upgrade-for-powerstrip"></a>
### expedite upgrade for switch
`GET /powerstrip/network/<network-id>/expedite_upgrade`

If upgrades are not disabled, this flag forces an update for all power strips on the network outside the normally scheduled maintenance window.

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/network/123456/expedite_upgrade`

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error(s) in the case of failure.

<a name="list_allowed-firmware"></a>
### list allowed firmware
`GET /powerstrip/network/<network_id>/firmware_versions`

##### example request
`GET https://api-v3.cloudtrax.com/powerstrip/network/123456/firmware_versions`

##### output

The API returns a list of allowed firmware for the network.

##### example output
````json
{
  "firmware_versions": [
    {
      "tag_id": "1",
      "tag": "stable",
      "build": ""
    },
    {
      "tag_id": "6",
      "tag": "beta",
      "build": ""
    }
  ]
}
````
