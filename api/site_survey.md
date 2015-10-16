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

 <a name="scan-results"></a>
### retrieve stored scan results
 GET `/sitesurvey/network/<network id>`
 
Obtain the results of the previous scan that was carried out using the `scan` API above.

