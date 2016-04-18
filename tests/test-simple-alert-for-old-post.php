<?php
require_once __DIR__ . '/' . '../src/admin/admin.php';

class SimpleAlertForOldPostTest extends WP_UnitTestCase {

	private $instance;
	private $instance_reflection;
	private $normal_params;
	private $normal_checks;

	public function __contruct(){
		parent::setUp();

		$this->instance = Simple_Alert_For_Old_Post::get_instance();
		$this->instance_reflection = new ReflectionClass( $this->instance );
	}

	public function test_check_show_date() {

	}

	/**
	 * get method shortcut.
	 *
	**/
	private function get_method( $name ){
		$method = $this->reflection->getMethod( $name );
		$method->setAccessible( true );
		return $method;
	}

}

