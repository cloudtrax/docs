# Account Management endpoints

functionality | method | endpoint
--- | --- | ---
[create account](#create_account) | PUT |
[remove account](#remove_account) | DELETE |
[log in and authenticate](#login) | POST | `/account/login`
[create embed credentials](#embed) | GET | `/account/credential/<net-id>/embed`
[delete embed credentials](#un-embed) | DELETE | `/account/credential/<net-id>/embed`
[service agreement status](#service-agreement-status) | GET | `/account/service_agreement`
[agree to service agreement](#service-agreement-agree) | PUT | `/account/service_agreement`


<a name="create_account"></a>
### create account

*create account* is currently a restricted API and is not documented here at this time.

<a name="remove_account"></a>
### remove account

*remove account* is currently a restricted API and is not documented here at this time.

<a name="login"></a>
### log in
`POST /account/login`

Allow a user to log in using a username and password. These are passed in via , and an `account` or `network` key and secret are returned. This endpoint requires use of an `application` key (see the [CloudTrax API document](README.md#keys)).

##### example request

POST https://api.cloudtrax.com/account/login

##### example input
```` JSON
{
	"username" : "joe_blow",
	"password" : "my-beloved-dead-dog-rufus"
}
````

##### output

Returns a key and secret or an appropriate error. The "expire" field if successful is a Unix timestamp.

##### example output

```` JSON
{
  "key": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
  "secret": "0032665e503e866e331408bdb1edf02041abc53d21f6b9d70ce4495bee1de31d",
  "expire": 2145916800,
  "type": "master",
  "type_value": "BerlinCloudtrax",
  "read_only": 0,
  "last_network_name": "open mesh office lamai beach",
  "last_network_id": 92200
}
````

 <a name="embed"></a>
### create embed credentials
`GET /account/credential/<network id>/embed`

Create 'embed' user credentials for a specific network. Returns a key and secret which provide limited access to sections of the CloudTrax Dashboard for embedding on a local website. The returned key and secret can be pasted into the HTML snippet that is accessible via the Dashboard's "External embeds" section under "Configure::Display".

##### example request

GET https://api.cloudtrax.com/account/credential/12989/embed

##### example output

```` JSON
{
  "key": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
  "secret": "0032665e503e866e331408bdb1edf02041abc53d21f6b9d70ce4495bee1de31d" 
}
````

 <a name="un-embed"></a>
 
### delete embed credentials
 `DELETE /account/credential/<network id>/embed`
 
Delete a previously created embed credential.
 
##### example request

DELETE https://api.cloudtrax.com/account/credential/12989/embed

##### output
Code 14001 will be returned on a successful deletion; 14002 if the deletion failed.

##### example output

```` JSON
{
	"code": 14001,
	"message": "Deletion of key/secret succeeded.",
	"context": "account",
	"details": "",
	"values": {
	}
}
````

 <a name="service-agreement-status"></a>
### service agreement status

`GET /account/service_agreement`

Check if an `account` or `network` keyholder has agreed to Open-Mesh's latest service agreement. Log in as either an `account` or `network` user to do so.

##### example request

GET https://api.cloudtrax.com/account/service_agreement

##### output
The returned JSON package will indicate either `"valid" : true` or `"valid" : false`.

##### example output

```` JSON
{ 
	"valid" : true
}
````

 <a name="service-agreement-agree"></a>
### agree to service agreement

`PUT /account/service_agreement`

Agree to a service agreement. Note that no JSON structure is required for this endpoint.

##### example request

PUT https://api.cloudtrax.com/account/service_agreement

##### output

Code 1009 is returned on success.

##### example output
```` JSON
{
	"code": 1009,
	"message": "Success.",
	"context": "service_agreement_put",
	"details": "",
	"values": {

	}
}
````
