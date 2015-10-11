=== WP LinkedIn for Companies ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-co
Tags: linkedin,resume,recommendations,profile,network updates,companies
Requires at least: 2.7
Tested up to: 3.8
Stable tag: 1.0
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

= Version 1.0 =
- Initial release.
