# CloudTrax API

*This is preliminary documentation. It applies only to networks running under CloudTrax 4.*<br/>

*Note that there are no access charges associated with use of the CloudTrax API for users with fewer than 100 devices. For pricing on more than 100 devices, contact sales@openmesh.com. *<br/>

### Contents of this document

* [Overview](#overview)
* [Introduction](#intro)
* [Required headers](#headers)
* [Errors](#errors)
	* [Error elements](#error-elements)
	* [1009 "Success" elements](#1009-success)
* [Authentication](#authentication)
	* [Keys](#keys)
	* [Retrieving API Keys](#getting_keys)
	* [Generating the Authorization header](#authorization-header)
	* [Generating the Signature header](#signature-header)
* [An API Server test harness](#code)
* [Endpoint Details](#details)

<a name="overview"></a>
### Overview

This document describes the CloudTrax API, an Application Programming Interface that allows you to create, access, configure, and manipulate your CloudTrax networks and their  Access Points. The API provides the facilities to let you recreate, if desired, the functionality of the CloudTrax Dashboard and have it entirely under your own control.

The API is comprised of the following endpoint collections:

endpoints | description
---- | -----
[Account Management](account_management.md) | Create new user accounts, log in users with username and password, provide embedded web access to CloudTrax visuals, and manage service agreements
[Client Management](client_management.md) | Block and edit clients 
[History](history.md) | View traffic statistics in a given domain over a time span
[Network Management](network_management.md) | Create, list, and delete networks
[Node Management](node_management.md) | Create, list, update, delete, and test for characteristics of Access Points (also called nodes)
[Site Survey](site_survey.md) | Scan Access Points in a network for neighboring Access Points
[Time](time.md) | Synchronize time against the API server
[Vouchers](vouchers.md) | Create, list, and update vouchers allowing per-user access to your networks

In order to use the facilities of the API, you'll need to have the API keys relevant to your account and/or network(s). See [Retrieving API Keys](#getting_keys) for more information about this.

 <a name="intro"></a>
### Introduction

The CloudTrax API is [RESTful](https://en.wikipedia.org/wiki/Representational_state_transfer). It uses the HTTP methods (or *verbs*), GET, POST, PUT, and DELETE, to retrieve, create, update, and delete,  respectively, CloudTrax-based *resources*. The resources themselves, as well as the operations performed on them, are specified in RESTful fashion by the *path* component of the URL that addresses the CloudTrax API server.

Because the API is RESTful, it's the combination of HTTP method plus path that fully specifies, or describes, an API *endpoint* or call. For example, the endpoint that's described in this documentation as:

`PUT /node/<node-id>`

will actually be invoked as:

`https://api.cloudtrax.com/node/<node-id>`

with your HTTP client indicating separately that this call is a `PUT`.

*Note:* the word *node* as used in the API and at places in this documentation is a nickname for *Access Point*. The two are equivalent.

The effect of this endpoint is to update the node with id `node-id` with the information contained in the JSON  structure that's passed in as the HTTP *message body*. The same path used in conjunction with the HTTP method `DELETE`:

`DELETE /node/<node-id>`

has the effect of deleting that node.

Here's another example in a bit more detail. The API call requesting a list of all CloudTrax networks belonging to a particular user is specified by the endpoint ` GET /network/list`. We could invoke this call using the curl HTTP client on the command line, requesting verbose output as follows:

````
curl -v https://api.cloudtrax.com/network/list
````

This would produce the following output, showing the GET followed by the three Request Headers produced by the curl client in this case:

````
> GET /network/list HTTP/1.1
> User-Agent: curl/7.37.1
> Host: api.cloudtrax.com
> Accept: */*
...
````

If this call were successful, the body of the HTTP Response would return a JSON structure showing information on each network found. This particular call will fail however because all CloudTrax API calls need to be *authenticated* before they can be run (see [Authentication](#authentication) below), and we haven't done that here. What curl will show in this instance is a piece of JSON reporting the particular type of authentication error that occurred:

```
{
	"errors": [
		{
			"code": 13001,
			"message": "No nonce or timestamp in header.",
			"context": "authorize",
			"values": {

			}
		}
	]
}
```

Since this particular call uses GET, you could in the spirit of experimentation also invoke it directly from the address bar of your favorite web browser to see what happens:

````
https://api.cloudtrax.com/network/list
````

This will return the same error-reporting JSON as above (though not as nicely prettified, since the HTML will blithely ignore all internal linefeeds and reduce whitespace to a minimum).

If the above call *had* been properly authenticated and was successful, the returned JSON might have looked something like this:

````
{
	"networks": [
		{
			"name": "hk_test_network",
			"id": 135587,
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

Several of the GET-based calls in this API use an HTTP *query-string* to pass additional information to the server. For example, the `GET /history/network/<net-id>` call can add an optional "time span" parameter as in the following:

````
GET https://api.cloudtrax.com/history/network/12478?period=week
````
As already mentioned, POST and PUT requests that create new resources or update existing resources, respectively, pass required information in the body of the HTTP Request as a JSON structure. The `POST /network` request to create a new network, for example, will need to add a JSON body something like the following:

````
{
    "name":"newNetworkTest_2",
    "password":"passwordForNetwork",
    "email":"someEmail@example.com",
    "location":"Moose Jaw",
    "timezone":"Canada/Central",
    "country_code":"CA"
}
````


 <a name="headers"></a>
### Required headers
The API expects all HTTP Requests to contain the following headers:

header-name | header-value | notes
------ | ------ | -----
`Host:` | api.cloudtrax.com | Most HTTP libraries/clients generate this header automatically for the given URL. All CloudTrax API calls use this hostname.
`Content-Length:` | length in bytes of the Request body  | Generated automatically by most libraries/clients. Length will be 0 for body-less requests.
`Content-Type:` | 'application/json' | PUT and POST requests will be passing JSON structures to the server.
`OpenMesh-API-Version:` | 1 | Version of the API to use. 0 assumed if this header is missing. Incorrect versioning may cause difficult-to-diagnose errors.
`Authorization:` | see [Authentication](#authentication) | Required on all requests.
`Signature:` | see [Authentication](#authentication) | Required on all requests.


<a name="errors"></a>
### Errors

For a list of the actual errors that can occur during API operations, see the [Error codes](error_codes.md) document.

Errors are returned in a consistent fashion throughout the API. A non-200 HTTP status code (e.g. 404 HTTP_NOT_FOUND or 403 HTTP_FORBIDDEN) provides a hint as to the general nature of the error, and the body of the returned JSON provides more detailed information. For instance, If you try to create a network with a name that already exists and use an invalid country code as well, you'll get an HTTP_FORBIDDEN error with the following body:

````
{
    "errors": [
        {
            "code": 12001,
            "context": "name",
            "message": "String length (104) out of range (1 - 100).",
            "values": {
                "length": "104",
                "max": "100",
                "min": "1"
            }
        },
        {
            "code": 12005,
            "context": "country_code",
            "message": "Unknown country code.",
            "values": {}
        }
    ]
}
````

<a name="error-elements></a>
#### Error elements
`"errors"` is an array of one or more specific error elements. Each element has the following fields:

field | description
---- | ----
code | a unique error code
context | further information about where the error occurred
message | an English-language error string suitable for display
values | a list of key-value pairs that were the specific values used to create the error message. Not all error messages have values.

<a name="1009-success"></a>
#### 1009 "Success" elements
Code 1009 is a special *non-error* status-type code that is used to indicate "Success" on the completion of certain operations that do not themselves otherwise return JSON output. A successful [update node](node_management.md#update-node) call, for example, returns a JSON 1009 "Success" element to indicate a successful update:

````json
{
    "code": 1009,
    "message": "Success.",
    "context": "update_node",
    "values": {

    }
}
````

The exceptions are calls in the  [Cloud AP's](#cloud-ap) endpoint collection. For technical reasons, none of these endpoints return JSON, and their HTTP Response status codes need to be checked directly for either 200 Success or 40x Failure.

#### Error code listing
See the [Error codes document](error_codes.md).

 <a name="authentication"></a>
### Authentication

As noted above, all CloudTrax API calls need to be authenticated. Authentication is the process of determining that the user making this call is known and acceptable to the system. This requires adding two additional headers to every HTTP Request: 'Authorization:' and 'Signature:'.  We'll first take a brief look at keys.

<a name="keys"></a>
#### Keys

Keys are an essential part of the authentication process, since they identify users and the degree of access they're allowed to the CloudTrax system. The term *key* is used here loosely: access to the API is actually controlled by a *key pair* consisting of a *key* and a *secret*, both of which are required for API access. Keys come in three types.

type of key | provides access to
---- | ----
`account` | All networks belonging to an account, with the ability to create new networks
`network` | A single network
`application` | API endpoints that allow the creation of new accounts and log-in of users

* `account` keys (also called *master-level* keys) are the most common type of key. They provide access to all networks assigned to an account and allow their account holders to create new networks on an *ad hoc* basis.

* `network` keys allow their clients to use nearly all the same endpoints as an account-level key holder, but access is restricted to a single network. A typical use-case would be a hotel chain that maintains hotels in different cities. A different `network` key would be assigned to each hotel in the chain, restricting access at each hotel to its own network. Administrators at head office would be able to use an `account` key to oversee and manage all corporate networks system-wide.

*  `application` keys provide access to several endpoints that are not available to the other key types. These endpoints provide the functionality required to create new accounts and to retrieve account and network keys given username and password. An application, say a smartphone app, would use an `application` key to create new accounts and allows users to log in using their username and password.  See the document [Account Management endpoints](account_management.md) for details.

Whichever type of key you have, the authentication process described below is identical.

<a name="getting_keys"></a>
#### Retrieving API Keys

As described above, you must have the relevant key and secret for your account and/or network(s) in order to use the API as described in this document. To apply for access keys for your account or network, please send an email request to <a href='mailto:api@cloudtrax.com'>api@cloudtrax.com</a>. Your request should state:

 * Your name and company name
 * Your telephone number and email address
 * The name of the account and/or network(s) for which you need keys
 * A short description of why you require API access and how you plan to use the API.

Note that currently there are no access charges associated with use of the CloudTrax API for users with fewer than 100 devices. For more than 100 devices, contact sales@openmesh.com for pricing. Open Mesh may enforce rate limiting for some uses of the API. In order to help constrain resource demands, we ask that you please attempt to design any systems utilizing the API to limit frequency of access to the API.


<a name="authorization-header"></a>
#### Generating the Authorization header
Generating the "Authorization:" header is straightforward. It is formed by the string concatenation of three key-value pairs:

* an appropriate key, depending on which API is being called
* a Unix timestamp
* a so-called "Number-Used-Once" nonce

An easy-to-fix error here, 13002, is occasioned by providing a timestamp that differs from the server's time by more than 15 minutes. Call the `GET /time` endpoint to retrieve the "correct" time (from the server's perspective), and adjust your own accordingly. Nonces, a cryptographic device used to ensure that older communications cannot be reused in replay attacks, also need to be unique across all API calls during a time window that varies between 15 to 30 minutes (13003). (This error is easily avoided by generating a random nonce on every call.)

As noted above, all CloudTrax API calls are required to be signed by either an application-level or an account- or network-level key. Which is used depends on the particular call being made.

Here's a piece of PHP showing the authentication operation:

````php
$key = '1b88730ac5ba6000a1271e0b2a2edb5a163ce77bf9630850f22f8ca3de490a5f';
$nonce = 'ThisIsANonce';
$authorization = "key=" . $key . ",timestamp=" . time() . ",nonce=" . $nonce;
$authorization_header = "Authorization: " . $authorization;
````

Note that there are no spaces in the concatenated authorization string (though there is one following the colon (":") in the Authorization header itself, as allowed by the HTTP protocol).

<a name="signature-header"></a>
#### Generating the Signature header
Generating the "Signature:" header is a teeny bit more complicated perhaps, but not by much. You need to create an HMAC SHA-256 hashed form of the authorization string concatenated with the endpoint's path, keyed on the secret string shared between you and the CloudTrax server. Certainly the code is straightforward; here it is in PHP:

````php
$signature = hash_hmac('sha256', $authorization . $path, $secret);
$sig_header = "Signature: " . $signature;
````

One final wrinkle is that the above HMAC keyed hash example only works as is with GET and DELETE. If you're working with either POST or PUT endpoints that pass a JSON structure in the message body, you'll need to concatenate that body onto the first, "message" argument of the `hash_hmac` function:

````php
$signature = hash_hmac('sha256', $authorization . $path . $jsonbody, $secret);
````


If you're getting back a 13000 "Signature wrong" error on your calls, check if you're attempting to create a signature on a PUT or POST without concatenating the HTTP message body as well.

Whatever form the signature takes, the authorization and signature headers need to be present for every API request you make.

<a name="code"></a>
### An API Server test harness

An accompanying document, [An API Server test harness](api_server_test_harness.md), provides PHP working-code examples of how to call individual API's from several of the endpoint collections:  [Network Management](network_management.md) ,  [Node Management](node_management.md), and  [Network Settings](network_settings.md). These calls cover the entire RESTful gamut of GET, POST, PUT, and DELETE and provide a representative sample of the types of endpoints in the API and how to call them.

<a name="details"></a>
### API Endpoint Details

For reference purposes, here is the full list of endpoint collections comprising the CloudTrax API. Dive into the following sections for details about specific API endpoints:

endpoints | description
---- | -----
[Account Management](account_management.md) | Create new user accounts, log in users with username and password, provide embedded web access to CloudTrax visuals, and manage service agreements
[Network Management](network_management.md) | Create, list, and delete networks
[Node Management](node_management.md) | Create, list, update, delete, and test for characteristics of nodes
 [History](history.md) | View traffic statistics in a given domain over a time span
[Site Survey](site_survey.md) | Scan nodes in a network for neighboring Access Points
 [Time](time.md) | Synchronize time against the API server
