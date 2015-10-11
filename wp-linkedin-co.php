<?php
/*
Plugin Name: WP LinkedIn for Companies
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-co
Description: This plugin enables you to display company's profiles.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-co
Version: 1.3
Text Domain: wp-linkedin-co

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/

define('WP_LINKEDIN_CO_VERSION', '1.3');

define('LINKEDIN_CO_FIELDS_BASIC', 'id,name,website-url,logo-url,industries,employee-count-range');
define('LINKEDIN_CO_FIELDS_DEFAULT', 'description,specialties,locations:(description,is-headquarters,address:(street1,street2,city,state,postal-code,country-code))');
define('LINKEDIN_CO_FIELDS', get_option('wp-linkedin-co_fields', LINKEDIN_CO_FIELDS_DEFAULT));


if (!defined('WP_LINKEDIN_PROFILE_CACHE_TIMEOUT')) {
	if (defined('WP_LINKEDIN_CACHETIMEOUT')) {
		define('WP_LINKEDIN_PROFILE_CACHE_TIMEOUT', WP_LINKEDIN_CACHETIMEOUT);
	} else {
		define('WP_LINKEDIN_PROFILE_CACHE_TIMEOUT', 43200); // 12 hours
	}
}

include 'updater.php';
include 'class-company-card-widget.php';
include 'class-company-updates-widget.php';

add_action('plugins_loaded', array('WPLinkedInCoPlugin', 'get_instance'));

class WPLinkedInCoPlugin {

	private static $instance;

	public static function get_instance() {
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function __construct() {
		add_action('init', array(&$this, 'init'));
		add_action('widgets_init', array(&$this, 'widgets_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'), 30);
		add_filter('linkedin_scope', array(&$this, 'linkedin_scope'));

		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		add_filter('load_textdomain_mofile', array(&$this, 'smarter_load_textdomain'), 10, 2);
		load_plugin_textdomain('wp-linkedin-co', false, dirname(plugin_basename(__FILE__)) . '/languages/' );
	}

	function init() {
		if (!is_admin()) {
			add_shortcode('li_company_profile', 'wp_linkedin_company_profile');
			add_shortcode('li_company_card', 'wp_linkedin_company_card');
			add_shortcode('li_company_updates', 'wp_linkedin_company_updates');
		}
	}

	function smarter_load_textdomain($mofile, $domain) {
		if ($domain == 'wp-linkedin-co' && !is_readable($mofile)) {
			extract(pathinfo($mofile));
			$pos = strrpos($filename, '_');

			if ($pos !== false) {
				# cut off the locale part, leaving the language part only
				$filename = substr($filename, 0, $pos);
				$mofile = $dirname . '/' . $filename . '.' . $extension;
			}
		}

		return $mofile;
	}

	function linkedin_scope($scope) {
		$scope[] = 'rw_company_admin';
		return $scope;
	}

	function widgets_init() {
		register_widget('WPLinkedInCompanyCardWidget');
		register_widget('WPLinkedInCompanyUpdatesWidget');
	}

	function admin_menu() {
		require_once 'class-admin.php';
		$this->admin = new WPLinkedInCoAdmin($this);
	}

}


function wp_linkedin_get_company_admin() {
	$linkedin = wp_linkedin_connection();
	$company_admin = $linkedin->api_call('https://api.linkedin.com/v1/companies',
			'', array('is-company-admin' => 'true'));

	if (!is_wp_error($company_admin)) {
		return $company_admin->values;
	} elseif (LI_DEBUG) {
		return $company_admin->get_error_message();
	} else {
		return false;
	}
}


function wp_linkedin_get_company_profile($id, $options='id', $lang=LINKEDIN_PROFILELANGUAGE) {
	$linkedin = wp_linkedin_connection();
	if ($linkedin === false) return false;

	$profile = false;
	$cache_key = sha1($id.$options.$lang);

	$cache = $linkedin->get_cache('wp-linkedin_cache');
	if (!is_array($cache)) $cache = array();

	// Do we have an up-to-date profile?
	if (isset($cache[$cache_key])) {
		$expires = $cache[$cache_key]['expires'];
		$profile = $cache[$cache_key]['profile'];
		// If yes let's return it.
		if (time() < $expires) return $profile;
	}

	// Else, let's try to fetch one.
	$url = "https://api.linkedin.com/v1/companies/$id:($options)";
	$fetched = $linkedin->api_call($url, $lang);

	if (!is_wp_error($fetched)) {
		$profile = $fetched;

		$cache[$cache_key] = array(
				'expires' => time() + WP_LINKEDIN_PROFILE_CACHE_TIMEOUT,
				'profile' => $profile);
		$linkedin->set_cache('wp-linkedin_cache', $cache);
		return $profile;
	} elseif ($profile) {
		// If we cannot fetch one, let's return the outdated one if any.
		return $profile;
	} else {
		// Else just return the error
		return $fetched;
	}
}


function wp_linkedin_get_company_updates($id, $count=10, $start=0, $event_type=false) {
	$linkedin = wp_linkedin_connection();
	if ($linkedin === false) return false;

	// Else, let's try to fetch one.
	$url = "https://api.linkedin.com/v1/companies/{$id}/updates";
	$params = array('start' => $start, 'count' => $count);
	if ($event_type) $params['event-type'] = $event_type;
	return $linkedin->api_call($url, '', $params);
}


function wp_linkedin_company_profile($atts) {
	$atts = shortcode_atts(array(
				'id' => '',
				'fields' => LINKEDIN_CO_FIELDS,
				'lang' => LINKEDIN_PROFILELANGUAGE
			), $atts, 'li_company_profile');
	extract($atts);

	$fields = preg_replace('/\s+/', '', LINKEDIN_CO_FIELDS_BASIC . ',' . $fields);
	$profile = wp_linkedin_get_company_profile($id, $fields, $lang);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile->get_error_message());
	} elseif ($profile && is_object($profile)) {
		return wp_linkedin_load_template('company-profile',
				array_merge($atts, array('profile' => $profile)), __FILE__);
	}
}


function wp_linkedin_company_card($atts) {
	$atts = shortcode_atts(array(
				'id' => '',
				'summary_length' => 200,
				'fields' => 'square-logo-url,description',
				'lang' => LINKEDIN_PROFILELANGUAGE
			), $atts, 'li_company_card');
	extract($atts);

	$fields = preg_replace('/\s+/', '', LINKEDIN_CO_FIELDS_BASIC . ',' . $fields);
	$profile = wp_linkedin_get_company_profile($id, $fields, $lang);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile->get_error_message());
	} elseif ($profile && is_object($profile)) {
		return wp_linkedin_load_template('company-card',
				array_merge($atts, array('profile' => $profile)), __FILE__);
	}
}


function wp_linkedin_company_updates($atts) {
	$atts = shortcode_atts(array(
				'id' => '',
				'count' => 10,
				'start' => 0,
				'event_type' => 'status-update'
			), $atts, 'li_company_updates');
	extract($atts);

	$updates = wp_linkedin_get_company_updates($id, $count, $start, $event_type);

	if (is_wp_error($updates)) {
		return wp_linkedin_error($updates->get_error_message());
	} elseif ($updates && is_object($updates)) {
		return wp_linkedin_load_template('company-updates',
				array_merge($atts, array('updates' => $updates)), __FILE__);
	}
}


function wp_linkedin_co_locate_template($template_names) {
	$located = '';

	foreach ((array)$template_names as $template_name) {
		if ($template_name && is_readable($template_name)) {
			$located = $template_name;
			break;
		}
	}

	return $located;
}


function wp_linkedin_co_get_country_name($code) {
	static $languages;

	if (!isset($languages)) {
		$candidates = array();
		$basedir = dirname(__FILE__) . '/languages';

		if (defined('WPLANG')) {
			$candidates[] = $basedir . '/country-' . WPLANG . '.php';
			$candidates[] = WP_LANG_DIR . '/plugins/wp-linkedin-country-' . WPLANG . '.php';

			if (strpos(WPLANG, '_')) {
				$split = explode('_', WPLANG);
				$candidates[] = $basedir . '/country-' . $split[0] . '.php';
				$candidates[] = WP_LANG_DIR . '/plugins/wp-linkedin-country-' . $split[0] . '.php';
			}
		}

		$candidates[] = $basedir . '/country.php';
		$candidates[] = WP_LANG_DIR . '/plugins/wp-linkedin-country.php';

		$template = wp_linkedin_co_locate_template($candidates);
		$languages = require($template);
	}

	return $languages[strtoupper($code)];
}
