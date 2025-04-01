# links

Really simple link-shortener written in PHP with authentication through your Nextcloud instance.

# Installation

## Link-Shortener setup

1. Clone the repository
2. Create a mySQL database and import the `db.sql` file
3. Copy the `config.php.example` to `config.php` and edit it

### Editing the config.php

4. Enter your database credentials
5. Set the `instanceUrl` to your link-shortener URL without a trailing slash (e.g. `https://link.example.com`)
6. Also enter that URL into the spot indicated under `oauth2.redirectUri`
7. Set the `cloudUrl` to your Nextcloud URL without a trailing slash (e.g. `https://cloud.example.com`)

## Nextcloud setup

Go into the nextcloud administration panel > Security > OAuth 2.0 clients

Create a client with the following settings:

-   Name: `link-shortener`
-   Redirect URL: `https://YOUR-SHORT-SERVER/links/callback.php` (Replace `YOUR-SHORT-SERVER` with your server URL - where the link-shortener is hosted)

Copy the `Client ID` and `Client Secret` into the `config.php` file under oauth2 in the link-shortener directory.

That's it! You can now use the link-shortener. To create a short link, just go to `https://YOUR-SHORT-SERVER` and log in through Nextcloud. After that, you can create short links and manage them through the link-shortener interface.

# Pretty links

Pretty links are short links that are easier to type out. Normally links consist of your url following `/?l=` and then the short link.
Pretty links are just your url followed by the short link. For example, if your link-shortener is hosted at `https://link.example.com` and you create a short link `abcde`, the pretty link would be `https://link.example.com/abcde`, whereas the normal link would be `https://link.example.com/?l=abcde`.

To enable pretty links, you will need to setup your webserver to redirect all requests to the `index.php` file. With Apache running, this will be already done for you through the `.htaccess` file. If you are using nginx, you will need to apply the configuration yourself.

After you have set up your webserver, you can enable pretty links by setting the `pretty` variable in the `config.php` file to `true`.

# Configuration details

Database settings

-   host: The host of your database
-   user: The user to access your database (should be a custom user for this application)
-   password: The password for the user
-   dbname: The name of the database

OAuth2 settings

-   clientId: The client ID you got from the Nextcloud OAuth2 client
-   clientSecret: The client secret you got from the Nextcloud OAuth2 client
-   redirectUri: The redirect URI you set in the Nextcloud OAuth2 client (Should be your link-shortener URL + /links/callback.php`)
-   cloudUrl: The URL of your Nextcloud instance (without trailing slash - e.g. `https://cloud.example.com`)

shortLinks customization

-   min: The minimum length of the short link (default: 3)
-   max: The maximum length of the short link (default: 255)
-   default: The default length of the short link (default: 5)
-   pretty: Wheter to use pretty links or not (default: false - See below)

instanceUrl: The URL of your link-shortener instance (without trailing slash - e.g. `https://link.example.com`)  
footerMsg: A message to display in the footer of the link-shortener (If empty, nothing will be displayed)
