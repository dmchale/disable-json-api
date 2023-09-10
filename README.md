# Disable REST API

[![Code Climate](https://codeclimate.com/github/dmchale/disable-json-api/badges/gpa.svg)](https://codeclimate.com/github/dmchale/disable-json-api) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/9d636a2e10534acc98531cde0625a7e7)](https://www.codacy.com/gh/dmchale/disable-json-api/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dmchale/disable-json-api&amp;utm_campaign=Badge_Grade)

** This is the public repository for the latest DEVELOPMENT copy of the plugin. There is absolutely no guarantee, 
express or implied, that the code you find here is a stable build. For official releases, please see the 
WordPress repository at https://wordpress.org/plugins/disable-json-api/ **
  
Disable the use of the REST API on your website to unauthenticated users, with the freedom to enable individual
routes as desired. Manage route access for logged-in users based on their User Role.

## Installation
 1. Install to WordPress plugins as normal and activate.
## Usage
 1. Basic usage of the plugin requires no configuration.
 2. Optionally, you may use the Settings page to whitelist individual routes inside the REST API based on User Role (Unauthenticated Users as well as any logged-in user)
## History
 1. Initial versions of this plugin simply used the existing filters of the REST API to disable it entirely.
 2. As of WordPress 4.7 and version 1.3 of this plugin, the plugin would forcibly throw an authentication error for unauthenticated users.
 3. In version 1.4 we introduced the Settings screen and allow site admins to whitelist routes  they wish to allow for unauthenticated users.
 4. In version 1.5 we added minimum requirements checks for WordPress and PHP. Fixed minor bug to prevent unintended empty routes. Minor text & text-domain updates.
 5. In version 1.6 we added support for per-role rules and did a number of other housekeeping updates in the code.
 6. In version 1.7 we changed how we cache-bust static file enqueues, and repaired a logic bug in the role-based default_allow checks.
 7. In version 1.8 we provided a new filter so devs can customize the error message sent back if you fail the authentication check; updated minimum requirements to PHP 5.6 (up from 5.3) and WordPress 4.9 (up from WP 4.4); patched a Fatal Error when activating plugin on installations running LearnDash.
## Credits
Authored by Dave McHale. Contributed to by Tang Rufus.
## License
As with all WordPress projects, this plugin is released under the GPL 
