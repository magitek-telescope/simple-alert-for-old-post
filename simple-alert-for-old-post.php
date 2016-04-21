<?php
/**
 * Plugin Name: Simple Alert for Old Post
 * Version: 1.0.0
 * Description: This is the show alert plugin for old post.
 * Author: Potato4d(Hanatani Takuma)
 * Author URI: http://potato4d.me/
 * Text Domain: simple-alert-for-old-post
 * Domain Path: /res/languages
 * @package Simple Alert for Old Post
 */

require_once __DIR__ . '/src/admin/admin.php';
add_action( 'plugins_loaded', array( 'Simple_Alert_For_Old_Post', 'get_instance' ) );

class Simple_Alert_For_Old_Post {

	const ONE_DAY = 86400;

	private $dates = array(
		"day"   => 1,
		"month" => 30,
		"year"  => 365
	);

	private $admin;
	private $basename;

	public static function get_instance() {
		static $instance;

		if ( ! $instance instanceof self ) {
			$instance = new static;
		}
		return $instance;
	}

	private function __construct() {
		$this->load_textdomain();
		$this->basename = plugin_basename( __FILE__ );
		$this->admin = Simple_Alert_For_Old_Post_Admin::get_instance( $this, $this->basename );
		$this->add_actions();
		$this->add_filters();

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	private function add_actions() {
		$this->admin->add_actions();
		add_action( 'wp_head'    , array( $this, 'enqueue_script' ) );
		add_action( 'admin_head' , array( $this, 'admin_enqueue_script' ) );
	}

	private function add_filters() {
		$this->admin->add_filters();
		add_filter( 'the_content', array( $this, 'show_alert' ) );
	}

	private function load_textdomain() {
		load_plugin_textdomain( 'simple-alert-for-old-post', null, basename( __DIR__ ) . '/res/languages' );
	}

	public function show_alert( $content ) {
		if( ! $this->check_show_date() ) {
			return $content;
		}

		if( ! get_option( 'simple_alert_for_old_post_is_show_single', '1' ) && is_single() ){
			return $content;
		}

		if( ! get_option( 'simple_alert_for_old_post_is_show_page'  , '0' ) && is_page()   ){
			return $content;
		}

		$output  = $this->get_alert();

		$output .= $content;
		return $output;
	}

	public function enqueue_script() {
		wp_register_style( 'simple-alert-for-old-post-css', plugins_url( 'res/css/style.css' , __FILE__ ) );
		wp_enqueue_style('simple-alert-for-old-post-css');

		$img_url = array(
			'default' => plugins_url( 'res/images/Info.svg' , __FILE__ ),
			'caution' => plugins_url( 'res/images/Caution.svg' , __FILE__ )
		);
		echo <<< EOT
<style>
.simple-old-alert:not(.alert-caution):before,
.simple-old-alert:not(.alert-info):before{
	background-image: url({$img_url['default']});
}
.simple-old-alert.alert-caution:before{
	background-image: url({$img_url['caution']});
}

</style>
EOT;
	}

	public function admin_enqueue_script() {
		$this->enqueue_script();
		wp_register_style( 'simple-alert-for-old-post-admin-css', plugins_url( 'res/css/admin.css' , __FILE__ ) );
		wp_enqueue_style('simple-alert-for-old-post-admin-css');

		wp_register_script('simple-alert-for-old-post-admin-js' , plugins_url('res/js/main.js'    , __FILE__), array('jquery'), false, true);
		wp_enqueue_script('simple-alert-for-old-post-admin-js');
	}

	private function check_show_date() {
		$reference = (int)get_option( 'simple_alert_for_old_post_date', '1' ) * $this->dates[get_option( 'simple_alert_for_old_post_date_type', 'month' )];
		$date = (int)((int)( date('U') - get_the_date('U') ) * (1.0/self::ONE_DAY) );
		return $date > $reference;
	}

	private function get_alert(){

		$params = array(
			'date'           => get_option( 'simple_alert_for_old_post_date'           , '1' ),
			'date_type'      => get_option( 'simple_alert_for_old_post_date_type'      , 'month' ),
			'theme'          => get_option( 'simple_alert_for_old_post_theme'          , 'default' ),
			'icon'           => get_option( 'simple_alert_for_old_post_icon'           , 'info' ),
			'message'        => get_option( 'simple_alert_for_old_post_message'        , __('This article has been written before more than %s, information might old.', 'simple-alert-for-old-post') ),
			'is_show_single' => get_option( 'simple_alert_for_old_post_is_show_single' , '1' ),
			'is_show_page'   => get_option( 'simple_alert_for_old_post_is_show_page'   , '0' )
		);

		$message = sprintf( $params['message'], $params['date'] . __( $params['date_type'] . 's', 'simple-alert-for-old-post') );

		$output ="<div class='simple-old-alert alert-" . $params['theme'] . " alert-" . $params['icon'] . "'>";
			$output .= "<p class='alert-content'>";
				$output .= $message;
			$output .= "</p>";
		$output .= "</div>";

		return $output;

	}

}
