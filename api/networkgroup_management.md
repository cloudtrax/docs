# Network Group Management endpoints

This set of endpoints is for managing network groups in the new CloudTrax user system.

functionality | method | endpoint
--- | --- | ---
[Create a network group](#post-networkgroup) | POST | `/networkgroup`
[Get a network group](#get-networkgroup-id) | GET | `/networkgroup/<id>`
[List network groups](#get-networkgroup-list) | GET | `/networkgroup/list`
[Edit a network group](#put-networkgroup) | PUT | `/networkgroup/<id>`
[Delete a network group](#delete-networkgroup-id) | DELETE | `/networkgroup/<id>`
[Edit the network group for a network](#put-networkgroup-id-network-id) | PUT | `/networkgroup/<id>/network/<id>`

<a name="post-networkgroup"></a>
### Create a network group
`POST /networkgroup`

Create a network group.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

```` json
{
	"name":"New Network Group"
}
````

##### Example response

```` json
{
	"networkgroup_id":1
}
````

#### Error codes

30002
40008

<a name="get-networkgroup-id"></a>
### Get a network group
`GET /networkgroup/<id>`

Get more information about the specified network group. Includes a list of all networks within the specified network group.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
Role | Permitted
-|-
Network Editor | X
Network Viewer | 
Voucher Editor | 

##### Example request

````
GET https://api-v2.cloudtrax.com/networkgroup/123
````

##### Example response

```` json
{
	"networkgroup_id":123,
	"creator_user_id":1,
	"name":"Network Group #1",
	"account_id":4,
	"networks":[
		{
			"network_id":98,
			"name":"Some Network"
		}
	]
}
````

#### Error codes

30002
40003
40006

<a name="get-networkgroup-list"></a>
### List network groups
`GET /networkgroup/list`

Get a list of network groups accessible to the requester.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | X

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/networkgroup/list
````

##### Example response

```` json
{
	"networkgroups":[
		{
			"networkgroup_id":1,
			"name":"Network Group #1",
			"role_id":3 /* the role ID the requester has on the network group */
	]
}
````

#### Error codes
N/A

<a name="put-networkgroup"></a>
### Edit a network group
`PUT /networkgroup/<id>`

Edit a network group.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
Role | Permitted
-|-
Network Editor | X
Network Viewer | 
Voucher Editor | 

##### Example request

````
PUT https://api-v2.cloudtrax.com/networkgroup/123
````

```` json
{
	"name":"New Network Group Name"
}
````

##### Example response

```` json
{
}
````

#### Error codes

30002
40003
40006
40008

<a name="delete-networkgroup-id"></a>
### Delete a network group
`DELETE /networkgroup/<id>`

Delete a network group. All networks within the network group must first be removed.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
Role | Permitted
-|-
Network Editor | X
Network Viewer | 
Voucher Editor | 

##### Example request

````
DELETE https://api-v2.cloudtrax.com/networkgroup/123
````

##### Example response

```` json
{
}
````

#### Error codes

30002
40006
40004

<a name="put-networkgroup-id-network-id"></a>
### Edit the network group for a network
`POST /networkgroup/<id>/network/<id>`

Edit the network group for a network.

Note that the requester must have Network Editor permissions for all associated resources (network, current network group, new network group).

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | X

##### Resource Permissions
Role | Permitted
-|-
Network Editor | X
Network Viewer | 
Voucher Editor | 

##### Example request

````
PUT https://api-v2.cloudtrax.com/networkgroup/123/network/456
````

##### Example response

```` json
{
}
````

#### Error codes

12000
12047
30002
40006
