# Network Settings endpoints

* [Overview](#overview)
* [JSON structures and naming conventions](#json)
	* [The "network" object](#network-object)
	* ["network" sub-objects](#network-sub-objects)
	* [The "ssids" object](#ssids-object)
	* ["ssids" sub-objects](#ssids-sub-objects)
	* [Notes on "required"](#required)
* [Network settings in detail](#network-global-settings)
	* [general](#network-general)
	* [maintenance](#network-maintenance)
	* [radio](#network-radio)
	* [firmware upgrade](#network-firmware-upgrade)
	* [advanced](#network-advanced)
	* [paypal](#network-paypal)
* [SSID settings in detail](#network-per-ssids)
	* [general](#ssid-general)
		* [Wi-Fi authentication types table](#wifi-auth)
	* [captive portal](#ssid-captive-portal)
		* [Splash page modes table](#splash-options)
		* [captive portal/remote splash pages](#ssid-captive-portal-splashpages)
		* [captive portal/radius authentication](#ssid-captive-portal-radius-auth)
		* [captive portal/http authentication](#ssid-captive-portal-http-auth)
	* [advanced](#ssid-advanced)
* [API endpoints](#api-functions)
	* [get network settings](#get-settings)
	* [set network settings](#set-settings)

<a name="overview"></a>
## Overview
The Cloudtrax API  document on network-related endpoints, [(network_management.md)](./network_management.md), provides information on a number of network-related operations, such as creating, listing, and deleting networks.  The two endpoints for getting and setting network settings,

`GET /network/<network-id>/settings`

and 

`PUT /network/<network-id>/settings`

are nominally part of the collection of endpoints described in that document, as they share the same path component,  `/network/<network-id>/`. A full description of those two endpoints is somewhat complex, however, and for reasons of readability and ease of access has been broken out separately in the current document.

<a name="json"></a>
## JSON structures and naming conventions

The Cloudtrax API exposes a number of network-related settings. Key to understanding how these settings are accessed is an understanding of the JSON structures that describe them. 

Network settings are divided into two top-level objects: "network", which is a dictionary of settings that are global to the network as a whole, and "ssids", which is an array-like structure (more below) of *per-SSID* objects, each of which contains settings that are local to a particular SSID. Both the "network" object and the "ssids" object contain a number of sub-objects that compartmentalize the settings within them into different groupings.

<a name="network-object"></a>
#### The "network" object

The "network" object contains, among others, a "general" object, which is a dictionary of settings such as `name` and `location`,  where these are keys that are used to retrieve and update the actual values for their settings. Here's a snippet of JSON showing what a small piece of this object hierarchy looks like:

```` json
{
	"network" : {
		"general" : {	
			"name" : "Test Network #1",
			"location" : "Westmount, Canada"
		}
	}
}
````

This document uses a structured-property-like *dot notation* to refer to these setting groups. For example we can refer to the "general" settings sub-object within the "network" object as `network.general`, and the `location` setting within that object as `network.general.location`.

<a name="network-sub-objects"></a>
#### "network" sub-objects
Settings within the "network" object are categorized into the following six JSON sub-objects.

sub-object | description
----- | -----
[`network.general`](#network-general) | general settings applicable to the network as a whole.
[`network.maintenance`](#network-maintenance)	 | network settings for the daily maintenance window.
[`network.radio`](#network-radio) | network settings related to radio channels and modes.
[`network.firmware_upgrade`](#network-firmware-upgrade) | network settings related to firmware upgrades.
[`network.advanced`](#network-advanced) | network settings related to such features as vouchers, DNS, bridging, and the like.
[`network.paypal`](#network-paypal) | network settings related to PayPal, such as maximum user count, and up and down limits.

<a name="ssids-object"></a>
#### The "ssids" object

The other top-level JSON object is "ssids", as already mentioned. We referred to this object as an *array-like* structure because, while it provides access to the settings of each SSID object as if it were an element in a standard JSON array, it doesn't use an array structure to represent them. Instead it uses a dictionary in which the numbers "1" to "4" are used as keys to the sub-objects representing each SSID.

For example, a sub-section of the JSON hierarchy containing a dictionary representing the captive-portal settings for SSID #2 looks like this:

```` json
{
	"ssids" : {
		"2" : {
			"captive_portal" : {
			
			}
		}
	}
}
````

The dot-notation we'll use to represent this particular hierarchy is `ssids["2"].captive_portal`, and in the case of SSID's generally,  `ssids[].captive_portal`.

<a name="ssids-sub-objects"></a>
#### "ssids" sub-objects

Settings for each SSID object are categorized into the following sub-objects.

sub-object | description
----- | -----
<a href="#ssid-general">`ssids[].general`<a/> | general settings applicable to an SSID as a whole.
<a href="#ssid-captive-portal">`ssids[].captive_portal`</a> | settings applicable to the captive-portal capabilities of the SSID in general.
<a href="#ssid-captive-portal-splashpages">`ssids[].captive_portal.splashpages`</a> | settings related to captive portal remote splash pages.
<a href="#ssid-captive-portal-radius-auth">`ssids[].captive_portal.radius_auth`</a> | settings related to authentication for captive portal RADIUS servers.
<a href="#ssid-captive-portal-http-auth">`ssids[].captive_portal.http_auth`</a> | settings related to captive portal HTTP authentication.
<a href="#ssid-advanced"> `ssids[].advanced` </a> | a potpourri of more advanced per-SSID settings.

Details of the settings for all global "network" sub-objects and per-SSID sub-objects are outlined in the tables below. 

Entries in the tables should be self-explanatory, with the possible exception of the "required" column. Entries in that column are interpreted as follows.

<a name="required"><a/>
#### Notes on "required"

entry | meaning
---- | ----
`required` | Value must be set. Note that defaults are never supplied for required values.
`optional` | The setting need not be supplied; a default may or may not be supplied by the system.
`conditional` | Value of this setting depends on the value of another setting. If the other has been set to a specified value, this one becomes compulsory, otherwise it remains optional. <br/>:small_orange_diamond:Example: a number of the settings in <a href="#ssid-captive-portal-splashpages">`ssids[].captive_portal.remote_splashpage`</a>  are `conditional`because they depend on the value of the `splashpage` setting in <a href="#ssid-captive-portal">`ssids[].captive_portal`</a>. If the latter is set to `remote`, they are `required` otherwise `optional`

<a name="network-global-settings"></a>
## Network settings in detail

 <a name="network-general"></a>
### network.general
```` json
{
	"network" : {
		"general" : {	
		
		}
	}
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`name` | string | Human-readable network name. <br/>:small_orange_diamond:Example value: `"my-network"` <br/>:small_orange_diamond:Allowed chars: `0-9, a-z, A-Z, hyphen, underscore, whitespace` | none | required
`display_name` | string | Name/description used on reports and the splash page. Network name will be used if this is left blank. <br/>:small_orange_diamond:Example value: `"Acme Services Network"` <br/>:small_orange_diamond:Allowed chars: `0-9, a-z, A-Z, hyphen, underscore, whitespace` | none | optional 
`location` | string | Google maps-compatible description of your network location (used to center location on the map).<br/>:small_orange_diamond:Example value: `"Portland, Oregon"` <br/>:small_orange_diamond:Allowed chars: `0-9, a-z, A-Z, hyphen, underscore, comma, dot, whitespace` | none | optional
`timezone` | string | Network timezone, used to schedule automatic scans, upgrades, etc. See [List of timezones](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones) (among other sources).<br/>:small_orange_diamond:Example value: `"America/Los_Angeles"` <br/>:small_orange_diamond:Allowed chars: `See above link`  | none | required
`disable_public_access` | bool | Lock out password-free/anonymous access. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional 
`enable_12h_display` | bool | Display am/pm instead of 24h format.<br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`disable_internet_check` | bool | Disables the regular `is_internet_available` check performed by each network AP. Will keep the wi-fi network up in event of an internet connection loss. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`notification_email_address` | string | List of email addresses receiving alerts/notifications if enabled.  Addresses separated by whitespace<br/>:small_orange_diamond:Example value: `"addr@example.com"` <br/>:small_orange_diamond:Allowed chars: `valid email address(es) and whitespace` | none | optional
`notes` | string | Freeform multiline comments.<br/>:small_orange_diamond:Example value: `"Oh, what a lovely day!"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | optional

 <a name="network-maintenance"></a>
### network.maintenance
```` json
{
	"network" : {
		"maintenance" : {
		
		}
	}
}
````

The maintenance window species a period of time during which network maintenance may be scheduled, typically during off-peak hours. The default is daily, 02:00 AM through 04:00 AM local time.
<br/>

setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`start` | string | The time of day (local time) when maintenance should start. <br/>:small_orange_diamond:Example value: "02:00:00"<br/>:small_orange_diamond:Allowed: "00:00:00" thru "23:59:59" | `"02:00:00"`  | optional
`duration` | string | The duration of the maintenance window<br/>:small_orange_diamond:Example value: "02:00:00"<br/>:small_orange_diamond:Allowed: "01:00:00" thru "24:00:00"  | `"02:00:00"` | optional
`days` | array of strings | Days of the week in which maintenance may be performed during the above maintenance window, specified by a JSON array of two-character day names, eg: `["mo", "tu", "su"]`. All networks require at least several hours of maintenance a week. | daily | optional 

  <a name="network-radio"></a>
### network.radio
```` json
{
	"network" : {
		"radio" : {
		
		}
	}
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`country_code` | string | The regulatory domain which sets available channels and maximum power. 2-character code as per [ISO-3166](http://www.iso.org/iso/home/standards/country_codes.htm) <br/>:small_orange_diamond:Example value: `"US"` <br/>:small_orange_diamond:Allowed chars: `as per ISO-3166` | none | required
`tx_power` | int | Max TX power (in dbm) for AP's on this network. <br/>:small_orange_diamond:Example value: `20` <br/>:small_orange_diamond:Allowed chars: `0-9` | `26` | optional
`enable_autorf` | bool | Enables a network-wide channel/HT-mode optimization service which considers interference from neighboring non-OpenMesh AP's, as well as repeater gateway relationships. Once enabled, the auto-rf service overwrites per-AP channel and HT-mode settings on a daily basis. <br>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`autorf_scan_hour` | int | The daily channel/HT-mode optimization requires a full network neighbor scan (assumes auto-rf has been enabled). While scanning, the wireless performance may suffer, therefore the scan time is configurable. The settings takes into consideration the configured timezone. <br/> :small_orange_diamond:Example value: `5` <br/> :small_orange_diamond:Allowed chars: `0-9` | `0` | conditional
`channel_2_4_ghz` | int | Network-wide 2.5GHz channel configuration. Superceded by the per-AP 2.4GHz channel setting if configured. <br/>:small_orange_diamond:Example value: `7` <br/>:small_orange_diamond:Allowed chars: `0-9` | `5` | required
`channel_5_ghz` | int | Network-wide 5GHz channel configuration. Superceded by the per-AP 5GHz channel setting if configured.<br/>:small_orange_diamond:Example value: `48` <br/>:small_orange_diamond:Allowed chars: `0-9` | `44` | required
`ht_mode_2_4ghz` | string | Network-wide HT-mode setting for the 2.4GHz band. Superceded by the per-AP 2.4GHz HT-mode setting if configured. <br/>:small_orange_diamond:Example value: `"HT20"` <br/>:small_orange_diamond:Allowed entries: `HT20/HT40+/HT40-/HT80+/HT80-/HT160+/HT160-`| `"HT20"` | required
`ht_mode_5ghz` | string | Network-wide HT-mode setting for the 5GHz band. Superceded by the per-AP 5GHz HT-mode setting if configured. <br/>:small_orange_diamond:Example value: `"HT40-"` <br/>:small_orange_diamond:Allowed entries: `HT20/HT40+/HT40-/HT80+/HT80-/HT160+/HT160-` | `"HT40+"` | required

<a name="network-firmware-upgrade"></a>
### network.firmware_upgrade
```` json
{
	"network" : {
		"firmware_upgrade" : {
		
		}
	}
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`disable` | bool | Disables automatic upgrades and freezes the firmware at the current version.  <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`firmware_release` | string | Switch between stable firmware releases ("stable": well-tested & debugged) and ones under test ("testing": less well-tested but with newer features).  <br/>:small_orange_diamond:Example value: `"testing"` <br/>:small_orange_diamond:Allowed entries: `stable/testing` | `"stable"` | optional


<a name="network-advanced"></a>
### network.advanced
 ```` json
{
	"network" : {
		"advanced" : {
		
		}
	}
}
````

setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`enable_share_voucher` | bool | Allows this network to access vouchers on other networks associated with your master login. If false, only vouchers created on this network will be available.  <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`node_root_password` | string | Root password for all AP's on your network accessible via SSH.  <br/>:small_orange_diamond:Example value: `"mysecretpassword"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | required
`enable_mesh_encryption` | bool | WPA2 encrypts all mesh traffic. nodes not belonging to this network won't be able to join the mesh due to the encryption.  <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`disable_accounting` | bool | Disable per client accounting for shaping and per client traffic reports.  <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`disable_matching` | bool | Disable matching of layer7 traffic. If this is disabled and accounting is enabled, the traffic will be reported as unclassified.  <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`dns_server_addresses` | array of strings | alternate DNS server IP address(es) for network, eg OpenDNS or local DNS servers.  <br/>:small_orange_diamond:Example value: `"8.8.8.8"` <br/>:small_orange_diamond:Allowed entries: `valid IP address(es)` | none | optional
`dns_domain_name` | string | Local domain to use for resources w/out fully qualified domain names on the LAN. Requires a configured alternate DNS server in your LAN.  <br/>:small_orange_diamond:Example value: `"example.com"` <br/>:small_orange_diamond:Allowed chars: `a-z, A-Z, 0-9, hyphen, underscore, dot` | none | optional
`cloud_ap_host` | string | Alternate host implementing the full cloud-AP API for check-ins, voucher authentication, etc.  <br/>:small_orange_diamond:Example value: `my_cloud_ap_host` <br/>:small_orange_diamond:Allowed entries: `valid hostname or IP address` | none | optional
`custom_script_url` | string | The URL of the "custom.sh" script. This is a developer option used for installing additional features. See the Help Center article, [Custom.sh scripts overview](https://help.cloudtrax.com/hc/en-us/articles/202382700-Custom-sh-scripts-overview), for some examples.<br/>:small_orange_diamond:Example value: `"http://my-website.com/custom-script-url"` <br/>:small_orange_diamond:Allowed entries: `a valid URL` | none | optional
`wireless_bridge_ssid` | int | Setting this will bridge the SSID with this id with the LAN, giving your Wi-Fi clients full access to LAN resources and disable NAT.  <br/>:small_orange_diamond:Example value: `1` <br/>:small_orange_diamond:Allowed chars: `0-9` | `0` (no SSID) | optional
`wired_bridge_ssid` | int | Configures which SSID wired clients are bridged into. All corresponding SSID settings apply to all wired clients.  <br/>:small_orange_diamond:Example value: `1` <br/>:small_orange_diamond:Allowed chars: `0-9` | `id of the first created SSID` | optional
 
     
 <a name="network-paypal"></a>
### network.paypal
```` json
{
	"network" : {
		"paypal" : {
		
		}
	}
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`max_user_count` | int | Number of client devices per voucher. | `0` | optional 
`upload_limit` | int | Upload bandwidth per client device in Kbits/sec. | `2500` | optional 
`download_limit` | int | Download bandwidth per client device in Kbits/sec. | `5000` | optional 
`pdt_key` | string | The key for Payment Data Transfer. | none | optional 

<a name="network-per-ssids"></a>
## SSID settings in detail

<a name="ssid-general"></a>
### ssids[ ].general
```` json
{
	"ssids" : {
		"n" : {
			"general" : {
			
			}
		}
	}
}
````

The key "n"  is assigned by the CloudTrax API at SSID creation time and is used as an identifier for various settings such as `wired_bridge_mode` and when storing per-SSID settings. To create a new SSID, set this value to "0". Use an existing value when updating the per-SSID settings to indicate the specific SSID being updated. 

setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`ssid_num` | int | Number of the SSID array element.  <br/>:small_orange_diamond:Example value: `1` <br/>:small_orange_diamond:Allowed entries: `1-4` | none | required
`ssid_name` | string | Human-readable name for this network<br/>:small_orange_diamond:Example value: `"Test Network #3"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | required 
`wifi_name` | string | The wireless network name broadcast by all AP's on this network if the SSID is enabled<br/>:small_orange_diamond:Example value: `"test-net-3"` <br/>:small_orange_diamond:Allowed char: `unrestricted` | none | required 
`enable` | bool | Disable the SSID w/out deleting it. Settings can still be retrieved and altered.  <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `true` | optional 
`enable_use_node_name` | bool | Each  AP will broadcast its own name, rather than that in the `wifi_name` field.  Useful for debugging  or to prevent wi-fi clients from roaming from AP to AP. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional 
`wifi_auth_type` | string | controls the authentication method on the wireless level (pre-splashpage). See [Wi-Fi authentication types](#wifi-auth) below for full details.  <br/>:small_orange_diamond:Example value: `wpa-mixed-psk` <br/>:small_orange_diamond:allowed entries: See [authentication types](#wifi-auth) | `open` | optional 
`wpa_password` | string | The WPA passwork (key) for this SSID. assumes the wireless authentication type (`wifi_auth_type`) is either `wpa-mixed-psk` or `wpa-2only-psk`. Must be at least 8 characters in length.  <br/>:small_orange_diamond:Example value: `"my-password"` <br/>:small_orange_diamond:Allowed chars: `printable ASCII chars including whitespace` | none | conditional
`enable_hidden_network` | bool | Prevent this SSID from being detected by most network scanner. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional

<a name="wifi-auth"></a>
#### Wi-Fi authentication types table
Allowable entries for `wifi_auth_type` in [ssids[].general](#ssid-general)

`wifi_auth_type` | description
----- | -----
`open` | Wi-Fi network is open and unencrypted
`wpa-mixed-psk` | Simple password-based authentication allows both WPA and WPA2. `wpa_password` must be set.
`wpa-2only-psk` | Enforce WPA2 encryption. `wpa_password` must be set.
`wpa-mixed-e` | Enables WPA Enterprise with a Radius authentication server. allows both WPA and WPA2.
`wpa-2only-e` | Same as previous but enforces WPA2. The corresponding WPA Enterprise server settings for both these settings can be found in the [`ssids[].advanced`](#ssid-advanced) section.

<a name="ssid-captive-portal"></a>
### ssids[ ].captive_portal
```` json
{
	"ssids" : [
		{
			"captive_portal" : {
			
			}
		}
	]
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`splashpage` | string | Refer to [Splash page modes table](#splash-options) for details. <br/>:small_orange_diamond:Example value: `cloudtrax` <br/>:small_orange_diamond:Allowed entries: `none/cloudtrax/facebook/remote` | none | optional
`enable_traffic_shaping` | bool | Enable per-client traffic throttling on this SSID. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`disable_dhcp_fingerprinting` | bool | Disable DHCP fingerprinting for clients of this SSID.. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`per_client_download_speed` | int | Default download limit (throttling) per client in Kbits/sec, required if traffic shaping is enabled. Can be overridden by various mechanisms such as vouchers, RADIUS, etc. <br/>:small_orange_diamond:Example value: `10000` <br/>:small_orange_diamond:Allowed chars: `0-9` | `0` | conditional
`per_client_upload_speed` | int | Default upload limit (throttling) per client in Kbits/sec, required if traffic shaping is enabled. Can be overridden by various mechanisms such as vouchers, RADIUS, etc. <br/>:small_orange_diamond:Example value: `10000` <br/>:small_orange_diamond:Allowed chars: `0-9` | `0` | conditional
`client_force_timeout` | int | Default time in minutes between splashpage showings, regardless of client activity. Can be overridden by various mechanisms such as vouchers, RADIUS, etc. 1 day = 1440.<br/>:small_orange_diamond:Example value: `2880` <br/>:small_orange_diamond:Allowed chars: `0-9` | `1440` | required
`enable_block_pre_auth_clients` | bool | Block all ports until a client device has been authenticated. If unchecked, only browsing is blocked. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`enable_require_valid_login` | bool | Require a valid login on splash pages (no 'click to enter'). authentication can be provided through vouchers, RADIUS, etc. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`splashpage` | string | Enable additional authentication mechanisms on top of vouchers. Each mechanism requires supplementary settings (see below). <br/>:small_orange_diamond:Example value: `"radius"` <br/>:small_orange_diamond:Allowed entries: `none/radius/ldap/active_directory/http` | none | optional
`after_splashpage_redirect_url` | string | The page to display after the splash page. Leave blank to display the client's requested page.  This has no effect on remote splashpages.<br/>:small_orange_diamond:Example value: `http://my-website.com` <br/>:small_orange_diamond:Allowed entry: `a valid URL` | none | optional
`enable_redirect_client_track` | bool | The client MAC, AP MAC, and request URL will be appended to the redirect URL above, using the parameter names "client_mac", "node_mac", and "client_url", respectively. Useful for client logging. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`whitelist` | array of strings | MAC address(es) of device(s) that will not be shown splash pages (if the latter is enabled). Useful for game consoles that do not have a browser. <br/>:small_orange_diamond:Example value: `"00:11:22:33:44:55"` <br/>:small_orange_diamond:Allowed entries: `valid MAC addresses` | none | optional
`blocklist` | array of strings | MAC address(es) of device(s) blocked from using this network. Blocked devices will be shown a "blocked message" (below) explaining why they're being denied access. <br/>:small_orange_diamond:Example value: `"00:11:22:33:44:55"` <br/>:small_orange_diamond:Allowed entries: `valid MAC addresses` | none | optional
`block_message` | string | Message to be displayed when blocked clients try to connect. If not specified, a system-provided message will be displayed. <br/>:small_orange_diamond:Example value: `"You have been blocked!"` <br/>:small_orange_diamond:Allowed entries: `unrestricted` | none | optional
`walled_garden` | array of strings | List of sites clients can visit prior to authentication. <br/>:small_orange_diamond:Example value: `""my-website.com""` <br/>:small_orange_diamond:Allowed entries: `valid hostname(s) or IP address(es)` | none | optional

<a name="splash-options"></a>
#### Splash page modes table
Allowable entries for the setting <a href="#ssid-captive-portal">`ssids[].captive_portal.splashpage`</a>

mode | description
----- | -----
`none` | Deactivate splash pages. All clients are authenticated by default.
`cloudtrax` | Splash pages hosted on CloudTrax are downloaded to the AP's and served on first HTTP request.
`remote` | Clients' HTTP requests are redirected to a specified website for authentication. The website can signal the authentication status to the AP via a dedicated protocol.
`facebook` | Use Facebook's splash pages and authentication.

<a name="ssid-captive-portal-splashpages"></a>
### ssids[ ].captive_portal.remote_splashpage
```` json
{
	"ssids" : [
		{
			"captive_portal" : {
				"remote_splashpage" : {
				}
			}
		}
	]
}
````

setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`server_address` | string | Server name or IP address the client connecting to the network is redirected to. Required if the setting <a href="#ssid-captive-portal">`ssids[].captive_portal.splashpage`</a> in the preceding table is set to `remote`. <br/>:small_orange_diamond:Example value: `"192.168.1.1"` <br/>:small_orange_diamond:Allowed entries: `valid hostname or IP address` | none | conditional
`enable_server_ssl` | bool | Redirect the client to the UAM server via HTTPS instead of HTTP. This is recommended for security and privacy reasons. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`server_path` | string | URL portion of an initial redirect a connecting client has to go through in order to authenticate. Required if  <a href="#ssid-captive-portal">`ssids[].captive_portal.splashpage`</a> is set to `remote`. <br/>:small_orange_diamond:Example value: `"/my/path/to/splashpage.html"` <br/>:small_orange_diamond:Allowed entries: `valid URL w/out hostname` | none | conditional
`server_secret` | string | Shared secret used to secure communication between AP's and the UAM server during final stages of the authentication process. <br/>:small_orange_diamond:Example value: `"mysecret"` <br/>:small_orange_diamond:Allowed entries: `unrestricted` | none | optional
`nas_id` | string | User-defined value sent along with the remote splash page redirect (NASID variable). Can be used to identify the network the Wi-Fi client is connected to on the remote splashpage server. <br/>:small_orange_diamond:Example value: `"mynasid"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | optional

<a name="ssid-captive-portal-radius-auth"></a>
### ssids[ ].captive_portal.radius_auth
```` json
{
	"ssids" : [
		{
			"captive_portal" : {
				"radius_auth" : {
				
				}
			}
		}
	]
}
````

setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`server_addresses` | array of strings | Server name(s) or IP address(es) of RADIUS server(s) used to authenticate clients and which receive updates. Required if <a href="#ssid-captive-portal">`ssids[].captive_portal.login_method`</a> is set to `radius`. <br/>:small_orange_diamond:Example value: `"192.168.1.1"` <br/>:small_orange_diamond:Allowed entries: `valid hostname(s) or IP address(es)` | none | conditional
`server_secret` | string | Shared secret used to secure communication between AP and a RADIUS server. Required if <a href="#ssid-captive-portal">`ssids[].captive_portal.login_method`</a> is set to `radius`. <br/>:small_orange_diamond:Example value: `"myradiussecret"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | conditional
`radius_nas_id` | string | RADIUS-required identifier sent along with RADIUS packets (for example status updates). If unspecified, a combination of hostname and SSID is used. <br/>:small_orange_diamond:Example value: `"mynasid"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | optional
`enable_swap_octets` | bool | Swap upload and download bytes when reporting traffic statistics. <br/>:small_orange_diamond:Example value: `false` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional

<a name="ssid-captive-portal-http-auth"></a>
### ssids[ ].captive_portal.http_auth
```` json
{
	"ssids" : [
		{
			"captive_portal" : {
				"http_auth" : {
				
				}
			}
		}
	]
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`url` | string | HTTP base URL used for authenticating Wi-Fi clients. should begin with either "http://" or "https://" for SSL-secured connections (recommended). If SSL is enabled the root CA certificate of the entity which signed your SSL certificate has to be installed on each AP in the '/etc/ss/certs/' folder. This setting is required if <a href="#ssid-captive-portal">`ssids[].captive_portal.login_method`</a> is set to `http`. <br/>:small_orange_diamond:Example value: `"https://mydomain.com/auth/"` <br/>:small_orange_diamond:Allowed entries: `valid URL` | none | conditional
`secret` | string | Shared secret used to secure communication between AP and HTTP server. Required if <a href="#ssid-captive-portal">`ssids[].captive_portal.login_method`</a> is set to `http`. <br/>:small_orange_diamond:Example value: `"myhttpsecret"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | conditional

<a name="ssid-advanced"></a>
### ssids[ ].advanced
```` json
{
	"ssids" : [
		{
			"advanced" : {

			}
		}
	]
}
````
setting | type | description | default | required
----- | ----- | ----- | ----- | -----
`wpa_e_server_address` | string | IP address of your 802.1x (WPA-Enterprise) RADIUS server. Required if Wi-Fi authentication is set to WPA Enterprise. <br/>:small_orange_diamond:Example value: `"192.168.1.1"` <br/>:small_orange_diamond:Allowed entries: `valid IP address` | none | conditional
`wpa_e_server_port` | int | 802.1x (WPA-Enterprise) RADIUS server port, if not the standard 1812. <br/>:small_orange_diamond:Example value: `1912` <br/>:small_orange_diamond:Allowed chars: `0-9` | `1812` | optional
`wpa_e_server_secret` | string | Shared secreet of your 802.1x (WPA-Enterprise) RADIUS server. Required if Wi-Fi authentication is set to WPA Enterprise. <br/>:small_orange_diamond:Example value: `"server_secret"` <br/>:small_orange_diamond:Allowed chars: `unrestricted` | none | conditional
`enable_ap_isolation` | bool | Prevents your wireless clients from being able to access each other's computers. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`enable_lan_block` | bool | Prevents clients on the wireless networks from accessing your wired LAN. <br/>:small_orange_diamond:Example value: `true` <br/>:small_orange_diamond:Allowed entries: `true/false` | `false` | optional
`smtp_redirect_server_address` | string | Alternate SMTP server IP address for your network. All SMTP connections will be transparently forwarded to this address. <br/>:small_orange_diamond:Example value: `"192.168.1.1"` <br/>:small_orange_diamond:Allowed entries: `valid IP address` | none | optional
`wifi_whitelist` | array of strings | MAC address(es) Allowed to use this AP. All other wireless clients (MAC addresses) will not be able to connect to this AP at all. Leave blank to allow all MAC's. <br/>:small_orange_diamond:Example value: `"00:11:22:33:44:55"` <br/>:small_orange_diamond:Allowed entries: `valid MAC address(es)` | none | optional
`vlan_tag` | int | Tag all outgoing SSID traffic with the configured VLAN tag and implicitly bridge wireless clients into the LAN (disabling NAT). The LAN needs to be configured to support this tag, supply DHCP and DNS. A setting of `-1` deactivates tagging entirely. <br/>:small_orange_diamond:Example value: `10` <br/>:small_orange_diamond:Allowed chars: `0-9` | `-1` | required

<a name="api-functions"></a>
## API endpoints

 <a name="get-settings"></a>
### get network settings
`GET /network/<network-id>/settings`

##### example request
`GET https://api.cloudtrax.com/network/135587/settings`

Doing a GET on network settings returns a list of all settings and their values for the named network, both its global settings as a whole and the individual settings of each of its four SSID's. Note that this list can be rather extensive.

##### example output

See [Network settings example](./code/json/network_settings_example.json) for a full list of settings for a typical network.

 <a name="set-settings"></a>
### set network settings
`PUT /network/<network-id>/settings`

Update the network settings specified in the JSON package comprising the body of the HTTP request. Only those settings in the package will be updated; others remain untouched.  For example, in the [example input below](#example-input), only the `"location"` setting will be updated on the PUT -- all other settings in the `network.general` dictionary will be unaffected by the update. 

In general, the full JSON *pathname* of a setting (`network.general.location` in this case) has to match that of an existing setting, and its new value needs to be valid according to the requirements outlined in the tables above for the update to occur. 

For per-SSID setting updates, the key "n" needs to match that of the SSID being updated. If you're creating the SSID for the first time, use "0". See the section on [`ssids[].general`](#ssid-general) for details.

Note in particular that if the named setting does not exist -- possibly you've misspelled the name of the setting or have omitted one of its intermediate path "components" -- the update will fail silently; ie, you'll still receive an HTTP status code 200 and  JSON "code" 1009. In order to determine if the update actually worked, you'll need to do a follow-up `GET` and check that the value of the setting was updated as expected. (This behavior may change in future.)
			

##### example request
````
PUT https://api.cloudtrax.com/network/135587/settings
````

<a name="example-input"></a>
##### example input #1

Update the `location` setting in the `network.general` object:

```` json
{
	"network" : {
		"general" : {
			"location" : "Anywheresville, USA"
		}
	}
}
`````

##### example input #2
Update the `block_message` setting for SSID #2:

```` json
{
	"ssids" : {
		"2" : {
			"captive_portal" : {
				"block_message" : "Stop in the name of love!"
			}
		}
	}
}
`````
##### output

The API either returns HTTP status code 200 (success), or an HTTP error and JSON describing the error.

##### example output
````json
{
	"code": 1009,
	"message": "Success.",
	"context": "settings_put",
	"values": {
	}
}
````




