=== Disable JSON API ===
Contributors: dmchale
Tags: admin, api, json, REST, rest-api, disable
Requires at least: 4.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Uses the built-in filters of the JSON REST API to disable its functionality.

== Description ==

The JSON REST API is currently in development via the [JSON REST API](https://wordpress.org/plugins/json-rest-api/)
plugin by Ryan McCue. Soon, this functionality will become a part of WordPress Core. While this is very exciting news
for many reasons, it is also not functionality that every site admin is going to want enabled on their website.
Similar to other plugins which already disable the XML-RPC protocol, this plugin looks to make your life simple by
allowing you to disable the JSON REST API simply by installing and activating this plugin.

If you are such an admin, you can install this plugin now to ensure that your site will not support the JSON REST API
when it is launched as a part of WordPress Core.

This plugin simply uses two filters that are built into the API which turn off support for JSON and JSONP,
respectively. Nothing is done which is not intended by the API author(s).

== Installation ==

1. Upload the `disable-json-api` directory to the `/wp-content/plugins/` directory via FTP
1. Alternatively, upload the `disable-json-api_v#.#.zip` file to the 'Plugins->Add New' page in your WordPress admin
area
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

n/a

== Screenshots ==

1. The JSON returned by a website that is protected by this plugin.

== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.0 =
* Initial Release