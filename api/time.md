# Time endpoints

The single endpoint in this collection is useful if you don't have a reliable time source. It helps ensure that your local time is in synch with that of the API server, since authentication uses a time window, outside of which authentication will fail.

### time

```` 
GET /time
````

##### example request
`GET https://api.cloudtrax.com/time`

##### output

The current time is returned in ISO 8601 format (UTC).

##### example output

```` json
{
	"time" : "2015-08-24T13:52:00Z"
}
````
