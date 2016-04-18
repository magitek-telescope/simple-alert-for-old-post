<div class="wrap">
<h1><?php _e( 'Alert Settings', 'simple-alert-for-old-post' ); ?></h1>
<form id="alert_settings" method="post" action="">
	<?php wp_nonce_field('simple-alert-for-old-post-key', 'simple-alert-for-old-post'); ?>

	<h2><?php _e( 'Date Settings', 'simple-alert-for-old-post'); ?></h2>

	<p class="vertical-middle">
		<input type="text" name="date" id="alert_settings_date" size="3" value="<?php echo esc_attr( $params['date'] ); ?>" required>
		<select name="date_type" id="alert_settings_date_type">
			<?php foreach ($this->date_types as $name) {
				echo '<option value="' . esc_attr( $name ) . '" ' .($params['date_type'] == $name ? 'selected' : '' ) . '>';
					echo __( ucfirst( $name ) . 's', 'simple-alert-for-old-post');
				echo '</option>';
			}?>
		</select> <?php _e( 'or earlier', 'simple-alert-for-old-post' ); ?>
	</p>

	<h2><?php _e( 'Visual Settings', 'simple-alert-for-old-post'); ?></h2>

	<div class='clearfix alert-settings-three-columns'>

		<div class='alert-settings-three-columns-child'>
			<h3><?php _e( 'Theme', 'simple-alert-for-old-post' ); ?></h3>

			<p>
				<select name="theme" id="alert_settings_theme">
					<?php foreach ($this->themes as $name) {
						echo '<option value="' . esc_attr( $name ) . '" ' .($params['theme'] == $name ? 'selected' : '' ) . '>';
							echo __( ucfirst( $name ), 'simple-alert-for-old-post');
						echo '</option>';
					}?>
				</select>
			</p>
		</div>

		<div class='alert-settings-three-columns-child'>
			<h3><?php _e( 'Icon Type', 'simple-alert-for-old-post'); ?></h3>

			<p>
				<select name="icon" id="alert_settings_icon">
					<?php foreach ($this->icons as $name) {
						echo '<option value="' . esc_attr( $name ) . '" ' .($params['icon'] == $name ? 'selected' : '' ) . '>';
							echo __( ucfirst( $name ), 'simple-alert-for-old-post');
						echo '</option>';
					}?>
				</select>
			</p>
		</div>

		<div class="alert-settings-three-columns-child">
			<h3><?php _e( 'Text', 'simple-alert-for-old-post' ); ?></h3>

			<div class="alert-editor">
				<textarea cols="40" rows="3" name="message" id="alert_settings_message"><?php echo esc_html( $params['message'] ); ?></textarea><br>
				<?php _e( '"%s" will be replaced to date.' , 'simple-alert-for-old-post' ); ?>
			</div>
		</div>

	</div>

	<h2><?php _e( 'Preview', 'simple-alert-for-old-post' );?></h2>

	<div class="alert-preview">
		<div class='simple-old-alert alert-<?php echo esc_attr( $params['theme'] ); ?> alert-<?php echo esc_attr( $params['icon'] ); ?>'>
			<p class='alert-content'>
				<?php echo esc_html( $params['message'] ); ?>
			</p>
		</div>
	</div>


	<h2><?php _e( 'Display Settings', 'simple-alert-for-old-post' ); ?></h2>

	<h3><?php _e( 'Targets', 'simple-alert-for-old-post'); ?></h3>

	<ul>
		<li>
			<input type="checkbox" name="is_show_single" value="1" class="alert-settings-checkbox" <?php if(   $params['is_show_single'] ) echo 'checked="checked"'; ?> >
			<input type="checkbox" name="is_show_single" value="0" class="alert-settings-checkbox checkbox-hidden" <?php if( ! $params['is_show_single'] ) echo 'checked="checked"'; ?> >
			<label><?php _e( 'Post', 'simple-alert-for-old-post' ); ?></label>
		</li>

		<li>
			<input type="checkbox" name="is_show_page"   value="1" class="alert-settings-checkbox" <?php if(   $params['is_show_page'] ) echo 'checked="checked"'; ?> >
			<input type="checkbox" name="is_show_page"   value="0" class="alert-settings-checkbox checkbox-hidden" <?php if( ! $params['is_show_page'] ) echo 'checked="checked"'; ?> >
			<label><?php _e( 'Page', 'simple-alert-for-old-post' ); ?></label></li>
	</ul>


	<input type="hidden" name="simple_old_alert_settings_isupdate" value="1">

	<?php submit_button(); ?>
</form>
</div>
