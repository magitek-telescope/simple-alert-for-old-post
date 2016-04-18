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

	private $theme_types = array(
		'info',
		'caution'
	);

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
		$names = array(
			'date',
			'date_type',
			'theme',
			'theme_type',
			'message'
		);

		foreach ($names as $name) {
			update_option( 'simple_alert_for_old_post_' . $name, $_REQUEST[$name] );
		}

		require_once __DIR__ . '/../../res/views/notice_update.php';
	}

	public function menu() {

		$params = array(
			'date'       => get_option( 'simple_alert_for_old_post_date'      , '1' ),
			'date_type'  => get_option( 'simple_alert_for_old_post_date_type' , 'month' ),
			'theme'      => get_option( 'simple_alert_for_old_post_theme'     , 'default' ),
			'theme_type' => get_option( 'simple_alert_for_old_post_theme_type', 'info' ),
			'message'    => get_option( 'simple_alert_for_old_post_message'   , 'この記事は%s以上前に書かれたもので、情報が古い場合があります。' )
		);

		require_once __DIR__ . '/../../res/views/settings_main.php';
	}

	public function add_setting_link( $links ) {
		$links[] = '<a href="' . menu_page_url( 'simple-alert-for-old-post', false ) . '">' . __( 'Settings', 'simple-alert-for-old-post' ) . '</a>';
		return $links;
	}

}
