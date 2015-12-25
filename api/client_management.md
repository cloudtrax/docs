# Client Management endpoints

This API component provides endpoints for blocking clients and editing client names.


functionality | method | endpoint
--- | --- | ---
[get blocked clients](#get-blocked) | GET | `/client/network/<network-id>/block`
[block/unblock clients](#set-blocked) | PUT | `/client/network/<network-id>/block`
[edit clients](#edit-client) | PUT | `/client/network/<network-id>/edit`

<a name="get-blocked"></a>
### get blocked clients
`GET /client/network/<network-id>/block`

Get all blocked clients for a network.

##### example request

````
GET https://api.cloudtrax.com/client/network/123456/block
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error. On success, the API returns all blocked clients.

##### example output
```` json
{
    "clients": {
        "00:00:00:00:00:01": {
            "name": "unittest client",
            "name_override": "test overwrite",
            "cid": "V28hO3syPho0szL5dqDeoRDBtdv9_cofjMygMbpmstQ=",
            "signal": {
                "antenna1": -64,
                "antenna2": -74
            },
            "mcs": {
                "tx": 6
            },
            "bitrate": {
                "rx": 52,
                "tx": 58.5
            },
            "channel_width": 20,
            "band": "2.4GHz",
            "last_seen": "2015-10-04T23:18:58Z",
            "last_name": "andreas test",
            "last_node": "ac:86:74:00:00:04",
            "link": "wireless",
            "wifi_mode": "ac",
            "blocked": true,
            "os": "IOS",
            "os_version": "",
            "traffic": {
                "ssid2": {
                    "bup": 60000000,
                    "bdown": 54000000
                },
                "ssid1": {
                    "bup": 24000000,
                    "bdown": 18000000
                }
            }
        },
        "AB:CD:EF:14:3B:04": {
            "name": "",
            "name_override": "",
            "cid": "8DEnHhpg5sRvpnrumoCiuKQXJ-gA0Hmeyr2aHKXBMdE=",
            "signal": {

            },
            "mcs": {
                "tx": 0
            },
            "bitrate": {
                "rx": 0,
                "tx": 0
            },
            "channel_width": 0,
            "band": "",
            "last_seen": "",
            "last_name": "",
            "last_node": "",
            "link": "",
            "wifi_mode": "",
            "blocked": true,
            "os": "IOS",
            "os_version": "",
            "traffic": {
                "ssid3": {
                    "bup": 2400000,
                    "bdown": 1800000
                },
                "ssid2": {
                    "bup": 6000000,
                    "bdown": 5400000
                }
            }
        }
    }
}
````

<a name="set-blocked"></a>
### block/unblock client
Block/unblock a client.

````
PUT /client/network/<network-id>/block
````

##### example request

````
PUT https://api.cloudtrax.com/client/network/12345/block
````
##### example input


````json
{
  "block":1,
  "client_ids": [
       "_4G6of2NARub8yqm-YJR3RveJYK1SI07uXhLygJSkOs=",
       "V28hO3syPho0szL5dqDeoRDBtdv9_cofjMygMbpmstQ=",
   ]
}
````

##### output

The API returns HTTP status code 200 (success) or an HTTP error and JSON describing the error.


<a name="edit-client"></a>
### override client name
````
PUT /client/network/<network-id>/edit
````

##### example request

````
PUT https://api.cloudtrax.com/client/network/12345/edit
````

##### example input


````json
{
    "clients": [
        {
            "id": "_4G6of2NARub8yqm-YJR3RveJYK1SI07uXhLygJSkOs=",
            "name": "new-name"
        },
        {
            "id": "V28hO3syPho0szL5dqDeoRDBtdv9_cofjMygMbpmstQ=",
            "name": "another-new-name"
        }
    ]
}
````

##### output

The API returns HTTP status code 200 (success) or an HTTP error and JSON describing the error.

