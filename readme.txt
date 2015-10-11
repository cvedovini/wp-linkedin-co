=== WP LinkedIn for Companies ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-co
Tags: linkedin,resume,recommendations,profile,network updates,companies
Requires at least: 2.7
Tested up to: 4.3
Stable tag: 1.3.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html


== Description ==

This plugin is an extension to the WP-LinkedIn plugin providing shortcodes and
widgets to display company profiles and company updates on your blog.

The following shortcodes are available:

* `[li_company_profile]` displays a company profile. You must provide the
company id using the `id` attribute. Optional attributes `fields` and `lang` to
override the general settings.
* `[li_company_card]` displays a company card. You must provide the
company id using the `id` attribute. Optional attributes `summary_length`,
 `fields` and `lang` to override the general settings.
* `[li_company_updates]` displays a company updates. You must provide the
company id using the `id` attribute. Optional attributes `event_type` (by
default the template supports `status-update` and `job-posting`), `start`
and `count`.
* `[li_company_products]` displays a company's products and services. You must
provide the company id using the `id` attribute.

There are also two widgets to display a company profile card and a company
updates.

All templates can be customized the same way they are customized in the
WP-LinkedIn plugin.


== Installation ==

This plugin follows the [standard WordPress installation
method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin-co` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

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
