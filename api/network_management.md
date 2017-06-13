# Network Management endpoints

This API component provides a large number of endpoints for creating, listing, and deleting networks, as well as viewing and editing network settings, checking for network existence, determining whether upgrades are necessary, and uploading and deleting splash pages.

functionality | method | endpoint
--- | --- | ---
[create network](#create-network) | POST | `/network`
[delete network](#delete-network) | DELETE | `/network/<network-id>`
[search networks by name](#search-network) | GET | `/network/search?by_name=<name>`
[list networks](#list-networks) | GET | `/network/list`
[get allowed channels](#get-allowed-channels) | GET | `/network/<network-id>/allowed_channels`
[get network settings](#get-settings) | GET | `/network/<network-id>/settings`
[set network settings](#set-settings) | PUT |`/network/<network-id>/settings`
[upload splash pages](#upload-splash) | POST | `/network/<network-id>/file`
[delete splash pages](#delete-splash) | DELETE | `/network/<network-id>/file/<file-id>`

 <a name="create-network"></a>
### create network
`POST /network`

Create a new network and associate it with the user sending the `create` request.

Characteristics of the new network are defined by the JSON package that comprises the body of the HTTP request.  At a minimum, the following four fields are required. The password must be at least 8 characters long:

* `name`
* `password`
* `timezone`
* `country`

##### example request

````
POST https://api.cloudtrax.com/network
POST https://api-v2.cloudtrax.com/network/networkgroup/<id>
````

##### input example
```` json
{
    "name" : "my-test-network-#2",
    "password" : "pw345678",
    "email" : "support@open-mesh.com",
    "location" : "Portland",
    "timezone" : "America/Los_Angeles",
    "country_code" : "US"
}
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error. On success, the API returns the id of the newly created network.

##### example output
```` json
{
    "id" : 123456
}
````

 <a name="delete-network"></a>
### delete network
Delete a network.

````
DELETE /network/<network id>
````

##### example request

````
DELETE https://api.cloudtrax.com/network/12345
````
##### example output

Note the use of error code 1009 to indicate success.

````json
{
	"code": 1009,
	"message": "Success.",
	"context": "delete_network",
	"values": {

	}
}
````

<a name="search-network"></a>
### search networks by name
````
GET /network/search?by_name=<name>
````

##### example request

````
GET https://api.cloudtrax.com/network/search?by_name=%25unittest%25
````

##### output

The output returns an array of networks, with each network identified by name and id.

##### example output

```` json
{
	"networks": [
		{
			"name": "unittest1",
			"id": 114338
		},
		{
			"name": "unittest2",
			"id": 114339
		},
		{
			"name": "unittest3",
			"id": 17275
		}	]
}
````

<a name="list-networks"></a>
### list networks
````
GET /network/list
````

##### example request

````
GET https://api.cloudtrax.com/network/list
GET https://api-v2.cloudtrax.com/network/list
````

##### output

The API returns the list of network name/id pairs, or an empty list if no network is associated with the user sending the request.

##### example output

```` json
{
	"networks": [
		{
			"name": "hk_network",
			"id": 135587,
			"network_id": 135587, /* api-v2. new property name. same as "id" */
			"networkgroup_id": 1, /* api-v2 */
			"networkgroup_name": "Network Group #1", /* api-v2 */
			"role_id": "Network Group #1", /* api-v2. role ID for the requester */
			"latitude": 49.44026050000000083,
			"longitude": -123.6724020000000053,
			"down_repeater": 1,
			"down_gateway": 0,
			"spare_nodes": 0,
			"node_count": 2
		}
	]
}
````

 <a name="get-allowed-channels"></a>
### get allowed channels
`GET /network/<network-id>/allowed_channels`

Returns all allowed channels for a network for a particular country, defaulting to the country code for the network that's stored in the database. You can specify a different country with the query-string "country" parameter, using one of the two-letter country codes specified by [ISO 3166](http://www.iso.org/iso/home/standards/country_codes.htm).

You can also query this information on a per-node basis; see [Node Management API](./node_management.md#get-allowed-channels-for-node).

##### example request

````
GET https://api.cloudtrax.com/network/135587/allowed_channels
````
##### example output

```` json
{
	"channels": {
		"2_4GHz": [
			{
				"channel": 10,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 9,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 8,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 7,
				"ht_modes": [
					"HT40+", "HT40-", "HT20"
				]
			},
			{
				"channel": 6,
				"ht_modes": [
					"HT40+", "HT40-", "HT20"
				]
			},
			{
				"channel": 5,
				"ht_modes": [
					"HT40+", "HT40-", "HT20"
				]
			},
			{
				"channel": 4,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 3,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 11,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 2,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 1,
				"ht_modes": [
					"HT40+", "HT20"
				]
			}
		],
		"5GHz": [
			{
				"channel": 161,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 153,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 149,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 48,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 46,
				"ht_modes": [
					"HT20"
				]
			},
			{
				"channel": 165,
				"ht_modes": [
					"HT20"
				]
			},
			{
				"channel": 42,
				"ht_modes": [
					"HT20"
				]
			},
			{
				"channel": 157,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 40,
				"ht_modes": [
					"HT40-", "HT20"
				]
			},
			{
				"channel": 38,
				"ht_modes": [
					"HT20"
				]
			},
			{
				"channel": 44,
				"ht_modes": [
					"HT40+", "HT20"
				]
			},
			{
				"channel": 36,
				"ht_modes": [
					"HT40+", "HT20"
				]
			}
		]
	}
}
````

<a name="get-settings"></a>
### get network settings
`GET /network/<network-id>/settings`

This API is documented in [Network Settings endpoints](network_settings.md#get-settings).

<a name="set-settings"></a>
### set network settings
`PUT /network/<network-id>/settings`

This API is documented in [Network Settings endpoints](network_settings.md#set-settings).

<a name="upload-splash"></a>
### upload splash pages
`POST /network/<network-id>/file`

Used by CloudTrax to move internally-hosted splash pages (HTML and related files, including image assets) up to the Amazon S3 instance.

##### example request
````
POST https://api.cloudtrax.com/network/12345/file
````

##### input
Details of the file to be moved, base64encoded.

##### example input
```` json
  "ssid_id":1,
  "name":"logo1.gif",
  "role":"image",
  "file":"R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
````

##### output
The API either returns `200 Success` or an HTTP error and JSON describing the error on failure.
On success, the api returns the file-id and the S3 location if the transfer was successful.

##### example output
```` json
{
    "file_id": 168,
    "url": "https://s3.amazonaws.com/my-cloudtrax-files/ct4/133314/splashpage/1/image/logo1.gif"
}
````

<a name="delete-splash"></a>
### delete splash pages
`DELETE /network/<network-id>/file/<file-id>`

Delete internal splash pages from the Amazon S3 instance.

##### example request

````
DELETE https://api.cloudtrax.com/network/12345/file/102
````


The API either returns `200 Success` or an HTTP error and JSON describing the error on failure.
On success, the api returns the file-id and the S3 location if the transfer was successful.











