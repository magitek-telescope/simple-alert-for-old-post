<div class="wrap">
<h1><?php _e( 'Alert Settings', 'simple-alert-for-old-post' ); ?></h1>
<form id="alert_settings" method="post" action="">

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
			<h3><?php _e( 'Type', 'simple-alert-for-old-post'); ?></h3>

			<p>
				<select name="theme_type" id="alert_settings_theme_type">
					<?php foreach ($this->theme_types as $name) {
						echo '<option value="' . esc_attr( $name ) . '" ' .($params['theme_type'] == $name ? 'selected' : '' ) . '>';
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

	<h3>Preview</h3>

	<div class="alert-preview">
		<div class='simple-old-alert alert-<?php echo esc_attr( $params['theme'] ); ?> alert-<?php echo esc_attr( $params['theme_type'] ); ?>'>
			<p class='alert-content'>
				<?php echo esc_html( $params['message'] ); ?>
			</p>
		</div>
	</div>

	<input type="hidden" name="simple_old_alert_settings_isupdate" value="1">

	<?php submit_button(); ?>
</form>
</div>
