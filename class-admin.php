<?php

class WPLinkedInCoAdmin {

	function __construct() {
		if (function_exists('wp_linkedin_connection')) {
			add_action('admin_menu', array(&$this, 'admin_menu'), 30);
		}

		add_filter('plugin_action_links_' . WP_LINKEDIN_CO_PLUGIN_BASENAME, array(&$this, 'add_settings_link'));
		add_action('admin_notices', array(&$this, 'admin_notices'));
		add_action('network_admin_notices', array(&$this, 'admin_notices'));
	}

	function admin_menu() {
		$this->add_settings_field('wp-linkedin-co_fields',
				__('Company fields', 'wp-linkedin-co'), 'settings_field_fields');
	}

	function add_settings_link($links) {
		$url = admin_url('options-general.php?page=wp-linkedin');
		$links['settings'] = '<a href="' . $url . '">' . __('Settings') . '</a>';
		return $links;
	}

	function add_settings_field($id, $title, $callback) {
		register_setting('wp-linkedin', $id);
		add_settings_field($id, $title, array(&$this, $callback), 'wp-linkedin');
	}

	function settings_field_fields() { ?>
		<textarea id="wp-linkedin_fields" name="wp-linkedin-co_fields" rows="5"
		cols="50"><?php echo get_option('wp-linkedin-co_fields', LINKEDIN_CO_FIELDS_DEFAULT); ?></textarea>
		<p><em><?php _e('Comma separated list of fields to show on a company profile.', 'wp-linkedin-co'); ?>
		<?php _e('You can overide this setting in the shortcode with the `fields` attribute.', 'wp-linkedin-co'); ?>
		<?php printf(__('See the <a href="%s" target="_blank">LinkedIn API documentation</a> for the complete list of fields.', 'wp-linkedin-co'),
				'https://developer.linkedin.com/docs/fields/company-profile'); ?></em></p><?php
	}

	function admin_notices() {
		if (current_user_can('install_plugins')) {
			if (!function_exists('wp_linkedin_connection')): ?>
				<div class="error"><p><?php _e('The WP LinkedIn for Companies plugin needs the WP LinkedIn plugin to be installed and activated.', 'wp-linkedin-co'); ?></p></div>
			<?php elseif (version_compare(WP_LINKEDIN_VERSION, '2.5') < 0):
				$format = __('The WP LinkedIn for Company plugin requires at least version %s of the WP-LinkedIn plugin, current installed version is %s', 'wp-linkedin-co');
				$error = sprintf($format, '2.5', WP_LINKEDIN_VERSION); ?>
				<div class="error"><p><?php echo $error; ?></p></div>
			<?php endif;
		}
	}

}