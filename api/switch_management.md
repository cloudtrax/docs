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
[list switch-related network settings](#list-switch-related-settings) | GET | `/switch/network/<network-id>/settings
[update switch-related network settings](#update-switch-related-settings) | PUT | `/switch/network/<network-id>/settings`
[list allowed firmware](#list-allowed-firmware) | GET | `/switch/network/<network-id>/allowed_firmware`
[get switch snmp traps](#get-switch-snmp-traps) | GET | `/switch/<switch-id>/snmp_traps`
[update switch snmp traps](#update-switch-snmp-traps) | PUT | `/switch/<switch-id>/snmp_traps`
[get switch acls](#get-switch-acls) | GET | `/switch/<switch-id>/acl`
[create switch acls](#create-switch-acls) | POST | `/switch/<switch-id>/acl`
[update switch acls](#update-switch-acls) | PUT | `/switch/<switch-id>/acl`
[delete switch acls](#delete-switch-acls) | DELETE | `/switch/<switch-id>/acl`




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
    "switch_id":123,
    "id":123,
    "summary_info":{
        "mac":"AC:86:74:00:00:00",
        "model":"OMS8",
        "name":"switch",
        "description":"",
        "warranty_first_checkin":"2017-04-06T18:41:50Z",
        "network_first_add":"2017-03-20T17:43:20Z",
        "expedite_upgrade":false,
        "monitor_mode":false,
        "connection_keeper_status":"connected",
        "last_checkin":"2018-07-17T22:45:18Z",
        "last_checkin_host":"cloud-switch.cloudtrax.com",
        "firmware_version":"v01.03.19",
        "ip":"10.20.20.108",
        "gateway_ip":"10.20.20.1",
        "uptime_seconds":4935777,
        "management_vlan":1,
        "cloud_status":"pairing",
        "total_ports":12,
        "active_ports":1
    },
    "firmware":{
        "active_partition":2,
        "version_partition_1":"IMG-01.03.15",
        "version_partition_2":"IMG-01.03.19",
        "flags":[

        ]
    },
    "igmp":{
        "enable":false,
        "version":"v2",
        "vlans":[
            {
                "id":991,
                "enable":true
            },
            {
                "id":992,
                "enable":false
            },
            {
                "id":993,
                "enable":false
            },
            {
                "id":994,
                "enable":false
            },
            {
                "id":1,
                "enable":false
            }
        ]
    },
    "voice_vlan":{
        "enable":false,
        "mode":-1,
        "vlan":991,
        "enable_priority":true,
        "priority":5,
        "aging_time":1440,
        "ouis":[
            {
                "address":"00:01:E3",
                "description":"Siemens"
            },
            {
                "address":"00:03:6B",
                "description":"Cisco"
            },
            {
                "address":"00:09:6E",
                "description":"Avaya"
            },
            {
                "address":"00:0F:E2",
                "description":"H3C"
            },
            {
                "address":"00:60:B9",
                "description":"NEC\/Philips"
            },
            {
                "address":"00:D0:1E",
                "description":"Pingtel"
            },
            {
                "address":"00:E0:75",
                "description":"Veritel"
            },
            {
                "address":"00:E0:BB",
                "description":"3COM"
            }
        ]
    },
    "qos":{
        "enable":false,
        "scheduling_method":"strict",
        "trust_mode":"8021p",
        "cos":[
            {
                "id":0,
                "queue":2
            },
            {
                "id":1,
                "queue":1
            },
            {
                "id":2,
                "queue":3
            },
            {
                "id":3,
                "queue":4
            },
            {
                "id":4,
                "queue":5
            },
            {
                "id":5,
                "queue":6
            },
            {
                "id":6,
                "queue":7
            },
            {
                "id":7,
                "queue":8
            }
        ],
        "dscp":[
            {
                "id":0,
                "queue":1
            },
            {
                "id":1,
                "queue":1
            },
            {
                "id":2,
                "queue":1
            },
            {
                "id":3,
                "queue":1
            },
            {
                "id":4,
                "queue":1
            },
            {
                "id":5,
                "queue":1
            },
            {
                "id":6,
                "queue":1
            },
            {
                "id":7,
                "queue":1
            },
            {
                "id":8,
                "queue":2
            },
            {
                "id":9,
                "queue":2
            },
            {
                "id":10,
                "queue":2
            },
            {
                "id":11,
                "queue":2
            },
            {
                "id":12,
                "queue":2
            },
            {
                "id":13,
                "queue":2
            },
            {
                "id":14,
                "queue":2
            },
            {
                "id":15,
                "queue":2
            },
            {
                "id":16,
                "queue":3
            },
            {
                "id":17,
                "queue":3
            },
            {
                "id":18,
                "queue":3
            },
            {
                "id":19,
                "queue":3
            },
            {
                "id":20,
                "queue":3
            },
            {
                "id":21,
                "queue":3
            },
            {
                "id":22,
                "queue":3
            },
            {
                "id":23,
                "queue":3
            },
            {
                "id":24,
                "queue":4
            },
            {
                "id":25,
                "queue":4
            },
            {
                "id":26,
                "queue":4
            },
            {
                "id":27,
                "queue":4
            },
            {
                "id":28,
                "queue":4
            },
            {
                "id":29,
                "queue":4
            },
            {
                "id":30,
                "queue":4
            },
            {
                "id":31,
                "queue":4
            },
            {
                "id":32,
                "queue":5
            },
            {
                "id":33,
                "queue":5
            },
            {
                "id":34,
                "queue":5
            },
            {
                "id":35,
                "queue":5
            },
            {
                "id":36,
                "queue":5
            },
            {
                "id":37,
                "queue":5
            },
            {
                "id":38,
                "queue":5
            },
            {
                "id":39,
                "queue":5
            },
            {
                "id":40,
                "queue":6
            },
            {
                "id":41,
                "queue":6
            },
            {
                "id":42,
                "queue":6
            },
            {
                "id":43,
                "queue":6
            },
            {
                "id":44,
                "queue":6
            },
            {
                "id":45,
                "queue":6
            },
            {
                "id":46,
                "queue":6
            },
            {
                "id":47,
                "queue":6
            },
            {
                "id":48,
                "queue":7
            },
            {
                "id":49,
                "queue":7
            },
            {
                "id":50,
                "queue":7
            },
            {
                "id":51,
                "queue":7
            },
            {
                "id":52,
                "queue":7
            },
            {
                "id":53,
                "queue":7
            },
            {
                "id":54,
                "queue":7
            },
            {
                "id":55,
                "queue":7
            },
            {
                "id":56,
                "queue":8
            },
            {
                "id":57,
                "queue":8
            },
            {
                "id":58,
                "queue":8
            },
            {
                "id":59,
                "queue":8
            },
            {
                "id":60,
                "queue":8
            },
            {
                "id":61,
                "queue":8
            },
            {
                "id":62,
                "queue":8
            },
            {
                "id":63,
                "queue":8
            }
        ]
    },
    "lldp":{
        "enable":true,
        "transmission_interval":30,
        "holdtime_multiplier":4,
        "reinitialization_delay":2,
        "transmit_delay":2
    },
    "jumbo_frame":{
        "mtu":1522
    },
    "stp":{
        "force_version":"rstp",
        "enable":true,
        "root_bridge":{
            "root_address":"AC:86:74:00:00:00",
            "device_name":"",
            "priority":8192,
            "cost":30000,
            "port":"9",
            "forward_delay_s":15,
            "maximum_age_s":20,
            "hello_time_s":2
        }
    },
    "snmp":{
        "enable":true,
        "community":"blabla"
    },
    "acl":{
        "enable":true
    },
    "ports":[
        {
            "id":"1",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":true,
                "priority":"low",
                "power_limit_type":"manual",
                "power_limit_user_w":20,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":211223,
                    "ucast":1778,
                    "nucast":159,
                    "discard":0,
                    "mcast":132,
                    "bcast":27
                },
                "tx_bytes":{
                    "all":1482487,
                    "ucast":131,
                    "nucast":11717,
                    "discard":0,
                    "mcast":7958,
                    "bcast":3759
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"10",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"eth",
            "description":"",
            "link_speed_mode":3,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"2",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":false,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"3",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":true,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":2843016,
                    "ucast":12514,
                    "nucast":515,
                    "discard":0,
                    "mcast":474,
                    "bcast":41
                },
                "tx_bytes":{
                    "all":10329207,
                    "ucast":4069,
                    "nucast":77968,
                    "discard":0,
                    "mcast":52727,
                    "bcast":25241
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"4",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":true,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"5",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":true,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"6",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":false,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"7",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":false,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":1138622,
                    "ucast":3997,
                    "nucast":95,
                    "discard":0,
                    "mcast":72,
                    "bcast":23
                },
                "tx_bytes":{
                    "all":13051322,
                    "ucast":9155,
                    "nucast":1925,
                    "discard":0,
                    "mcast":1673,
                    "bcast":252
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"8",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"poe",
            "poe":{
                "enable":true,
                "priority":"low",
                "power_limit_type":"auto",
                "power_limit_user_w":30,
                "power_draw_w":0,
                "status":"searching"
            },
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"9",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"eth",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"up",
            "link_speed_neg":1,
            "uplink":true,
            "statistics":{
                "rx_bytes":{
                    "all":2085515712,
                    "ucast":622406,
                    "nucast":20161696,
                    "discard":0,
                    "mcast":16082727,
                    "bcast":4078969
                },
                "tx_bytes":{
                    "all":547190941,
                    "ucast":812376,
                    "nucast":205740,
                    "discard":0,
                    "mcast":205556,
                    "bcast":184
                }
            },
            "connected_device_mac_list":[
                "00:00:5E:00:01:04",
                "00:01:2E:6E:77:09",
                "00:01:2E:78:0E:5D",
                "00:04:F2:5F:99:37",
                "00:08:A2:0A:8B:9E",
                "00:23:04:54:05:00",
                "00:24:9B:1E:F1:03",
                "0C:4D:E9:99:F3:87",
                "0C:C4:7A:C0:0D:E2",
                "18:66:DA:2D:F5:18",
                "18:A6:F7:00:65:37",
                "28:16:AD:44:81:85",
                "52:54:00:86:89:E4",
                "5C:CF:7F:4F:07:F5",
                "74:D4:35:C4:FF:BC",
                "88:DC:96:10:FE:4A",
                "9C:F3:87:59:7E:7D",
                "A0:63:91:79:B9:20",
                "A8:60:B6:01:B7:D3",
                "A8:60:B6:06:5A:CD",
                "AC:86:74:83:81:AA",
                "AC:86:74:83:81:DE",
                "AC:86:74:83:86:49",
                "AC:86:74:84:0A:43",
                "AC:86:74:84:CD:80",
                "AC:86:74:8B:DF:80",
                "AC:86:74:8C:85:60",
                "AC:86:74:8F:E0:A0",
                "AC:86:74:94:0B:C0",
                "AC:86:74:97:57:60",
                "AC:86:74:9E:25:C0",
                "AC:86:74:AB:52:80",
                "AC:86:74:AB:7D:A0",
                "AC:86:74:B8:58:F9",
                "AC:86:74:C4:6B:80",
                "AC:86:74:C4:EF:40",
                "AC:86:74:C6:C1:90",
                "AC:86:74:C6:C9:C0",
                "AC:86:74:C6:C9:D0",
                "AC:86:74:CB:53:A0",
                "AC:86:74:CB:53:C0",
                "AC:86:74:CC:8E:80",
                "AC:86:74:D9:16:E0",
                "AE:86:74:9E:25:D5",
                "AE:86:74:B8:58:FF",
                "B8:27:EB:04:03:13",
                "B8:27:EB:CA:96:93",
                "B8:27:EB:FC:EC:F3",
                "BA:BE:05:8C:59:A8",
                "BA:BE:11:56:31:14",
                "BA:BE:17:44:D6:42",
                "BA:BE:2A:A1:06:D2",
                "BA:BE:45:69:20:3D",
                "BA:BE:50:CF:45:B6",
                "BA:BE:77:14:EC:60",
                "BA:BE:79:08:DB:9F",
                "BA:BE:7D:6D:71:3F",
                "BA:BE:AA:BC:A7:FB",
                "BA:BE:AF:12:82:56",
                "BA:BE:B6:B3:57:09",
                "BA:BE:B8:F1:F8:D5",
                "BA:BE:C6:12:1B:C0",
                "BA:BE:C6:3F:FA:73",
                "BA:BE:CD:C7:E6:3F",
                "BA:BE:CF:75:A1:77",
                "BA:BE:DA:E6:E0:AC",
                "BA:BE:DC:71:67:B4",
                "BA:BE:E2:4A:16:11",
                "BA:BE:F3:2C:D2:31",
                "BA:BE:F4:8A:2A:CC",
                "C0:EA:E4:75:19:C9",
                "C8:5B:76:FB:A8:D7",
                "E0:4F:43:58:FB:4E"
            ]
        },
        {
            "id":"F1",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"sfp",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"F2",
            "enable":true,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"sfp",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "rate_limit":{
                "enable_tx":false,
                "enable_rx":false,
                "tx_limit_kbps":0,
                "rx_limit_kbps":0
            },
            "mirror":{
                "enable_ingress_state":false,
                "enable_session_state":false,
                "tx_port_ids":[

                ],
                "rx_port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk1",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk2",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk3",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":2,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk4",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk5",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk6",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk7",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        },
        {
            "id":"trunk8",
            "enable":false,
            "tagged_vlans":"991-994",
            "vlan_id":1,
            "enable_isolation":false,
            "untagged_vlans":"1",
            "type":"trunk",
            "description":"",
            "link_speed_mode":0,
            "enable_flow_control":false,
            "acl_mac":"",
            "acl_ipv4":"",
            "enable_voice_vlan":false,
            "voice_cos_mode":0,
            "qos":{
                "trust":false,
                "cos_value":0
            },
            "aggregation":{
                "port_ids":[

                ]
            },
            "status":"down",
            "link_speed_neg":0,
            "uplink":false,
            "statistics":{
                "rx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                },
                "tx_bytes":{
                    "all":0,
                    "ucast":0,
                    "nucast":0,
                    "discard":0,
                    "mcast":0,
                    "bcast":0
                }
            },
            "connected_device_mac_list":[

            ]
        }
    ],
    "poe":{
        "total_power_w":150,
        "available_power_w":150
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
### list switch-related network settings [DEPRECATED]
`GET /switch/network/<network_id>/settings`

##### example request
`GET https://api.cloudtrax.com/switch/network/123456/settings`

##### output

The API either returns HTTP status code 200 (success) if the request is successful, along with a JSON package of the settings, otherwise an error explaining what prevented the operation in the case of failure.

##### example output
````json
{
  "disable_upgrade": false,
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
`firmware` | lists the firmware currently running on each switch model on this network.

###### Firmware
This section is organized as a map of model name to properties.

field | description
----- | -----
`tag` | the firmware tag running on the relevant model on this network.
`build` | the firmware build running on the relevant model on this network.

##### output
On success the API responds with a status code 200. In the case of an error, the API responds with an explanation in JSON.

<a name="update-switch-related-settings"></a>
### update switch-related network settings [DEPRECATED]
`PUT /switch/network/<network_id>/settings`

##### example request
`PUT https://api.cloudtrax.com/switch/network/123456/settings`

##### example input

````json
{
  "disable_upgrade": false,
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

<a name="get-switch-snmp-traps"></a>
### get switch snmp traps
`GET /switch/<switch_id>/snmp_traps`

##### example request
`GET https://api.cloudtrax.com/switch/123/snmp_traps`

##### output

Get SNMP traps per switch.

##### example output
````json
{
	"traps":[
		{
			"host":"localhost",
			"community_name":"foobar",
			"notify_type":"informs",
			"retry":1,
			"timeout":10,
			"udp":16,
			"version":"v2c"
		}
	]
}
````

<a name="update-switch-snmp-traps"></a>
### update switch snmp traps
`PUT /switch/<switch_id>/snmp_traps`

Update SNMP traps per switch.

##### example request
`PUT https://api.cloudtrax.com/switch/123/snmp_traps`

##### example input
````json
{
	"traps":[
		{
			"host":"localhost",
			"community_name":"foobar"
		}
	]
}
````

<a name="get-switch-acls"></a>
### get switch acls
`POST /switch/<switch_id>/acl`

Get ACLs or ACEs per switch.

##### example request
`POST https://api.cloudtrax.com/switch/123/acl`

##### example output
````json
{
	"mac_acl":[
		{"name":"foo", "assigned":true}
	],
	"mac_ace":[
		{
			"acl_name":"foo",
			"id":654,
			"sequence":1,
			"action":"permit",
			"source_mac":"00:00:00:00:00:00",
			"destination_mac":"00:00:00:00:00:01",
			"vlan_id":56,
			"priority":2,
			"ethertype":"EEDD",
			"checksum":"abc124"
		}
	],
	"ipv4_acl":[
		{"name":"bar", "assigned":true}
	],
	"ipv4_ace":[
		{
			"acl_name":"bar",
			"id":655,
			"sequence":1,
			"action":"permit",
			"protocol":"any",
			"type_of_service":-1,
			"source_ip":"192.*",
			"destination_ip":"172.*",
			"checksum":"def456"
		}
	],
}
````

<a name="create-switch-acls"></a>
### create switch acls
`POST /switch/<switch_id>/acl`

Create ACLs or ACEs per switch.

##### example request
`POST https://api.cloudtrax.com/switch/123/acl`

##### example input
````json
{
	"mac_acl":[
		{"name":"bar"}
	],
	"mac_ace":[
		{
			"acl_name":"bar",
			"action":"permit",
			"source_mac":"00:00:00:00:00:00",
			"destination_mac":"11:11:11:11:11:11"
		}
	]
}
````

<a name="update-switch-acls"></a>
### update switch acls
`PUT /switch/<switch_id>/acl`

Update ACLs or ACEs per switch.

##### example request
`PUT https://api.cloudtrax.com/switch/123/acl`

##### example input
````json
{
	"ipv4_acl":[
		{"name":"foo"}
	],
	"ipv4_ace":[
		{
			"id":9876,
			"acl_name":"foo",
			"action":"permit",
			"protocol":"icmp"
		}
	]
}
````

<a name="delete-switch-acls"></a>
### delete switch acls
`DELETE /switch/<switch_id>/acl`

Delete ACLs or ACEs per switch.

##### example request
`DELETE https://api.cloudtrax.com/switch/123/acl`

##### example input
````json
{
	"mac_acl":[
		{"name":"foo"}
	]
}
````
