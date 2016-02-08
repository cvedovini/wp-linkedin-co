<?php
$link = esc_url('https://www.linkedin.com/company/' . $profile->id);
?>
<div class="linkedin"><div class="card">
<div id="cartouche">
	<a href="<?php echo $link; ?>"><img class="picture alignleft" width="50"
		height="50" src="<?php echo esc_url($profile->squareLogoUrl); ?>"/></a>
	<div class="name"><a href="<?php echo $link; ?>"><?php echo $profile->name; ?></a></div>
</div>

<?php if (isset($profile->description)): ?>
<div class="summary"><?php echo wpautop(wp_linkedin_excerpt($profile->description, $summary_length)); ?></div>
<?php endif; ?>
</div></div>
