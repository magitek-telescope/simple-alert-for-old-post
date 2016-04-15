<?php
/**
 * Plugin Name: Simple Alert for Old Post
 * Version: 0.1-alpha
 * Description: PLUGIN DESCRIPTION HERE
 * Author: Potato4d(Hanatani Takuma)
 * Author URI: YOUR SITE HERE
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: simple-alert-for-old-post
 * Domain Path: /languages
 * @package Simple Alert for Old Post
 */

class Simple_Alert_For_Old_Post {

	const ONE_DAY = 86400;

	public static function get_instance() {
		static $instance;

		if ( ! $instance instanceof Simple_Alert_For_Old_Post ) {
			$instance = new static;
		}
		return $instance;
	}

	public function __construct() {
		$this->load_textdomain();
		$this->add_actions();
		$this->add_filters();
	}

	private function add_actions() {
		add_action( 'wp_head', array( $this, 'enqueue_script' ) );
	}

	private function add_filters() {
		add_filter( 'the_content', array( $this, 'show_alert' ) );
		add_filter( 'admin_menu' , array( $this, 'add_page' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_setting_link' ) );
	}

	private function load_textdomain() {
		load_plugin_textdomain( 'simple-alert-for-old-post', basename( __DIR__ ) . '/languages' );
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

	public function menu() {
		echo '<h2>' . __( 'Settings', 'simple-alert-for-old-post' ) . '</h2>';
	}

	public function add_setting_link( $links ) {
		$links[] = '<a href="' . menu_page_url( 'simple-alert-for-old-post', false ) . '">' . __( 'Settings', 'simple-alert-for-old-post' ) . '</a>';
		return $links;
	}

	public function show_alert( $content ) {
		$reference = 3;
		$date = (int)((int)(date('U') - get_the_date('U') ) / 86400);
		if ( $date < $reference ){
			return $content;
		}

		$output  = "<div class='simple-old-alert alert-default'>";
		$output .= "<p class='alert-content'>";
		$output .= "この記事は";
		$output .= $reference;
		$output .= "ヶ月以上前に書かれたもので、情報が古い場合があります。";
		$output .= "</p>";
		$output .= "</div>";
		$output .= $content;
		return $output;
	}

	public function enqueue_script() {
		wp_register_style ('simple-alert-for-old-post-csst'  , plugins_url('res/css/style.css' , __FILE__));

		wp_enqueue_style('simple-alert-for-old-post-csst');
	}

}

add_action( 'plugins_loaded', array( 'Simple_Alert_For_Old_Post', 'get_instance' ) );
