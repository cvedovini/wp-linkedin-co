<div class="linkedin"><div class="profile">
<div id="cartouche" class="section">
	<a href="<?php echo $profile->websiteUrl; ?>"><img class="picture" width="80" src="<?php echo $profile->logoUrl; ?>"/></a>
	<div class="name"><a href="<?php echo $profile->websiteUrl; ?>"><?php echo $profile->name; ?></a></div>
	<?php
	$headline = array();

	if (isset($profile->industries)) {
		foreach ($profile->industries->values as $industry) {
			$headline[] = $industry->name;
		}
	}

	if (isset($profile->employeeCountRange)) {
		$headline[] = sprintf(__('%s employees', 'wp-linkedin-co'), $profile->employeeCountRange->name);
	}

	if (!empty($headline)) {
		echo '<div class="location">' . implode(' | ', $headline) . '</div>';
	}
	?>
</div>

<?php if (isset($profile->description)): ?>
<div id="summary" class="section">
<div class="heading"><?php _e('Description', 'wp-linkedin-co'); ?></div>
<div class="summary"><?php echo wpautop($profile->description); ?></div>
</div>
<?php endif; ?>

<?php if (isset($profile->specialties)): ?>
<div id="locations" class="section">
<div class="heading"><?php _e('Specialties', 'wp-linkedin-co'); ?></div>
<?php
$specialties = array();
foreach ($profile->specialties->values as $v) {
	$specialties[] = '<span class="specialty">' . $v . '</span>';
} ?>
<p><?php echo implode(', ', $specialties); ?></p>
</div>
<?php endif; ?>

<?php if (isset($profile->locations)): ?>
<div id="locations" class="section">
<div class="heading"><?php _e('Headquarters', 'wp-linkedin-co'); ?></div>
<?php foreach ($profile->locations->values as $location): if ($location->isHeadquarters): ?>
<div class="location">
	<?php if (!empty($location->description)): ?><div class="title"><?php echo $location->description; ?></div><?php endif; ?>
	<?php if (isset($location->address)): ?><div class="address">
		<?php if (!empty($location->address->street1)): ?><div class="street"><?php echo $location->address->street1; ?></div><?php endif; ?>
		<?php if (!empty($location->address->street2)): ?><div class="street"><?php echo $location->address->street2; ?></div><?php endif; ?>
		<?php
			$line = array();
			if (!empty($location->address->city)) $line[] = '<span class="city">' . $location->address->city . '</span>';
			if (!empty($location->address->postalCode)) $line[] = '<span class="postalCode">' . $location->address->postalCode . '</span>';
			if (!empty($location->address->state)) $line[] = '<span class="state">' . $location->address->state . '</span>';
			$line = implode(', ', $line);
			if (!empty($line)) echo '<div>' . $line . '</div>';
		?>
		<?php if (!empty($location->address->countryCode)): ?><div class="country"><?php echo wp_linkedin_co_get_country_name($location->address->countryCode); ?></div><?php endif; ?>
	</div><?php endif; ?>
</div>
<?php endif; endforeach; ?>
</div>
<?php endif; ?>
</div></div>