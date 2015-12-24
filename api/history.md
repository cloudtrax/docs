# History endpoints

These endpoints aggregate traffic-based statistical data for a specified network over a given time period. This provides a historical overview of traffic changes over time. These statistics and time-sliced data can be viewed from several different perspectives:

* for all ssid's in the network
* by node (either singly or per all)
* by client (either singly or per all)

functionality | method | endpoint
--- | --- | --- 
[retrieve historical traffic statistics for all ssid's](#network) | GET | `/history/network/<network-id>`
[retrieve historical traffic statistics for all clients](#clients) | GET | `/history/network/<network-id>/clients`
[retrieve historical traffic statistics for a single client](#client) | GET | `/history/network/<network-id>/client/<client-id>`
[retrieve historical traffic statistics for all nodes](#nodes) | GET |` /history/network/<network-id>/nodes`
[retrieve historical traffic statistics for a single node](#node) | GET | `/history/network/<network-id>/node/<node-id>`

#### Reporting time span and sampling frequency
Each of the calls allows an optional query-string argument, `period=<timespan>`, to specify the overall time span during which statistics are to be gathered. Allowable values are:  `2hours`, `day`, `week`, and `month`. If the query-string is omitted, `day` is assumed.

The number of samples gathered per reporting period and the per-each-sample time span depend on the reporting period, as follows:

<a name="sampling-table"></a>
##### samples and intervals per reporting period
reporting period | number of samples | sample span
----- | ----- | -----
`2hours` | 24 | 5 minutes
`day` | 288 | 5 minutes
`week` | 288 | 35 minutes
`month` | 288 | 155 minutes

	
 <a name="network"></a>
### network
`GET /history/network/<network-id>?period=<period>`

This endpoint provides an aggregation of traffic statistics by ssid for all ssid's in the specified network over the given time period.

<a name="query-string-args"></a>
##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`

##### example request
`GET /history/network/12554?period=2hours`

##### example output
Output for the above query. For this endpoint, the sample array is partitioned into "rows" of smaller "time slices" (only two out of 24 being shown here). Each row is a snapshot of both upload and download data sampled over the time span of the sample. 

In this and the other API calls below, "bup" and "bdown" stand for bytes up and bytes down, respectively (total bytes uploaded and downloaded during the time slice), while "rup" and "rdown" stand for rate up and rate down, respectively (ie, upload and download throughput during the same time span).

````json
{
    "clients": {
        "ssid1": 5,
        "ssid2": 0
    },
    "rows": [
        [
            "2013-11-24T13:50:00Z",
            {
                "ssid1": {
                    "traffic": {
                        "udp": {
                            "bup": 278,
                            "bdown": 193,
                            "rup": 278,
                            "rdown": 193
                        },
                        "tcp": {
                            "bup": 278,
                            "bdown": 193,
                            "rup": 278,
                            "rdown": 193
                        }
                    },
                    "users": 1
                },
                "ssid2": {
                    "traffic": {
                        "netbios": {
                            "bup": 1872,
                            "bdown": 0,
                            "rup": 1872,
                            "rdown": 0
                        }
                    },
                    "users": 1
                }
            }
        ],
        [
            "2013-11-24T13:55:00Z",
            {
                "ssid1": {
                    "traffic": {
                        "udp": {
                            "bup": 278,
                            "bdown": 193,
                            "rup": 278,
                            "rdown": 193
                        },
                        "tcp": {
                            "bup": 278,
                            "bdown": 193,
                            "rup": 278,
                            "rdown": 193
                        },
                        "users": 1
                    }
                }
            }
        ]
    ]
}
````

 <a name="clients"></a>
### clients
`GET /history/network/<network-id>/clients?period=<period>`

This endpoint returns a list of all client devices connected to the specified network, with traffic statistics aggregated over the selected time span. 

##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`

##### example request
`GET https://api.cloudtrax.com/history/network/12345/clients?period=week`

##### example output
````json
{
  "clients": [
    {
      "1c:99:4c:7b:ae:fe": {
        "name": "android-77b812edd3097a81",
        "cid": "31792",
        "signal": {
          "antenna1": -75,
          "antenna2": -76
        },
        "mcs": {
          "tx": 3
        },
        "bitrate": {
          "rx": 6,
          "tx": 60
        },
        "channel_width": "40",
        "band": "5.8",
        "last_seen": "2014-02-10T20:20:17Z",
        "last_name": "Garage OM5P",
        "last_node": "ac:86:74:00:0b:f0",
        "os": "android",
        "os_version": "",
        "blocked": 0,
        "link": "unknown",
        "traffic": {
          "ssid1": {
            "bup": 40,
            "bdown": 50
          },
          "ssid2": {
            "bup": 28278,
            "bdown": 90538
          }
        }
      }
    },
    {
      "00:23:15:b3:dc:94": {
        "name": "Owner-PC",
        "cid": "49671",
        "signal": {
          "antenna1": -75,
          "antenna2": -76
        },
        "mcs": {
          "tx": 3
        },
        "bitrate": {
          "rx": 6,
          "tx": 60
        },
        "channel_width": "40",
        "band": "5.8",
        "last_seen": "2014-02-10T20:20:17Z",
        "last_name": "Garage OM5P",
        "last_node": "ac:86:74:00:0b:f0",
        "os": "android",
        "os_version": "",
        "blocked": 1,
        "link": "wired",
          "traffic": {
          "ssid1": {
            "bup": 40,
            "bdown": 50
          },
          "ssid2": {
            "bup": 28278,
            "bdown": 90538
          }
        }
      }
    }
  ]
}
````

 <a name="client"></a>
### client
`GET /history/network/<network-id>/client/<client-id>?period=<period>`

This endpoint returns traffic statistics for the specified client device on the specified network aggregated over the selected time span.

##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`


##### example output

````json
{
    "cid": "543",
    "traffic": [
        {
            "time": "2014-02-10T09:14:25Z",
            "ssid1": {
                "rup": 40,
                "rdown": 50
            },
            "ssid2": {
                "rup": 28278,
                "rdown": 90538
            }
        },
        {
            "time": "2014-02-10T09:19:25Z",
            "ssid1": {
                "rup": 30,
                "rdown": 10
            }
        }
    ]
}
````
 <a name="nodes"></a>
### nodes
`GET /history/network/<network-id>/nodes?period=<period>`

This endpoint returns a list of all nodes connected to the specified network, with the  traffic statistics for each node aggregated over the selected time span.

##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`

##### example request
`https://api.cloudtrax.com/history/network/12345/nodes?period=2hours`

##### example output

This sample output shows two time slices out of 24 for the above example request. The JSON consists of an array of nodes, with each node element displaying data on the upload and download traffic volume for each ssid on the node, as well as time-sliced arrays containing "metrics", "outages", and "checkins" data for each node. Specifically,

* * "traffic": reports bytes uploaded and downloaded per ssid, aggregated over the preceding 24 hours.
* "metrics": measure how fast traffic was moving at the timestamped time, sampled over a 5-minute time span. These are aggregated over the preceding 24 hours (independent of `period`).
* "checkins": report timestamped instances of the nodes' attempts to report traffic statistics and other state back to the CloudTrax servers, and to obtain updated configurations from them.
* "outages": time and status of each outage, if any.
 
````json
{
	"nodes": [{
		"31792": {
			"traffic": {
				"ssid1": {
					"bup": 40,
					"bdown": 50,
					"users": 1
				},
				"ssid2": {
					"bup": 28278,
					"bdown": 90538,
					"users": 7
				}
			},
			"metrics": [{
				"time": "2014-07-29T11:20:00Z",
				"speed": 11476
			}, {
				"time": "2014-07-29T11:25:00Z",
				"speed": 11786
			}],
			"checkins": [{
				"time": "2014-07-28T12:20:00Z",
				"status": "repeater"
			}, {
				"time": "2014-07-28T12:25:00Z"
			}]
		}
	}]
}
````

 <a name="node"></a>
### node
 `GET /history/network/<network-id>/node/<node-id>?period=<period>`
 
This endpoint returns traffic statistics for the specified node aggregated every 5 minutes over the selected time span. 

##### query-string arguments
argument | allowable values | required | note
---- | ---- | ---- | -----
`period` | `2hours`, `day`, `week`, `month` | optional | default is `day`

##### example request
`https://api.cloudtrax.com/history/network/12345/node/295?period=week`

##### example output

This JSON sample shows the array of time-sliced traffics statistics (upload and download speeds) for the node with id ("nid") 295. This example shows only a single sample.

````json
{
    "nid": "295",
    "traffic": [
        {
            "time": "2014-02-10T09:14:25Z",
            "ssid1": {
                "rup": 40,
                "rdown": 50
            },
            "ssid2": {
                "rup": 28278,
                "rdown": 90538
            }
        },
        {
            "time": "2014-02-10T09:19:25Z",
            "ssid1": {
                "rup": 30,
                "rdown": 10
            }
        }
    ]
}
````