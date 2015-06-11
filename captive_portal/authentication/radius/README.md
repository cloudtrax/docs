# RADIUS-based authentication

*This is preliminary documentation. It applies only to networks running under CloudTrax 4.*

CloudTrax may be configured to authenticate users using an external RADIUS server. The screenshot below shows the Configuration panel that appears in the CloudTrax Dashboard when you select the "RADIUS" option for "Splash Page Authentication".  

If your users are going to be logging in from a CloudTrax-hosted "custom" splash page, you'll need to make sure that splash page contains a RADIUS-authentication-compatible login form. Refer to [Custom Splash Pages](../../splash_pages/custom) for details.

If your users are logging in from an external, UAM-based splash page, refer to [UAM Splash Pages](../../splash_pages/external).


*Configuration of specific RADIUS servers is outside of the scope of this document.*

<hr/>

![config screenshot](./images/radius_configuration.png "title")

##### Server Address 1 #####
##### Server Address 2 #####
The hostname or ip address of the RADIUS server. Up to two RADIUS servers may be specified.

Example: radius_server.example.com

##### Server Secret #####
The shared secret maintained independently by both the RADIUS client (the Access Point) and the RADIUS server. 


##### NAS ID #####
A NAS ID may be provided to supply additional out-of-band information to the RADIUS server during the authentication request.
