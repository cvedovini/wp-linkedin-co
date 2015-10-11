<div class="linkedin"><div class="card">
<div id="cartouche">
	<a href="https://www.linkedin.com/company/<?php echo $profile->id; ?>"><img class="picture alignleft" width="50" height="50" src="<?php echo esc_url($profile->squareLogoUrl); ?>"/></a>
	<div class="name"><a href="https://www.linkedin.com/company/<?php echo $profile->id; ?>"><?php echo $profile->name; ?></a></div>
</div>

<?php if (isset($profile->description)): ?>
<div class="summary"><?php echo wpautop(wp_linkedin_excerpt($profile->description, $summary_length)); ?></div>
<?php endif; ?>
</div></div>
