<?php

class Simple_Alert_For_Old_Post_Admin{

	private $basename;

	private $date_types = array(
		'day',
		'month',
		'year'
	);

	private $themes = array(
		'default',
		'sky',
		'chocolat',
		'girly'
	);

	private $icons = array(
		'info',
		'caution'
	);

	private $errors;

	public static function get_instance( $parent=null, $basename=null ) {
		static $instance;

		if ( ! $instance instanceof self ) {
			$instance = new self( $parent, $basename );
		}
		return $instance;
	}

	private function __construct( $parent, $basename ) {
		$this->basename = $basename;
	}

	public function add_actions() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function add_filters() {
		add_filter( 'admin_menu' , array( $this, 'add_page' ) );
		add_filter( 'plugin_action_links_' . $this->basename, array( $this, 'add_setting_link' ) );
	}

	public function add_page() {
		add_options_page(
			__( 'Alert Settings', 'simple-alert-for-old-post' ),
			__( 'Alert Settings', 'simple-alert-for-old-post' ),
			'administrator',
			'simple-alert-for-old-post',
			array( $this, 'menu' )
		);
	}

	public function admin_init() {
		if( ! isset( $_REQUEST['simple_old_alert_settings_isupdate'] ) ) return;
		if( check_admin_referer('simple-alert-for-old-post-key', 'simple-alert-for-old-post') ){
			$names = array(
				'date',
				'date_type',
				'theme',
				'icon',
				'message',
				'is_show_single',
				'is_show_page'
			);

			$result = $this->validation( $_REQUEST );

			if($result->errors){
				require_once __DIR__ . '/' . '../../res/views/notice_error.php';
				return;
			}

			foreach ($names as $name) {
				update_option( 'simple_alert_for_old_post_' . $name, $_REQUEST[$name] );
			}

			require_once __DIR__ . '/'. '../../res/views/notice_update.php';

		}
	}

	public function menu() {

		$params = array(
			'date'           => get_option( 'simple_alert_for_old_post_date'           , '1' ),
			'date_type'      => get_option( 'simple_alert_for_old_post_date_type'      , 'month' ),
			'theme'          => get_option( 'simple_alert_for_old_post_theme'          , 'default' ),
			'icon'           => get_option( 'simple_alert_for_old_post_icon'           , 'info' ),
			'message'        => get_option( 'simple_alert_for_old_post_message'        , __('This article has been written before more than %s, information might old.', 'simple-alert-for-old-post') ),
			'is_show_single' => get_option( 'simple_alert_for_old_post_is_show_single' , '1' ),
			'is_show_page'   => get_option( 'simple_alert_for_old_post_is_show_page'   , '0' )
		);

		require_once __DIR__ . '/../../res/views/settings_main.php';
	}

	public function add_setting_link( $links ) {
		$links[] = '<a href="' . menu_page_url( 'simple-alert-for-old-post', false ) . '">' . __( 'Settings', 'simple-alert-for-old-post' ) . '</a>';
		return $links;
	}

	public function validation( $params ) {
		$this->errors = new WP_Error();

		if( ! preg_match( '/^[0-9]+$/', $params['date'] ) ){
			$this->errors->add( '400', __('The date param allows only numeric values ​.', 'simple-alert-for-old-post') );
		}

		if( ! in_array( $params['date_type'], $this->date_types ) ) {
			$this->errors->add( '400', __('Please select the correct date type.', 'simple-alert-for-old-post') );
		}

		if( ! in_array( $params['theme'], $this->themes ) ) {
			$this->errors->add( '400', __('Please select the correct theme.', 'simple-alert-for-old-post') );
		}

		if( ! in_array( $params['icon'], $this->icons ) ) {
			$this->errors->add( '400', __('Please select the correct theme icon.', 'simple-alert-for-old-post') );
		}

		if( is_null( $params['message']) ) {
			$this->errors->add( '400', __('The body is empty, Please enter the text .', 'simple-alert-for-old-post') );
		}

		if( ! in_array( $params['is_show_single'], array('0', '1'), true ) ){
			$this->errors->add( '400', __('Invalid value display post', 'simple-alert-for-old-post') );
		}

		if( ! in_array( $params['is_show_page']  , array('0', '1'), true ) ){
			$this->errors->add( '400', __('Invalid value display page', 'simple-alert-for-old-post') );
		}

		return $this->errors;
	}

}
