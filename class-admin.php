<?php

class WPLinkedInCoAdmin {

	function WPLinkedInCoAdmin() {
		if (function_exists('wp_linkedin_connection')) {
			$this->add_settings_field('wp-linkedin-co_fields',
					__('Company fields', 'wp-linkedin-co'), 'settings_field_fields');
		}
		add_filter('plugin_action_links_wp-linkedin-co/wp-linkedin-co.php', array(&$this, 'add_settings_link'));
		add_action('admin_notices', array(&$this, 'admin_notices'));
	}

	function add_settings_link($links) {
		$url = site_url('/wp-admin/options-general.php?page=wp-linkedin');
		$links[] = '<a href="' . $url . '">' . __('Settings') . '</a>';
		return $links;
	}

	function add_settings_field($id, $title, $callback) {
		register_setting('wp-linkedin', $id);
		add_settings_field($id, $title, array(&$this, $callback), 'wp-linkedin');
	}

	function settings_field_fields() { ?>
		<textarea id="wp-linkedin_fields" name="wp-linkedin-co_fields" rows="5"
		cols="50"><?php echo get_option('wp-linkedin-co_fields', LINKEDIN_CO_FIELDS_DEFAULT); ?></textarea>
		<p><em><?php _e('Comma separated list of fields to show on a company profile.', 'wp-linkedin-co'); ?><br/>
		<?php _e('You can overide this setting in the shortcode with the `fields` attribute.', 'wp-linkedin-co'); ?><br/>
		<?php _e('See the <a href="https://developer.linkedin.com/documents/company-lookup-api-and-fields" target="_blank">LinkedIn API documentation</a> for the complete list of fields.', 'wp-linkedin-co'); ?></em></p><?php
	}

	function admin_notices() {
		if (!function_exists('wp_linkedin_connection')) {
			echo '<div class="error" style="font-weight:bold;"><ul><li>';
			echo __('The WP LinkedIn for Companies plugin needs the WP LinkedIn plugin to be installed and activated.', 'wp-linkedin-co');
			echo '</li></ul></div>';
		}
	}

}