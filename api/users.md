# User endpoints

This set of endpoints is for managing users in the new CloudTrax user system.

functionality | method | endpoint
--- | --- | ---
[Edit a user](#put-user-id) | PUT | `/user/<id>`
[Disable a user](#put-user-id-disable) | PUT | `/user/<id>/disable`
[Enable a user](#put-user-id-enable) | PUT | `/user/<id>/enable`
[Delete a user](#delete-user-id) | DELETE | `/user/<id>`
[Get a user](#get-user-id) | GET | `/user/<id>`
[Create a user API key](#post-user-id-key) | POST | `/user/<id>/key`
[Create a user API key](#post-user-id-key) | POST | `/user/key`
[List user API keys](#get-user-id-key-list) | GET | `/user/<id>/key/list`
[Delete a user API key](#delete-user-id-key-id) | DELETE | `/user/<id>/key/<id>`
[Delete a user API key](#delete-user-id-key-id) | DELETE | `/user/key/<id>`
[Create a user](#post-user) | POST | `/user`
[List users](#get-user-list) | GET | `/user/list`
[Edit a user password](#put-user-id-password) | PUT | `/user/<id>/password`
[Create a user password reset token](#post-user-id-password_set) | POST | `/user/<id>/password_set`
[Get a user network permission](#get-user-id-network-id) | GET | `/user/<id>/network/<id>`
[Get a user account permission](#get-user-id-account) | GET | `/user/<id>/account`
[Get a user service agreement status](#get-user-id-service_agreement) | GET | `/user/<id>/service_agreement`
[Edit a user service agreement status](#put-user-id-service_agreement) | PUT | `/user/<id>/service_agreement`

<a name="put-user-id"></a>
### Edit a user
`PUT /user/<id>`

Edit details about a user.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
PUT https://api-v2.cloudtrax.com/user/123
````

```` json
{
	"name":"foo",
	"notes":"district manager. watch out for him."
}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

30002
20000
20038

<a name="put-user-id-disable"></a>
### Disable a user
`PUT /user/<id>/disable`

Disable a user. User will not be able to login or make any requests to the API after this endpoint has been successfully called. User information and permissions will be retained, but API keys will be removed.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
PUT https://api-v2.cloudtrax.com/user/123/disable
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

30002
20038
20029

<a name="put-user-id-enable"></a>
### Enable a user
`PUT /user/<id>/enable`

Enable a user. User will be able to login and make requests to the API after this endpoint has been successfully called. User information and permissions will be restored, but new API keys will have to be generated.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
PUT https://api-v2.cloudtrax.com/user/123/enable
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

30002
20038
20029

<a name="delete-user-id"></a>
### Delete a user
`DELETE /user/<id>`

Delete a user. All user information (including permissions and API keys) will be permanently removed.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
DELETE https://api-v2.cloudtrax.com/user/123
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

30002
20004
20038
20029

<a name="get-user-id"></a>
### Get a user
`GET /user/<id>`

Get a user's information. This is information that is likely only of interest to Account Admins and Group Managers and not Network Users. For example, "notes" about a user may not be something administrators want to share. Or, "permissions" are only relevant to administrators, because a Network User cannot change their own.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/123
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"email":"foo@bar.com",
	"name":"foo",
	"notes":"he is a bar",
	"role_id":2, /* account role ID */
	"account_id":123,
	"enabled":true,
	"verified":true,
	"permissions":{
		/* networks that were assigned explicitly and via network groups */
		"all_networks":[
			{"network_id":1, "role_id":3},
			{"network_id":2, "role_id":3},
			{"network_id":3, "role_id":3}
		],
		/* only specifically assigned networks */
		"assigned_networks":[
			{"network_id":3, "role_id":3}
		],
		/* only specifically assigned network groups */
		"assigned_networkgroups":[
			{"networkgroup_id":1, "role_id":3} /* contains network IDs 1 and 2 */
		],
	}
}
````

#### Error codes

30002
20004
20038

<a name="post-user-id-key"></a>
### Create a user API key
`POST /user/<id>/key`
`POST /user/key`

Create an API key for the specified user or the user making the request (if the `<id>` is not specified). API keys have identical API permissions to a logged in user, but do not require entering the user's email and password. They should still be kept secret, even though the user's password is never accessible.

Limit 10 keys generated via this endpoint per user at once.

The "key" property must be stored after creation, because it is stored securely and cannot be retrieved again.

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
POST https://api-v2.cloudtrax.com/user/123/key
POST https://api-v2.cloudtrax.com/user/key
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"user_key_id":123,
	"key":"abc",
	"secret":"def",
	"created":"1970-01-01T00:00:00Z"
}
````

#### Error codes

30002
20004
20038
20044

<a name="get-user-id-key-list"></a>
### List user API keys
`GET /user/<id>/key/list`

Get a list of API keys for the specified user that were generated via the [Create user API key](#post-user-id-key) endpoint.

The "key" is not returned, because it is stored securely. If you lose the "key", you must generated a new API key.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | 
Network User | 

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/123/key/list
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"keys":[
		{
			"user_key_id":123,
			"secret":"def",
			"created":"1970-01-01T00:00:00Z"
		}
	]
}
````

#### Error codes

30002
20004
20038

<a name="delete-user-id-key-id"></a>
### Delete a user API key
`DELETE /user/<id>/key/<id>`
`DELETE /user/key/<id>`

Delete an API key (generated via the [Create user API key](#post-user-id-key) endpoint) for the specified user or the user making the request (if the `<id>` following `/user/` is not specified).

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | 
Network User | 

##### Resource Permissions
N/A

##### Example request

````
DELETE https://api-v2.cloudtrax.com/user/123/key/456
DELETE https://api-v2.cloudtrax.com/user/key/456
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

30002
20004
20038
20043

<a name="post-user"></a>
### Create a user
`POST /user`

Create a user in the same account as the requester.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
POST https://api-v2.cloudtrax.com/user
````

```` json
{
	"email":"foo@bar.com",
	"name":"foo",
	"role_id":1, /* account role ID */
	"notes":"foo needs bar"
}
````

##### Example response

````
HTTP 200
````

```` json
{
	"user_id":2,
	"account_id":123,
	"token":"abc", /* used to verify the user */
	"email":"foo@bar.com",
	"name":"foo",
	"role_id":1 /* account role ID */
}
````

#### Error codes

30002
20000
20010
20012
20014
20015
20029
20036

<a name="get-user-list"></a>
### List users
`GET /user/list`

Get a list of users on the requester's account.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/list
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"users":[
		{
			"user_id":2,
			"email":"fizz@buzz.com",
			"name":"fizz",
			"verified":true,
			"enabled":true,
			"notes":"fizzbuzz",
			"role_id":7,
			"created":"2017-06-08T00:00:00Z",
			"highest_network_role_id":4 /* only applicable to users with assigned roles, i.e., not Account Admins */
		}
	]
}
````

#### Error codes

30002

<a name="put-user-id-password"></a>
### Edit a user password
`PUT /user/<id>/password`

Update the requester's password.

##### Account Permissions
Only the requester may access this endpoint.

##### Resource Permissions
N/A

##### Example request

````
PUT https://api-v2.cloudtrax.com/user/123/password
````

```` json
{
	"password_current":"L33T",
	"password_new":"$P34K"
}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

20003
20004
20029
20030
20038
20045

<a name="post-user-id-password_set"></a>
### Create a user password reset token
`POST /user/<id>/password_set`

Create a token for resetting a password. This is meant for administrators to be able to reset a user's password without having to see the password; rather they can just deliver the token to the user and have the user set their own password.

Limit 10 tokens per user at once.

##### Account Permissions
Role | Permitted
-|-
Account Admin | X
Group Manager | X
Network User | 

##### Resource Permissions
N/A

##### Example request

````
POST https://api-v2.cloudtrax.com/user/123/password_set
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"user_id":2,
	"token":"abc"
}
````

#### Error codes

30002
20004
20029
20033
20038
20045

<a name="get-user-id-network-id"></a>
### Get a user network permission
`GET /user/<id>/network/<id>`

Get the role ID for the given network for the requester.

##### Account Permissions
Only the requester may access this endpoint.

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/123/network/456
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"role_id":4
}
````

#### Error codes

12047
20029

<a name="get-user-id-account"></a>
### Get a user account permission
`GET /user/<id>/account`

Get the account role ID for the given network for the requester.

##### Account Permissions
Only the requester may access this endpoint.

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/123/account
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"account_id":1,
	"role_id":2
}
````

#### Error codes

30002
20004
20038
20043

<a name="get-user-id-service_agreement"></a>
### Get a user service agreement status
`GET /user/<id>/service_agreement`

Get the status of the requester's acceptance of the service agreement.

##### Account Permissions
Only the requester may access this endpoint.

##### Resource Permissions
N/A

##### Example request

````
GET https://api-v2.cloudtrax.com/user/123/service_agreement
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{
	"valid":true
}
````

#### Error codes

20029

<a name="put-user-id-service_agreement"></a>
### Edit a user service agreement status
`PUT /user/<id>/service_agreement`

Accept the service agreement for the requester.

##### Account Permissions
Only the requester may access this endpoint.

##### Resource Permissions
N/A

##### Example request

````
PUT https://api-v2.cloudtrax.com/user/123/service_agreement
````

```` json
{}
````

##### Example response

````
HTTP 200
````

```` json
{}
````

#### Error codes

20029

