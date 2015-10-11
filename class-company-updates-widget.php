<?php

class WPLinkedInCompanyUpdatesWidget extends WP_Widget {

	public function __construct() {
		parent::__construct('wp-linkedin-co-updates-widget', __('Company Updates', 'wp-linkedin-co'),
				array('description' => __('A widget displaying a company updates', 'wp-linkedin-co')));
	}

	public function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args((array) $instance, array(
				'title' => '',
				'id' => '',
				'count' => 10,
				'event_type' => 'status-update'
			));

		$profile = wp_linkedin_company_updates($instance);

		if (!empty($profile)) {
			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

			echo $before_widget;
			if ($title) echo $before_title . $title . $after_title;
			echo $profile;
			echo $after_widget;
		}
	}

	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
				'title' => '',
				'id' => '',
				'count' => 10,
				'event_type' => 'status-update'
			));
		$title = esc_attr($instance['title']);
		$company_admin = wp_linkedin_get_company_admin();

			if (!is_array($company_admin)) {
			echo '<p class="error">An error has occured';
			if ($company_admin) {
				echo '<br>';
				print_r($company_admin);
			}
			echo '</p>';
			return;
		}
?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Company:', 'wp-linkedin-co'); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>">
		<?php foreach ($company_admin as $company):?>
		<option value="<?php echo $company->id; ?>" <?php selected($instance['id'], $company->id); ?>><?php echo $company->name; ?></option>
		<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of updates to show:', 'wp-linkedin-co'); ?></label>
	<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $instance['count']; ?>" size="3" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('event_type'); ?>"><?php _e('Type of updates to show:', 'wp-linkedin-co'); ?></label>
	<select id="<?php echo $this->get_field_id('event_type'); ?>" name="<?php echo $this->get_field_name('event_type'); ?>">
		<option value="status-update" <?php selected($instance['event_type'], 'status-update'); ?>><?php _e('Status Updates', 'wp-linkedin-co'); ?></option>
		<option value="job-posting" <?php selected($instance['event_type'], 'job-posting'); ?>><?php _e('Job Postings', 'wp-linkedin-co'); ?></option>
	</select>
</p>
<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = (int) $new_instance['id'];
		$instance['count'] = (int) $new_instance['count'];
		$instance['event_type'] = $new_instance['event_type'];
		return $instance;
	}

}
