<div class="linkedin">
<?php foreach ($products->values as $product):?><div class="profile">
<div id="cartouche" class="section">
	<a href="<?php echo esc_url($product->websiteUrl); ?>"><img class="picture" width="80" src="<?php echo esc_url($product->logoUrl); ?>"/></a>
	<div class="name"><a href="<?php echo esc_url($product->websiteUrl); ?>"><?php echo $product->name; ?></a></div>

	<?php if (!empty($product->productCategory->name)): ?>
	<div class="location"><?php echo $product->productCategory->name; ?></div>
	<?php endif; ?>

	<?php if (isset($product->description)): ?>
	<div class="summary"><?php echo wpautop($product->description); ?></div>
	<?php endif; ?>

	<?php if (!empty($product->recommendations->values)): ?>
		<div>
		<?php foreach ($product->recommendations->values as $r):
			if (isset($r->recommender->publicProfileUrl)):
				$pictureUrl = (isset($r->recommender->pictureUrl)) ? $r->recommender->pictureUrl :'http://www.gravatar.com/avatar/?s=50&f=y&d=mm';
				$name = $r->recommender->firstName . ' ' . $r->recommender->lastName;
				if (isset($r->recommender->headline)) $name .= ' - ' . $r->recommender->headline;
				?>
				<div style="float:left;margin:0 5px 5px 0">
				<a href="<?php echo esc_url($r->recommender->publicProfileUrl); ?>"
					target="_blank"><img src="<?php echo esc_url($pictureUrl); ?>"
					alt="<?php echo $name; ?>" title="<?php echo $name; ?>"
					width="50px" border="0"></a>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php $link = esc_url("http://www.linkedin.com/company/{$id}/{$product->id}/product"); ?>
		<p style="clear:both"><a href="<?php echo $link; ?>"><?php printf(__('%d recommendations', 'wp-linkedin-co'), $product->numRecommendations); ?></a></p></div>
	<?php endif; ?>
</div><?php endforeach; ?>
</div>