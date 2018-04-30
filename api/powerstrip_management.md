# Power Strip Management endpoints

Create, list, update, delete, and test for characteristics of power strips.

functionality | method | endpoint
--- | --- | ---
[list power strips](#list-powerstrips) | GET | `/powerstrip/network/<network-id>/list`
[create power strip](#create-powerstrip) | POST | `/powerstrip/network/<network-id>`
[get power strip](#get-powerstrip) | GET | `/powerstrip/<powerstrip-id>`


 <a name="list-powerstrips"></a>
### list power strips

`GET /powerstrips/network/<network-id>/list`

Retrieve a list of all power strips belonging to the given network, with detailed information.

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
