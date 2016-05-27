# Vouchers endpoints

This API component provides endpoints for creating, listing, and editing vouchers, which provide a mechanism that allows individuals and groups of individuals to access networks with preset restrictions on usage time and bandwidth, both paid and for free.

functionality | method | endpoint
--- | --- | ---
[list all vouchers for a given network](#list-vouchers) | GET | `/voucher/network/<network-id>/list`
[list specific vouchers for a given network](#list-specific-vouchers) | GET | `/voucher/network/<network-id>`
[create vouchers](#create-vouchers) | POST | `/voucher/network/<network-id>`
[update individual voucher settings](#update-individual) | PUT | `/voucher/network/<network-id>/update`
[update multiple vouchers' settings](#update-multiple) | PUT | `/voucher/network/<network-id>/<actions>`

<a name="list-vouchers"></a>
### list all vouchers for a given network
`GET /voucher/network/<network-id>/list`

List all vouchers for the given network, whether partially completed or not. 

Deleted and expired vouchers remain in the system indefinitely, with their `status` field marked appropriately, and are only expunged completely once the period `purge_days` has expired. Vouchers that have been manually cancelled (i.e., their `cancelled` field has been set to true) similarly remain in the system until expunged.

##### example request

````
GET https://api.cloudtrax.com/voucher/network/123456/list
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error. On success, the API returns all vouchers for the given network.

##### example output
The following output from this endpoint lists two vouchers that were specified during a "Create Vouchers" session in the CloudTrax Dashboard. For an explanation of the meaning of the individual fields, refer to the table [JSON detail](#json-detail-list) below.

```` json
{
	"vouchers": [{
		"code": "64dd7fc",
		"type": 2,
		"created": "2016-05-23T23:05:17Z",
		"duration": 1,
		"users": [],
		"max_users": 2,
		"down_limit": 1,
		"up_limit": 1,
		"comment": "howard's voucher test #1",
		"cancelled": false,
		"purge_days": 90,
		"tx_ids": [],
		"remaining": 0,
		"status": "unused"
	}, {
		"code": "f673d28",
		"type": 2,
		"created": "2016-05-23T23:05:17Z",
		"duration": 1,
		"users": [],
		"max_users": 2,
		"down_limit": 1,
		"up_limit": 1,
		"comment": "howard's voucher test #1",
		"cancelled": false,
		"purge_days": 90,
		"tx_ids": [],
		"remaining": 0,
		"status": "unused"
	}]
}
````
<a name="json-detail-list"></a>
##### JSON detail

field | type | description | example value
--- | --- | --- | ----
`code` | string | The code used to activate this voucher. | `"abc123"`
`type` | int | The voucher type. | `2`
`created` | string | The timestamp corresponding to when the voucher was created. | `"2016-05-23T23:05:17Z"`
`duration` | int | The amount of usage time remaining for the voucher, in hours. | `8`
`users` | array of strings | An array of the MAC's of all devices that have used this voucher. | `"["00:AA:BB:11:22:33","00:AA:CC:22:33:44"]"`
`max_users` | int | Number of users (i.e., devices) that can share this voucher. | `2`
`down_limit` | float | The maximum download speed possible with this voucher, in Mbits/sec. | `10.0`
`up_limit` | float | The maximum upload speed possible with this voucher, in Mbits/sec. | `10.0`
`comment` | string | The comment associated with this voucher. | `"Free guest Wi-Fi"`
`cancelled` | bool | Indicates whether the voucher has been cancelled manually. | false
`purge_days` | int | The number of days a voucher will remain in the system before being automatically expunged. | `90` 
`tx_ids` | array of strings | All the Paypal tx_ids associated with this voucher. |  `"["0000012345ABC","1111154321CBA"]"`
`remaining` | int | The hours of usage left on the voucher. | `20`
`status` | string | The current condition of the voucher, one of: `"unused"`, `"deleted"`, `"active"`, or `"expired"` | `"unused"`

<a name="list-specific-vouchers"></a>
### list specific vouchers for a given network
`GET /voucher/network/<network-id>`

List the vouchers for the given network specified in the array of voucher codes in the Request body. The array is named "vouchers". The output, behavior, and description of returned fields is otherwise identical to that of the endpoint for listing all vouchers for the given network [described above](#list-vouchers).

##### example request

````
GET https://api.cloudtrax.com/voucher/network/123456
````

##### example input

```` json
{
    "vouchers" : [ "Room 1", "Room 2", "Room 3", "Room 5" ]
}
````

<a name="create-vouchers"></a>
### create vouchers
`POST /voucher/network/<network-id>`

The POST body is a JSON array of voucher objects, one for each voucher to be created. The array is named "desired_vouchers".

##### example request

````
POST https://api.cloudtrax.com/voucher/network/123456
````

##### example input

```` json
{
	"desired_vouchers": [{
		"code": "Room 231",
		"duration": 1,
		"max_users": 1,
		"up_limit": 10,
		"down_limit": 20,
		"comment": "Free access for guests",
		"purge_days": 2
	}, {
		"code": "Room 232",
		"duration": 24,
		"max_users": 4,
		"up_limit": 20,
		"down_limit": 40,
		"comment": "Courtesy of management (4 guests)",
		"purge_days": 5
	}]
}
````

<a name="json-detail-create"></a>
##### JSON detail

As stated, the name of the topmost object is `"desired_vouchers"`. The fields of the individual voucher objects are as follows:

fields | type | description | required
----- | ----- | ----- | ----- 
`code` | string | Desired voucher code, up to 16 characters if user-entered. System-generated if left blank. <br/>:small_orange_diamond:Example value: `"Hotel Ritz"` <br/>:small_orange_diamond:Allowed chars: `0-9, a-z, A-Z, and all other ASCII values up through decimal 126 (tilde) ` | optional
`duration` | int | Number of hours the voucher will be usable once it's been submitted. Between 1 and 8760 (number of hours in one year). <br/>:small_orange_diamond:Example value: `24` <br/>:small_orange_diamond:Allowed chars: `0-9` | required
`max_users` | int | Number of users (i.e., devices) that can share this voucher, between 1 and 9. <br>:small_orange_diamond:Example value: `1` <br/>:small_orange_diamond:Allowed chars: `1-9` | required
`up_limit` | float | Maximum upload speed for all devices sharing this voucher, between 0.056 and 100 Mbits/sec. <br>:small_orange_diamond:Example value: `10.0` <br/>:small_orange_diamond:Allowed chars: `0-9 and .` | required
`down_limit` | float | Maximum download speed for all devices sharing this voucher, between 0.056 and 100 Mbits/sec. <br>:small_orange_diamond:Example value: `24.0` <br/>:small_orange_diamond:Allowed chars: `0-9 and .` | required
`comment` | string | User comments associated with the voucher(s), 64 characters max. <br>:small_orange_diamond:Example value: `"24 hours free access compliments of the hotel"` <br/>:small_orange_diamond:Allowed chars: `any` | optional 
`purge_days` | int | Number of days a voucher will remain in the system until automatically expunged, between 1 and 9999. <br>:small_orange_diamond:Example value: `90` <br/>:small_orange_diamond:Allowed chars: `0-9` | required

<a name="update-individual"></a>
### update individual voucher settings
`PUT /voucher/network/<network-id>/update`

The accompanying JSON body is an array of one or more vouchers, identified by their `code` fields, along with their updated contents. The topmost array object is named "vouchers". All fields are required except for `"comment"` (see the note below). The order of fields is not significant.

##### example request

````
PUT https://api.cloudtrax.com/voucher/network/123456/update
````

##### example input

```` json
{
  "vouchers": [{
      "code": "Room 231",
      "duration": 1,
      "max_users": 1,
      "up_limit": 10,
      "down_limit": 20,
      "comment": "Free access for guests",
      "purge_days": 2
    }]
}
````

##### output

On success the API returns HTTP status code 200. No JSON body is returned. 

On failure the API return a 400 and JSON listing the vouchers that could not be updated. If any of the vouchers specified in the request's JSON body do not correspond to the network ID used in the request, the API will return a 403 and none of the vouchers will be updated.

<a name="json-detail-update"></a>
##### JSON detail

As stated, the topmost array object is named `"vouchers"`. The fields of the individual voucher objects are as follows:

field | type | description | example value
--- | --- | --- | ----
`code` | string | The voucher code, up to 16 characters if user-entered, system-generated if left blank. <br/>:small_orange_diamond:Example value: `"Hotel Ritz"` <br/>:small_orange_diamond:Allowed chars: `0-9, a-z, A-Z, and all other ASCII values up through decimal 126 (tilde)` | required
`duration` | int | Number of hours the voucher will be usable once it's been submitted. Between 1 and 8760 (number of hours in one year). <br/>:small_orange_diamond:Example value: `24` <br/>:small_orange_diamond:Allowed chars: `0-9` | required
`max_users` | int | Number of users (i.e., devices) that can share this voucher, between 1 and 9. <br>:small_orange_diamond:Example value: `1` <br/>:small_orange_diamond:Allowed chars: `1-9` | required
`up_limit` | float | Maximum upload speed for each device sharing this voucher, between 0.056 and 100 Mbits/sec. <br>:small_orange_diamond:Example value: `10.0` <br/>:small_orange_diamond:Allowed chars: `0-9 and .` | required
`down_limit` | float | Maximum download speed for each device sharing this voucher, between 0.056 and 100 Mbits/sec. <br>:small_orange_diamond:Example value: `24.0` <br/>:small_orange_diamond:Allowed chars: `0-9 and .` | required
`comment` | string | User comments associated with the voucher(s), 64 characters max. The comment field may be omitted; if it is, the original comment (if any) will be truncated to length zero. <br>:small_orange_diamond:Example value: `"24 hours free access, compliments of the hotel"` <br/>:small_orange_diamond:Allowed chars: `any` | optional 
`purge_days` | int | Number of days a voucher will remain in the system until automatically expunged, between 1 and 9999. <br>:small_orange_diamond:Example value: `90` <br/>:small_orange_diamond:Allowed chars: `0-9` | required

<a name="update-multiple"></a>
### update multiple vouchers' settings
`PUT /network/<network-id>/<action>`

The action specified in `<action>` (see [actions table below](#actions)) is applied to all vouchers listed in the array of voucher-code objects in the PUT body. The array is named "vouchers".


##### example request

````
PUT https://api.cloudtrax.com/voucher/network/123456/reset
````

##### example input

```` json
{
    "vouchers" : [ "Room 1", "Room 2", "Room 3", "Room 5" ]
}
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error 400 and JSON describing the error. On success, the API returns all vouchers for the given network. If any of the vouchers specified in the request's JSON body do not correspond to the network ID used in the request, the API will return a 403 and no action will be performed on any of the vouchers.

<a name="actions"></a>
##### actions table

The specified action is applied to each voucher listed in the voucher-code array in the PUT body.

action | description
----- | -----
`restore` | Undelete (i.e., remove the deleted status) of all vouchers in the "vouchers" array.
`renew` | Undelete all vouchers in the "vouchers" array, reset their creation dates to `now`, and either set the date of first use to `now` if there are any users, or clear that field entirely if there aren't.
`reset` | Undelete all vouchers in the "vouchers" array, clear any existing users, reset the creation dates to `now`, and clear the date of first use.
`delete` | Mark all vouchers in the "vouchers" array as deleted.