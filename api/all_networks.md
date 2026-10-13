# All Networks endpoints

These endpoints report on every network your account can access in a single request, rather than one network at a time. They return each network's device inventory, online and offline counts and check-in history, totals across the whole account, and the devices your account owns that have not yet been added to a network. They are the endpoints behind the All Networks page in Datto Network Manager.

functionality | method | endpoint
--- | --- | ---
[get networks overview](#get-overview) | POST | `/networks/overview`
[get network stats](#get-stats) | POST | `/networks/stats`
[get network devices](#get-devices) | POST | `/networks/devices`
[get network device history](#get-history) | POST | `/networks/history`
[get unassigned devices](#get-unassigned) | POST | `/networks/devices/unassigned`
[hide or unhide an unassigned device](#hide-unassigned) | POST | `/networks/devices/unassigned/hidden`

<a name="conventions"></a>
### Common conventions

#### Requests

All six endpoints use `POST` and take their parameters as a JSON object in the request body. Every parameter is optional except on [hide or unhide an unassigned device](#hide-unassigned). To use the defaults, send an empty object (`{}`) or no body.

Parameters can also be passed in the query string, and a query-string value takes precedence over the same parameter in the body. If you use the query string, it must be part of the path you sign when generating the [Signature header](README.md#signature-header), or the request fails with error 1 ("Invalid auth"). Passing parameters in the body avoids this.

Filter values are strings. A list is a single comma-separated string, and a single network ID is still a string: `"network_id": "12345"`, not `"network_id": 12345`. A filter given as a number or an array is ignored, and the request then covers all of your networks.

#### Which networks are included

Responses cover every network your user has admin or read-only access to. To limit a request to particular networks, pass `network_id`:

parameter | type | description
--- | --- | ---
`network_id` | string | Comma-separated network IDs, for example `"12345,12346"`. Every ID must be a network you can access, otherwise the request fails with error 12047.

To keep individual responses smaller for an account with a very large number of networks, you can request your networks in batches, passing a different subset of network IDs in each call. [list networks](network_management.md#list-networks) returns your network IDs.

#### The `networks` collection

[get network stats](#get-stats), [get network devices](#get-devices), [get network device history](#get-history) and [get unassigned devices](#get-unassigned) return their results in a `networks` object keyed by network ID. Each value contains the network's `network_id` and `network_name`, plus that endpoint's data. `networks` is always a JSON object, and is `{}` when nothing matches. Read the networks from the object's values; the order of its keys is not significant.

#### Device types and statuses

These endpoints use the following device types, both in responses and in `device_type` filters:

device type | description
--- | ---
`access_point` | Access Points
`switch` | Switches
`router` | Routers
`managed_power` | Managed power devices (power strips and PDUs)

A `device_type` filter is a comma-separated string of these values, for example `"access_point,switch"`. Any other value fails with error 15000.

A device's `status` is one of:

status | meaning
--- | ---
`online` | The device has checked in, and is not currently down.
`offline` | The device has checked in before, but is currently down.
`new` | The device has never checked in.

Device objects also carry an `online` boolean. It reflects the device's stored state, and can be `true` for a device that has never checked in, so use `status` to determine whether a device is online. Wherever devices are counted as online or offline, `new` devices are counted as offline.

#### Lifecycle and subscription

Device objects include `lifecycle` and `subscription` properties, which take the values described under [Lifecycle](node_management.md#lifecycle) and [Subscription](node_management.md#subscription) in the Node Management endpoints.

#### Masked MAC addresses

For devices on networks where you have read-only access (role ID 4), MAC addresses are partially masked, with the fourth and fifth octets replaced by `xx`, for example `ac:86:74:xx:xx:01`. In [get unassigned devices](#get-unassigned), MAC addresses are masked when your account role is Network Viewer (role ID 11).

#### Errors

Errors are returned as described in [Errors](README.md#errors). The errors specific to these endpoints are:

code | returned by | cause
--- | --- | ---
1008 | hide or unhide an unassigned device | `mac` is not a valid MAC address.
1032 | get network device history | `period` is not one of the allowed values.
12047 | any endpoint that takes `network_id` | A network ID is not one you can access.
15000 | any endpoint that takes `device_type` | A device type is not one of the allowed values.
15001 | get network devices | A status is not one of the allowed values.
15002 | get network stats | `search` contains characters that are not allowed, or is longer than 255 characters.
30002 | any | Your account role does not allow the request, or, for hide or unhide an unassigned device, the device is not owned by your account.
60002 | hide or unhide an unassigned device | `mac` is missing.
190000 | hide or unhide an unassigned device | `hidden` is missing, or is not `true` or `false`.

See the [Error codes](error_codes.md) document for the full list.

<a name="get-overview"></a>
### get networks overview
`POST /networks/overview`

Get device totals across your networks: how many devices of each type are online and offline, and how their subscriptions break down. The response also includes the account's Datto Secure Edge (DSE) subscriptions.

##### input

parameter | type | description
--- | --- | ---
`network_id` | string | Optional. Comma-separated network IDs. See [Which networks are included](#conventions).

##### example request

````
POST https://api.cloudtrax.com/networks/overview
````

##### input example
```` json
{}
````

##### output

field | type | description
--- | --- | ---
`devices` | array | One entry per device type, always in the order `switch`, `access_point`, `router`, `managed_power`.
`devices[].device_type` | string | The device type.
`devices[].online_count` | integer | Number of devices of this type that are online.
`devices[].offline_count` | integer | Number of devices of this type that are offline, including devices that have never checked in.
`devices[].subscription_count` | object | Number of devices of this type with an `active`, `expiring` or `expired` subscription. A subscription is `expired` once its end date has passed, `expiring` if it ends within the next 30 days, and `active` otherwise. Evergreen subscriptions are always `active`. Which count a subscription falls into depends only on its dates, not on its `is_active` flag, so an inactive subscription that has not yet ended, or an inactive evergreen one, is counted as `active`. Devices without subscription information are not counted.
`dse_subscriptions` | array or null | The account's Datto Secure Edge subscriptions, sorted by `subscription_name`. An empty array if there are none, and `null` if the account is not linked to a Datto reseller or the subscriptions could not be retrieved.
`dse_subscriptions[].subscription_name` | string | For example `"Professional - 1 Year"` or `"Professional - Monthly"`.
`dse_subscriptions[].is_active` | boolean | Whether the subscription is active. A subscription with an end date is active until that date. One without an end date is active if it has a start date, and otherwise only if any of its licenses are assigned.
`dse_subscriptions[].subscription_type` | string | `"renewable"` for a fixed-term subscription, or `"evergreen"` for a monthly subscription.
`dse_subscriptions[].subscription_length` | integer | Length of the subscription in months; `1` for an evergreen subscription.
`dse_subscriptions[].subscription_start` | string or null | Start date, in RFC 3339 format. `null` if not known.
`dse_subscriptions[].subscription_end` | string or null | End date, in RFC 3339 format. `null` for an evergreen subscription.
`dse_subscriptions[].licenses_count` | integer | Total number of licenses.
`dse_subscriptions[].unassigned_licenses_count` | integer | Number of licenses not yet assigned.

##### example output
```` json
{
	"devices": [
		{
			"device_type": "switch",
			"online_count": 6,
			"offline_count": 0,
			"subscription_count": {
				"active": 1,
				"expiring": 0,
				"expired": 0
			}
		},
		{
			"device_type": "access_point",
			"online_count": 3,
			"offline_count": 0,
			"subscription_count": {
				"active": 0,
				"expiring": 0,
				"expired": 0
			}
		},
		{
			"device_type": "router",
			"online_count": 2,
			"offline_count": 0,
			"subscription_count": {
				"active": 0,
				"expiring": 0,
				"expired": 0
			}
		},
		{
			"device_type": "managed_power",
			"online_count": 3,
			"offline_count": 1,
			"subscription_count": {
				"active": 2,
				"expiring": 0,
				"expired": 0
			}
		}
	],
	"dse_subscriptions": [
		{
			"subscription_name": "Professional - 3 Years",
			"is_active": false,
			"subscription_type": "renewable",
			"subscription_length": 36,
			"subscription_start": "2023-04-03T10:02:12Z",
			"subscription_end": "2026-04-03T10:02:12Z",
			"licenses_count": 10,
			"unassigned_licenses_count": 10
		},
		{
			"subscription_name": "Professional - Monthly",
			"is_active": false,
			"subscription_type": "evergreen",
			"subscription_length": 1,
			"subscription_start": null,
			"subscription_end": null,
			"licenses_count": 15,
			"unassigned_licenses_count": 15
		}
	]
}
````

<a name="get-stats"></a>
### get network stats
`POST /networks/stats`

Get the number of online and offline devices on each of your networks.

##### input

parameter | type | description
--- | --- | ---
`network_id` | string | Optional. Comma-separated network IDs. See [Which networks are included](#conventions).
`device_type` | string | Optional. Comma-separated [device types](#conventions) to count. Networks with no devices of these types are still returned, with zero counts.
`search` | string | Optional. Count only devices matching this device name or MAC address. A device name matches if it contains the search text, and the comparison is case-sensitive. A MAC address must be given in full and match exactly, for example `ac:86:74:aa:aa:01`. The search text may contain only the letters A–Z (in either case), numbers, spaces, hyphens and underscores, unless it is a complete MAC address, and may be up to 255 characters. Otherwise the request fails with error 15002. Unless `network_id` is also given, networks with no matching devices are left out of the response.

##### example request

````
POST https://api.cloudtrax.com/networks/stats
````

##### input example
```` json
{}
````

##### output

Returns a [`networks` object](#conventions) whose values have the following fields:

field | type | description
--- | --- | ---
`network_id` | integer | The network's ID.
`network_name` | string | The network's name.
`online_count` | integer | Number of devices on the network that are online.
`offline_count` | integer | Number of devices on the network that are offline, including devices that have never checked in.

##### example output

Network 12348 has no devices, so it is returned with zero counts.

```` json
{
	"networks": {
		"12345": {
			"network_id": 12345,
			"network_name": "Main Office",
			"online_count": 12,
			"offline_count": 0
		},
		"12346": {
			"network_id": 12346,
			"network_name": "Warehouse",
			"online_count": 0,
			"offline_count": 1
		},
		"12347": {
			"network_id": 12347,
			"network_name": "Branch Office",
			"online_count": 2,
			"offline_count": 0
		},
		"12348": {
			"network_id": 12348,
			"network_name": "New Site",
			"online_count": 0,
			"offline_count": 0
		}
	}
}
````

<a name="get-devices"></a>
### get network devices
`POST /networks/devices`

Get the devices on each of your networks, with their current status and details, and each network's position.

##### input

parameter | type | description
--- | --- | ---
`network_id` | string | Optional. Comma-separated network IDs. See [Which networks are included](#conventions).
`device_type` | string | Optional. Comma-separated [device types](#conventions) to return.
`status` | string | Optional. Comma-separated [statuses](#conventions) to return: any of `online`, `offline` and `new`. Any other value fails with error 15001.

Without a `device_type` or `status` filter, every network is returned, including networks with no devices, which have an empty `devices` array. With either filter, only matching devices are returned, and networks with no matching devices are left out.

##### example request

````
POST https://api.cloudtrax.com/networks/devices
````

##### input example
```` json
{
	"network_id": "12345,12347"
}
````

##### output

Returns a [`networks` object](#conventions) whose values have the following fields:

field | type | description
--- | --- | ---
`network_id` | integer | The network's ID.
`network_name` | string | The network's name.
`latitude` | number | The latitude of the centre of the area covered by the network's Access Points, or `0` if none of them has a location. An Access Point counts as having no location if its latitude and longitude are both `0`. Always calculated from all of the network's Access Points, whatever `device_type` and `status` are set to.
`longitude` | number | The longitude of the same point, or `0` if none of the network's Access Points has a location.
`devices` | array | The network's devices, described below.

Each element of `devices` has the following fields:

field | type | description
--- | --- | ---
`device_type` | string | The [device type](#conventions).
`device_id` | integer | The device's ID.
`mac` | string | The device's MAC address, in lowercase. Partially [masked](#conventions) if you have read-only access to the network.
`device_name` | string | The device's name.
`hardware` | string | The device's model.
`firmware_version` | string | The device's firmware version, for example `"7.0.22"`. An empty string if not known.
`description` | string | The device's description. Present for Access Points and Switches only.
`latitude` | number | The device's latitude. Always `0` for device types other than Access Points.
`longitude` | number | The device's longitude. Always `0` for device types other than Access Points.
`online` | boolean | The device's stored online state. Use `status` instead; see [Device types and statuses](#conventions).
`status` | string | `online`, `offline` or `new`; see [Device types and statuses](#conventions).
`last_checkin` | string | Time of the device's last check-in, in RFC 3339 format. An empty string if it has never checked in.
`uptime` | integer | The device's uptime, in **milliseconds**. `0` if it has never checked in.
`lifecycle` | object or null | See [Lifecycle](node_management.md#lifecycle).
`subscription` | object or null | See [Subscription](node_management.md#subscription).
`enable_troubleshooting_until` | boolean or string | `false` if no troubleshooting window has been set for the device. Otherwise the time the device's most recent troubleshooting window ends, or ended, for example `"2026-04-01 15:15:35"`. This is not in RFC 3339 format, and may be in the past. Always `false` for Routers and managed power devices.

##### example output

Network 12345 is placed at its only Access Point. Network 12347 has no Access Points, so its position is `0`, `0`.

```` json
{
	"networks": {
		"12345": {
			"network_id": 12345,
			"network_name": "Main Office",
			"latitude": 45.5227,
			"longitude": -122.6786,
			"devices": [
				{
					"device_type": "access_point",
					"device_id": 520001,
					"mac": "ac:86:74:aa:aa:01",
					"device_name": "Lobby AP",
					"hardware": "A42",
					"firmware_version": "7.0.22",
					"description": "",
					"latitude": 45.5227,
					"longitude": -122.6786,
					"online": true,
					"status": "online",
					"last_checkin": "2026-10-02T13:49:33Z",
					"uptime": 97605000,
					"lifecycle": {
						"end_of_sale": "2021-05-31T00:00:00Z",
						"end_of_life": "2026-05-31T00:00:00Z"
					},
					"subscription": null,
					"enable_troubleshooting_until": false
				},
				{
					"device_type": "switch",
					"device_id": 10001,
					"mac": "ac:86:74:aa:aa:02",
					"device_name": "Core Switch",
					"hardware": "E8",
					"firmware_version": "1.3.27",
					"description": "",
					"latitude": 0,
					"longitude": 0,
					"online": true,
					"status": "online",
					"last_checkin": "2026-10-02T13:49:16Z",
					"uptime": 2520770000,
					"lifecycle": {
						"end_of_sale": "2022-09-30T00:00:00Z",
						"end_of_life": "2027-12-31T00:00:00Z"
					},
					"subscription": {
						"is_active": true,
						"start_date": "2024-05-01T00:00:00Z",
						"end_date": "2027-05-01T00:00:00Z",
						"term": "yearly",
						"term_length": 36,
						"is_evergreen": false
					},
					"enable_troubleshooting_until": false
				}
			]
		},
		"12347": {
			"network_id": 12347,
			"network_name": "Branch Office",
			"latitude": 0,
			"longitude": 0,
			"devices": [
				{
					"device_type": "router",
					"device_id": 30001,
					"mac": "ac:86:74:aa:aa:03",
					"device_name": "Edge Router",
					"hardware": "D200",
					"firmware_version": "1.3.6",
					"latitude": 0,
					"longitude": 0,
					"online": true,
					"status": "online",
					"last_checkin": "2026-10-02T13:49:02Z",
					"uptime": 90721000,
					"lifecycle": {
						"end_of_sale": "2022-12-31T00:00:00Z",
						"end_of_life": "2027-12-31T00:00:00Z"
					},
					"subscription": null,
					"enable_troubleshooting_until": false
				}
			]
		}
	}
}
````

<a name="get-history"></a>
### get network device history
`POST /networks/history`

Get each device's check-in history for the last 24 hours, and the number of clients it has seen.

##### input

parameter | type | description
--- | --- | ---
`network_id` | string | Optional. Comma-separated network IDs. See [Which networks are included](#conventions).
`device_type` | string | Optional. Comma-separated [device types](#conventions) to return.
`period` | string | Optional. The time period for `clients_count`: one of `2hours`, `day`, `week` or `month`. Defaults to `day`. Any other value fails with error 1032. `period` does not affect `checkins`.

Only networks with at least one device of the requested types are returned. Unlike [get network devices](#get-devices) and [get network stats](#get-stats), networks with no devices are left out.

##### example request

````
POST https://api.cloudtrax.com/networks/history
````

##### input example
```` json
{
	"network_id": "12345",
	"period": "week"
}
````

##### output

Returns a [`networks` object](#conventions) whose values have the fields `network_id`, `network_name` and `devices`. Each element of `devices` has the following fields:

field | type | description
--- | --- | ---
`device_type` | string | The [device type](#conventions).
`device_id` | integer | The device's ID, matching `device_id` in [get network devices](#get-devices).
`mac` | string | The device's MAC address. Partially [masked](#conventions) if you have read-only access to the network.
`checkins` | array | The device's check-ins over the last 24 hours, as 288 entries one five-minute interval apart, oldest first. This is always the last 24 hours, whatever `period` is.
`checkins[].time` | string | Start of the five-minute interval, in RFC 3339 format.
`checkins[].status` | string | Present only if the device checked in during the interval, and absent if it did not. Its value reflects the device's operating mode, for example `"gateway"` or `"repeater"` for an Access Point; treat its presence as indicating a check-in.
`clients_count` | integer | Number of clients seen on the device during `period`. Always `0` for managed power devices.

##### example output

The example is shortened: each `checkins` array has 288 entries. The managed power device did not check in during the interval starting at 13:55.

```` json
{
	"networks": {
		"12345": {
			"network_id": 12345,
			"network_name": "Main Office",
			"devices": [
				{
					"device_type": "access_point",
					"device_id": 520001,
					"mac": "ac:86:74:aa:aa:01",
					"checkins": [
						{
							"time": "2026-10-01T13:50:00Z",
							"status": "gateway"
						},
						{
							"time": "2026-10-01T13:55:00Z",
							"status": "gateway"
						},
						/* ... */
						{
							"time": "2026-10-02T13:45:00Z",
							"status": "gateway"
						}
					],
					"clients_count": 2
				},
				{
					"device_type": "managed_power",
					"device_id": 40001,
					"mac": "ac:86:74:aa:aa:04",
					"checkins": [
						{
							"time": "2026-10-01T13:50:00Z",
							"status": "gateway"
						},
						{
							"time": "2026-10-01T13:55:00Z"
						},
						/* ... */
						{
							"time": "2026-10-02T13:45:00Z",
							"status": "gateway"
						}
					],
					"clients_count": 0
				}
			]
		}
	}
}
````

<a name="get-unassigned"></a>
### get unassigned devices
`POST /networks/devices/unassigned`

Get the devices that are registered to your account but are not on any network.

##### input

parameter | type | description
--- | --- | ---
`device_type` | string | Optional. Comma-separated [device types](#conventions) to return.
`sort_by` | string | Optional. Sort devices by `mac` or `model`. Defaults to `mac`.
`sort_order` | string | Optional. `asc` or `desc`. Defaults to `asc`.

An unrecognized `sort_by` or `sort_order` value is not an error: the default is used instead.

##### example request

````
POST https://api.cloudtrax.com/networks/devices/unassigned
````

##### input example
```` json
{
	"sort_by": "model"
}
````

##### output

Returns a [`networks` object](#conventions) with a single entry, keyed `"0"`, whose `network_id` is `0` and `network_name` is `"Unassigned"`. Its `devices` array contains every unassigned device, including devices you have [hidden](#hide-unassigned). Each element of `devices` has the following fields:

field | type | description
--- | --- | ---
`mac` | string | The device's MAC address, as stored. Unlike [get network devices](#get-devices), this may be in upper or lower case. Partially [masked](#conventions) if your account role is Network Viewer.
`device_name` | string | Always an empty string.
`hardware` | string | The device's model.
`device_type` | string | The [device type](#conventions).
`serial_number` | string | The device's serial number. May be an empty string.
`hidden` | boolean | Whether the device has been [hidden](#hide-unassigned).
`ip` | string | Always an empty string.
`firmware_version` | string | Always an empty string.
`last_checkin` | integer | Always `0`.
`uptime` | integer | Always `0`.
`lifecycle` | object or null | See [Lifecycle](node_management.md#lifecycle).
`subscription` | object or null | See [Subscription](node_management.md#subscription).

##### example output
```` json
{
	"networks": {
		"0": {
			"network_id": 0,
			"network_name": "Unassigned",
			"devices": [
				{
					"mac": "AC:86:74:AA:AA:11",
					"device_name": "",
					"hardware": "D200",
					"device_type": "router",
					"serial_number": "A1234567891",
					"hidden": true,
					"ip": "",
					"firmware_version": "",
					"last_checkin": 0,
					"uptime": 0,
					"lifecycle": {
						"end_of_sale": "2022-12-31T00:00:00Z",
						"end_of_life": "2027-12-31T00:00:00Z"
					},
					"subscription": {
						"is_active": true,
						"start_date": "2026-01-01T00:00:00Z",
						"end_date": "2027-01-01T00:00:00Z",
						"term": "yearly",
						"term_length": 12,
						"is_evergreen": false
					}
				},
				{
					"mac": "ac:86:74:aa:aa:10",
					"device_name": "",
					"hardware": "DMP-PDU-12R-C13",
					"device_type": "managed_power",
					"serial_number": "A1234567890",
					"hidden": false,
					"ip": "",
					"firmware_version": "",
					"last_checkin": 0,
					"uptime": 0,
					"lifecycle": null,
					"subscription": null
				}
			]
		}
	}
}
````

<a name="hide-unassigned"></a>
### hide or unhide an unassigned device
`POST /networks/devices/unassigned/hidden`

Mark an unassigned device as hidden, or clear the mark. Hiding a device does not remove it from [get unassigned devices](#get-unassigned): the device is still returned, with `hidden` set to `true`, so that you can filter it out yourself.

The device must be registered to your account. This endpoint is not available to users whose account role is Network Viewer.

##### input

parameter | type | description
--- | --- | ---
`mac` | string | Required. The device's MAC address.
`hidden` | boolean | Required. `true` to hide the device, or `false` to unhide it.

##### example request

````
POST https://api.cloudtrax.com/networks/devices/unassigned/hidden
````

##### input example
```` json
{
	"mac": "ac:86:74:aa:aa:10",
	"hidden": true
}
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error. On success, the API returns the device's MAC address and its new hidden state.

##### example output
```` json
{
	"mac": "ac:86:74:aa:aa:10",
	"hidden": true
}
````

##### example error output

Sent without `hidden`:

```` json
{
	"errors": [
		{
			"code": 190000,
			"message": "Missing or invalid hidden state field. Hidden state must be either true or false."
		}
	]
}
````
