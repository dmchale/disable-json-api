# [Disable REST API]

** This is the public respository for the latest DEVELOPMENT copy of the plugin. There is absolutely no guarantee, 
express or implied, that the code you find here is a stable build. For official releases, please see the 
WordPress repository at https://wordpress.org/plugins/disable-json-api/ **
  
Disable the use of the JSON REST API on your website to unauthenticated users.
## Installation
1. Install to WordPress plugins as normal and activate.
## Usage
1. Basic usage of the plugin requires no configuration.
2. Optionally, you may use the Settings page to whitelist individual routes inside the REST API
## History
1. Initial versions of this plugin simply used the existing filters of the REST API to disable it entirely.
2. As of WordPress 4.7 and version 1.3 of this plugin, the plugin would forcibly throw an authentication error for 
unauthenticated users. 
3. In version 1.4 we introduced the Settings screen and allow site admins to whitelist routes 
they wish to allow for unauthenticated users.
## Credits
Authored by Dave McHale
## License
As with all WordPress projects, this plugin is released under the GPL 
