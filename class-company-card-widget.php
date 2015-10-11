<?php

class WPLinkedInCompanyCardWidget extends WP_Widget {

	public function __construct() {
		parent::__construct('wp-linkedin-co-card-widget', __('Company Card', 'wp-linkedin-co'),
				array('description' => __('A widget displaying a company card', 'wp-linkedin-co')));
	}

	public function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args((array) $instance, array(
				'title' => '',
				'id' => '',
				'summary_length' => 200
			));

		$profile = wp_linkedin_company_card($instance);

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
				'summary_length' => 200
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
	<label for="<?php echo $this->get_field_id('summary_length'); ?>"><?php _e('Max length of summary (in char):', 'wp-linkedin-co'); ?></label>
	<input id="<?php echo $this->get_field_id('summary_length'); ?>" name="<?php echo $this->get_field_name('summary_length'); ?>" type="text" value="<?php echo $instance['summary_length']; ?>" size="3" />
	<br/><small><em><?php _e('Specify \'0\' to hide the summary.', 'wp-linkedin-co'); ?></em></small>
</p>
<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = (int) $new_instance['id'];
		$instance['summary_length'] = $new_instance['summary_length'];
		return $instance;
	}

}
