=== Disable REST API ===
Contributors: dmchale
Tags: admin, api, json, REST, rest-api, disable
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disable the use of the JSON REST API on your website to anonymous users.

== Description ==

** As of WordPress 4.7, the filter provided for disabling the REST API has been removed. However, this plugin will now
forcibly return an authentication error to any API requests from sources who are not logged into your website, which
will effectively still prevent unauthorized requests from using the REST API to get information from your website **

The REST API is a project in development via the [JSON REST API](https://wordpress.org/plugins/rest-api/)
plugin by Ryan McCue, Rachel Baker, Daniel Bachhuber and Joe Hoyle. The engine for the API has existed in WordPress
since v4.4, but additional functionality and endpoints are a continual project. While this is very exciting news
for many reasons, it is also not functionality that every site admin is going to want enabled on their website if not
necessary.

For WordPress versions 4.4, 4.5 and 4.6, this plugin makes use of the `rest_enabled` filter provided by the API to
disable the API functionality. For WordPress 4.7+, the plugin will return an authentication error (effectively
disabling all endpoints) for any user not logged into the website.

== Installation ==

1. Upload the `disable-json-api` directory to the `/wp-content/plugins/` directory via FTP
1. Alternatively, upload the `disable-json-api_v#.#.zip` file to the 'Plugins->Add New' page in your WordPress admin
area
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is this plugin compatible with __insert other REST API plugin here__? =

This plugin ONLY uses the filters built into the official WordPress REST API meant for disabling its functionality.
So long as your other REST API does not also use those filters to allow itself to be disabled (and it shouldn't), you
should be safe.

== Screenshots ==

1. The JSON returned by a website that is protected by this plugin. (WordPress versions 4.4, 4.5, 4.6)

== Changelog ==

= 1.3 =
* Tested for WP v4.7
* Adding new functionality to raise authentication errors in 4.7+ for non-logged-in users

= 1.2 =
* Tested for WP v4.5
* Removal of actions which publish REST info to the head and header

= 1.1 =
* Updated to support the new filters created in the 2.0 beta API

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.3 =
*

= 1.1 =
* Now with support for the 2.0 beta API filters

= 1.0 =
* Initial Release