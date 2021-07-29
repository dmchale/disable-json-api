=== Disable REST API ===
Contributors: dmchale, tangrufus
Tags: admin, api, json, REST, rest-api, disable
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 5.8
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disable the use of the REST API on your website to site users. Now with User Role support!

== Description ==

The most comprehensive plugin for controlling access to the WordPress REST API!

Works as a "set it and forget it" install. Just upload and activate, and the entire REST API will be inaccessible to your general site visitors.

But if you do need to grant access to some endpoints, you can do that too. Go to the Settings page and you can quickly whitelist individual endpoints (or entire branches of endpoints) in the REST API.

You can even do this on a per-user-role basis, so your unauthenticated users have one set of rules while WooCommerce customers have another while Subscribers and Editors and Admins all have their own. NOTE: Out of the box, all defined user roles will still be granted full access to the REST API until you choose to manage those settings.

For most versions of WordPress, this plugin will return an authentication error if a user is not allowed to access an endpoint. For legacy support, WordPress 4.4, 4.5, and 4.6 use the provided `rest_enabled` filter to disable the entire REST API.

== Installation ==

1. Upload the `disable-json-api` directory to the `/wp-content/plugins/` directory via FTP
1. Alternatively, upload the `disable-json-api_v#.#.zip` file to the 'Plugins->Add New' page in your WordPress admin area
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I know if this plugin is working? =

While logged into WordPress as any user, the REST API will function as intended. Because of this, you must use a new browser - or Chrome's incognito mode - to test your website with a clean session. Go to yourdomain.com/wp-json/ (or yourdomain.com/?rest_route=/ if you have pretty permalinks disabled) while NOT LOGGED IN to test the results. You will see an authentication error returned if the plugin is active. "DRA: Only authenticated users can access the REST API."

= Does this plugin disable every REST API that is installed on my site? =

This plugin is ONLY meant to disable endpoints accessible via the core REST API that is part of WordPress itself. If a plugin or theme has implemented their own REST API (not to be confused with implementing their own endpoints within the WordPress API) this plugin will have no effect.

== Screenshots ==

1. The JSON returned by a website with the API disabled via filters (WP versions 4.4, 4.5, 4.6)
2. The JSON returned by a website with the API disabled via authentication methods (WP versions 4.7+)
3. The Settings page lets you selectively whitelist endpoints registered with the REST API, on a per-user-role basis.

== Changelog ==

= 1.7 =
* Tested up to WP v5.8
* Replace use of filemtime() with plugin version number for static file enqueues. Props @tangrufus for bringing this up!
* Fixed logic bug for role-based default_allow rules. Props @msp1974 for the report!
* Few small code-style updates

= 1.6 =
* Tested up to WP v5.6
* Added support for managing endpoint access on a per-user-role basis
* Soooooooo many small changes behind the scenes to support the above

= 1.5.1 =
* Tested up to WP v5.5

= 1.5 =
* Tested up to WP v5.3
* Added enforcement for WordPress and PHP minimum version requirements
* Fixed minor bug to prevent unintended empty routes
* Minor text updates and adding textdomain to translation functions that didn't have them

= 1.4.3 =
* Added `load_plugin_textdomain()` for i18n

= 1.4.2 =
* Fixed issue causing unintentional unlocking of endpoints when another WP_Error existed before this plugin did its job

= 1.4.1 =
* Fixed echo of text URL to primary Plugins page in WP Dashboard

= 1.4 =
* Tested for WP v4.8
* Tested for PHP 5.3+
* Added settings screen
* Site Admins may now whitelist routes that they wish to allow unauthenticated access to
* Added `dra_allow_rest_api` filter to the is_logged_in() check, so developers can get more granular with permissions
* Props to @tangrufus for all of the help that went into this release

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

= 1.6 =
* By popular request... now with User Role support!

= 1.4 =
* Adds support to optionally whitelist individual routes of the REST API via Settings page.

= 1.1 =
* Now with support for the 2.0 beta API filters

= 1.0 =
* Initial Release
