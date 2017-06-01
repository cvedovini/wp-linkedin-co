=== WP LinkedIn for Companies ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://paypal.me/vdvn
Tags: linkedin,resume,recommendations,profile,network updates,companies
Tested up to: 4.8
Stable tag: 1.5.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin is an extension to the WP-LinkedIn plugin providing shortcodes and
widgets to display company profiles and company updates on your blog.

The following shortcodes are available:

* `[li_company_profile]` displays a company profile. You must provide the
company page ID using the `id` attribute. Optional attributes `fields` and `lang`
to override the general settings.
* `[li_company_card]` displays a company card. You must provide the
company page ID using the `id` attribute. Optional attributes `summary_length`,
 `fields` and `lang` to override the general settings.
* `[li_company_updates]` displays a company updates. You must provide the
company page ID using the `id` attribute. Optional attributes `event_type` (by
default the template supports `status-update` and `job-posting`), `start`
and `count`.

You can find the list of company pages you have access to, with their IDs, on the
plugin's settings page. Only pages for which you are an administrator can be accessed.

There are also two widgets to display a company profile card and a company
updates.

All templates can be customized the same way they are customized in the
WP-LinkedIn plugin.

License purchase includes 1 year of product support and updates.

[Follow this link for a demo of all the shortcodes.](http://vedovini.net/company-profile)

== Installation ==

This plugin follows the [standard WordPress installation
method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin-co` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the plugin's settings page, enter your license key in the 'extensions'
	tab and press the 'Save Changes' button


== Changelog ==

= Version 1.5.4 =
- Forcing network activation on multi-site installs

= Version 1.5.3 =
- fixing cache issue

= Version 1.5.2 =
- Adding the list of accessible company pages, and IDs, on the settings' page.

= Version 1.5.1 =
- Small enhancement about the loading process
- Small enhancements in error handling

= Version 1.5 =
- Making it easier to translate countries' name
- Updating all translations with countries' name

= Version 1.4 =
- Better admin notices
- Improved license management
- Updated all translations

= Version 1.3.1 =
- Upgrading updater library

= Version 1.3 =

- Added missing `esc_url` in the templates
- Removed everything related to products

= Version 1.2 =
- Updated to adapt new restrictions on the LinkedIn API and changes in WP-LinkedIn 2.0

= Version 1.1.2 =
- Fixing updater code

= Version 1.1.1 =
- Changed the link in the header of the company card and profile to go to the
LinkedIn company page instead of the Company website

= Version 1.1 =
- added Dutch translations.
- added shortcode to display a company's products and services.

= Version 1.0 =
- Initial release.


== Credits ==

Following is the list of people and projects who helped me with this plugin,
many thanks to them :)

- [Jan Spoelstra](http://www.linkedin.com/in/janspoelstra): Contributed the
Dutch translations.
