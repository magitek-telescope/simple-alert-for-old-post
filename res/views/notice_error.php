<div class="notice notice-error">
	<ul>
		<?php foreach ($result->errors['400'] as $message) {?>
			<li><?php echo $message; ?></li>
		<?php } ?>
	</ul>
</div>
