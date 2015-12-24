# Site Survey endpoints

These endpoints are used to determine which Access Points are present in the specified network and their characteristics.

functionality | method | endpoint
--- | --- | ---
[trigger scan for neighboring Access Points](#scan) | GET | `/sitesurvey/network/<net-id>/scan`
[retrieve stored scan results](#scan-results) | GET | `/sitesurvey/network/<network id>`

 <a name="scan"></a>
### trigger scan for neighboring Access Points
GET  `/sitesurvey/network/<net-id>/scan`

Scan nodes in a network to determine neighboring Access Points and their signal strengths. The results of the scan are returned to the CloudTrax server on the next AP checkin, generally within five minutes.

##### example output

````json
{
	"status" : 200
}
````

 <a name="scan-results"></a>
### retrieve stored scan results
 GET `/sitesurvey/network/<network id>`
 
Obtain the results of the previous scan that was carried out using the `scan` API above.

##### example output

````json
{
  "access_points": [
    {
      "ssid": "blubberlutsch",
      "bssid": "B0:B2:DC:DA:AE:29",
      "mode": "11b/g",
      "encryption": "wpa2",
      "vendor": "Netgear",
      "seen_by_node": [
        {
          "mac": "02:aa:bb:cc:dd:ee",
          "name": "garage",
          "time": "2014-07-29T13:52:33Z",
          "signal": -43
        },
        {
          "mac": "02:aa:bb:cc:dd:43",
          "name": "kitchen",
          "time": "2014-07-29T13:54:33Z",
          "signal": -70
        }
      ],
      "channel_width": 40,
      "channel": [
        5,
        11
      ]
    }
  ]
}
````

