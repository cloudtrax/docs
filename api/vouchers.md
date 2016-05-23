# Vouchers endpoints

This API component provides endpoints for creating, listing, and editing vouchers, which in turn provide a mechanism that allow individuals and groups of individual to access networks with preset restrictions on usage time and bandwidth, both paid and for free.

functionality | method | endpoint
--- | --- | ---
[list all vouchers for a given network](#list-vouchers) | GET | `/voucher/network/<network-id>/list`
[create vouchers](#create-vouches) | POST | `/voucher/network/<network-id>`
[update individual voucher settings](#update-individual) | PUT | `/voucher/network/<network-id>/update`
[update multiple vouchers' settings](#update-multiple) | PUT | `/voucher/network/<network-id>/<actions>`

### list all vouchers for a given network
`GET /voucher/network/<network-id>/list`

List all vouchers [currently active?]  [not yet completed?] for the given network.

##### example request

````
GET https://api.cloudtrax.com/voucher/network/123456/list
````

##### output

The API either returns HTTP status code 200 (success) or an HTTP error and JSON describing the error. On success, the API returns all vouchers for the given network.

##### example output
```` json
{
	"vouchers": [{
		"code": "64dd7fc",
		"type": 2,
		"created": "2016-05-23T23:05:17Z",
		"duration": 1,
		"users": "0",
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
		"users": "0",
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

### create vouchers
`POST /voucher/network/<network-id>`

### update individual voucher settings
`PUT /voucher/network/<network-id>/update`

### update multiple vouchers' settings
`PUT /client/network/<network-id>/actions`