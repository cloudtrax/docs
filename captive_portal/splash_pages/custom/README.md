# Custom splash pages

CloudTrax custom splash pages allow you to host your own splash pages, choosing from a number of templates provided by CloudTrax. These templates can be customized, using the  full-featured HTML web-page editor that's built into the CloudTrax Dashboard, to provide graphic and textual information about your organization and its WiFi network to your clients. 

One of the special features that's provided is the ability to add or customize an HTML form on your splash page that lets your users log into the particular type of authentication service you've selected. 

You'll choose the type of authentication service you want using the Configuration dialog in the CloudTrax Dashboard (see the [Authentication overview](./../../authentication)), but you'll also need to ensure that your splash page contains a login form that's tailored to that particular service. In short, you'll either need to select a splash-page template that contains the proper form, or use the information that follows to build one yourself.

One of the key things you'll need to understand in order to be able to do this is the use of so-called "interpolated variables" in CloudTrax splash pages.

### Interpolated variables

All CloudTrax-hosted splash pages contain interpolated-variable "placeholders" in the form  `$<variable-name>`,  where the placeholder string is replaced at runtime on the Access Point with the string contents of the variable `variable-name`.

Some interpolated-variable placeholders are used to display information to users who will be viewing your splash page. For example, all CloudTrax splash pages contain a small header element near the top of the document:

````
<h1>Welcome to $gatewayname</h1>
````

where `$gatewayname` is replaced at runtime with text describing your network that would have been entered when you originally configured it. It might read, for example, "Welcome to the Hotel California WiFi Network!" You can use this placeholder if and where you see fit.

Another use of interpolated variables, if you're incorporating an authentication-service login form (see [Login forms for authentication](#login-forms) below), is to provide error-reporting information, whose contents will depend on the type of authentication service and the error messages it returns. If you're attempting to connect to an HTTP Authentication Server and have entered an invalid password, for example, you'll see "Invalid username or password."  You'll need to place the following small snippet of HTML somewhere on your splash page:

````
<p class="error">
    $error_msg
</p>
````

Finally, there are interpolated-variable placeholders that are used internally by code on the Access Point,. These are used in the HTML forms [shown below](#login-forms) that provide login access to the authentication service you've selected. 

Most of these forms contains three interpolated variables, one to indicate the piece of code on the Access Point used to process the particular type of login, `$authaction,` and two other "hidden" variables that are used by code on the Access Point to track internal state, `$tok` and `$redir.` 

Here's the current list of interpolated variables and the contexts in which they occur.

variable name | usage | context | required/optional
---------------- | --------------- | ----------------------- | ----------------------
$gatewayname | to display network name/description on splash page | all splash pages | optional
$error_msg | to display CT-originated error messages | authentication: voucher passcodes and RADIUS and HTTP API logins | required
$tok | internal CloudTrax use | all splash pages w/ login forms | required
$redir | internal CloudTrax use | all splash pages w/ login forms | required
$authaction | internal CloudTrax use | all splash pages w/ login forms | required

<a name="login-forms"></a>
### Login forms for authentication

Some of the text in the following HTML snippets is optional or editable, for example the `<h3>` elements providing the login form titles. You can style them as you like. Forms can be placed inside `<div class="box">` elements if you want the forms as a whole to take on default CloudTrax styling.

It's important to note that it's up to you to ascertain that the login form being shown on a splash page matches the type of authentication service you've configured.

#### Free access (no authentication required)

````
<h3>Free Access:</h3>
<!-- This is the "Enter" button for "Open" networks. -->
<form method="get" action="$authaction">
    <input name="tok" value="$tok" type="hidden"> 
    <input name="redir" value="$redir" type="hidden">
    <button class="button" type="submit">Continue</button>
</form>
````

#### Voucher passcode entry

````
<h3>Passcode Access:</h3>
<p>
Enter your access code below:
</p>
<!-- Voucher Error messages (not found, expired, etc) will be displayed using this variable -->
 <!-- do not remove -->
<p class="error">
    $error_msg
</p>
<!-- This the the "Enter" button for "Voucher" networks. -->
<form method="get" action="$authaction">
    <input name="tok" value="$tok" type="hidden">
    <input name="redir" value="$redir" type="hidden">
    <input class="inputbox" name="voucher" size="6" type="text">&nbsp;
    <button class="button" type="SUBMIT">Login</button>
</form>
````

#### CloudTrax HTTP Authentication login

````
<h3>HTTP Authentication:</h3>
<p>
Enter your login credentials:
</p>
<!-- Username and Password to be submitted to the HTTP Auth Server -->
<form method="get" action="$authaction">
    <input name="tok" value="$tok" type="hidden">
    <input name="redir" value="$redir" type="hidden">
    Username: <input class="inputbox" name="username" size="6" type="text">&nbsp;
    Password: <input class="inputbox" name="password" size="6" type="text">&nbsp;
    <button class="button" type="SUBMIT">Login</button>
</form>
````

#### RADIUS server login

````
<h3>Radius Access:</h3>
<p>
Enter your RADIUS credentials:
</p>
<!-- Username and Password to be submitted to RADIUS -->
<form method="get" action="$authaction">
    <input name="tok" value="$tok" type="hidden">
    <input name="redir" value="$redir" type="hidden">
    Username: <input class="inputbox" name="username" size="6" type="text">&nbsp;
    Password: <input class="inputbox" name="password" size="6" type="text">&nbsp;
    <button class="button" type="SUBMIT">Login</button>
</form>
````



