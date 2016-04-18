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

require_once __DIR__ . '/src/admin/admin.php';
add_action( 'plugins_loaded', array( 'Simple_Alert_For_Old_Post', 'get_instance' ) );

class Simple_Alert_For_Old_Post {

	const ONE_DAY = 86400;

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
		$this->basename = plugin_basename( __FILE__ );
		$this->admin = Simple_Alert_For_Old_Post_Admin::get_instance( $this, $this->basename );
		$this->load_textdomain();
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
		load_plugin_textdomain( 'simple-alert-for-old-post', basename( __DIR__ ) . '/languages' );
	}

	public function show_alert( $content ) {
		$reference = 3;
		$date = (int)((int)( date('U') - get_the_date('U') ) / self::ONE_DAY);
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
		wp_register_style( 'simple-alert-for-old-post-css', plugins_url( 'res/css/style.css' , __FILE__ ) );
		wp_enqueue_style('simple-alert-for-old-post-css');

		$img_url = array(
			'default' => plugins_url( 'res/images/info.svg' , __FILE__ ),
			'caution' => plugins_url( 'res/images/caution.svg' , __FILE__ )
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

}
