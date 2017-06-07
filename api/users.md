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
[Create a user API key](#post-user-key) | POST | `/user/key`
[List user API keys](#get-user-id-key-list) | GET | `/user/<id>/key/list`
[Delete a user API key](#delete-user-id-key-id) | DELETE | `/user/<id>/key/<id>`
[Delete a user API key](#delete-user-key-id) | DELETE | `/user/key/<id>`
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
Account Admin, Group Manager

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
20027

<a name="put-user-id-disable"></a>
### Disable a user
`PUT /user/<id>/disable`

Disable a user. User will not be able to login or make any requests to the API after this endpoint has been successfully called. User information and permissions will be retained, but API keys will be removed.

##### Account Permissions
Account Admin, Group Manager

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
20027
20029

<a name="put-user-id-enable"></a>
### Enable a user
`PUT /user/<id>/enable`

Enable a user. User will be able to login and make requests to the API after this endpoint has been successfully called. User information and permissions will be restored, but new API keys will have to be generated.

##### Account Permissions
Account Admin, Group Manager

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
20027
20029

<a name="delete-user-id"></a>
### Delete a user
`DELETE /user/<id>`

Delete a user. All user information (including permissions and API keys) will be permanently removed.

##### Account Permissions
Account Admin, Group Manager

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
20027
20029
