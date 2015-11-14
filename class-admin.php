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
		$this->add_settings_field('fields', __('Company fields', 'wp-linkedin-co'));
		$linkedin = wp_linkedin_connection();

		if ($linkedin->is_access_token_valid()) {
			$this->add_settings_field('company_ids', __('Company IDs', 'wp-linkedin-co'));
		}
	}

	function add_settings_link($links) {
		$url = admin_url('options-general.php?page=wp-linkedin');
		$links['settings'] = '<a href="' . $url . '">' . __('Settings') . '</a>';
		return $links;
	}

	function add_settings_field($id, $title) {
		$field_name = 'wp-linkedin-co_' . $id;
		$callback = 'field_' . $id;
		register_setting('wp-linkedin', $field_name);
		add_settings_field($field_name, $title, array(&$this, $callback), 'wp-linkedin',
				'default', array('field_name' => $field_name));
	}

	function field_fields($args) {
		extract($args); ?>
		<textarea name="<?php echo $field_name; ?>" rows="5" cols="50"><?php echo get_option($field_name, LINKEDIN_CO_FIELDS_DEFAULT); ?></textarea>
		<p><em><?php _e('Comma separated list of fields to show on a company profile.', 'wp-linkedin-co'); ?>
		<?php _e('You can overide this setting in the shortcode with the `fields` attribute.', 'wp-linkedin-co'); ?>
		<?php printf(__('See the <a href="%s" target="_blank">LinkedIn API documentation</a> for the complete list of fields.', 'wp-linkedin-co'),
				'https://developer.linkedin.com/docs/fields/company-profile'); ?></em></p><?php
	}

	function field_company_ids($args) {
		$companies = wp_linkedin_get_company_admin(); ?>
		<p><?php _e('Below is the list of company pages you have access to with their page ID.', 'wp-linkedin-co'); ?>
		<?php _e('You can use those page IDs with the [li_company_profile], [li_company_card] or [li_company_updates] shortcodes.', 'wp-linkedin-co'); ?>
		<?php _e('For example, to show a company profile use [li_company_profile id="page ID"].', 'wp-linkedin-co'); ?></p>
		<?php if (is_wp_error($companies)): ?>
			<p class="notice notice-error"><?php echo $companies->get_error_message(); ?></p>
		<?php elseif (empty($companies)): ?>
			<p class="notice notice-warning"><?php _e('You do not have access to any company page.', 'wp-linkedin-co'); ?></p>
		<?php else: ?>
			<style>
				.companies { align:center; width:auto; margin:8px 0; border-spacing:0 }
				.companies td, .companies th { padding:4px 0; border-bottom:1px solid #ddd }
			</style>
			<table class="companies">
				<?php foreach ($companies as $i => $company):?>
				<tr><th><?php echo $company->name; ?></th><td><?php echo $company->id; ?></td></tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
		<p><em><?php _e('If your company page is not listed then make sure your LinkedIn profile is an administrator of this page.', 'wp-linkedin-co'); ?></em></p>
		<?php
	}

	function admin_notices() {
		if (current_user_can('install_plugins')) {
			if (!function_exists('wp_linkedin_connection')): ?>
				<div class="notice notice-error"><p><?php _e('The WP LinkedIn for Companies plugin needs the WP LinkedIn plugin to be installed and activated.', 'wp-linkedin-co'); ?></p></div>
			<?php elseif (version_compare(WP_LINKEDIN_VERSION, '2.5') < 0):
				$format = __('The WP LinkedIn for Company plugin requires at least version %s of the WP-LinkedIn plugin, current installed version is %s', 'wp-linkedin-co');
				$error = sprintf($format, '2.5', WP_LINKEDIN_VERSION); ?>
				<div class="notice notice-error"><p><?php echo $error; ?></p></div>
			<?php endif;
		}
	}

}