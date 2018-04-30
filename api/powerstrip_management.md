# Power Strip Management endpoints

Create, list, update, delete, and test for characteristics of power strips.

functionality | method | endpoint
--- | --- | ---
[list power strips](#list-powerstrips) | GET | `/powerstrip/network/<network-id>/list`
[create power strip](#create-powerstrip) | POST | `/powerstrip/network/<network-id>`


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
