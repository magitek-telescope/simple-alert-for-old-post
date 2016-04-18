<?php
require_once __DIR__ . '/' . '../src/admin/admin.php';

class SimpleAlertForOldPostTestAdmin extends WP_UnitTestCase {

	private $admin;
	private $admin_reflection;
	private $normal_params;
	private $normal_checks;

	public function __construct(){
		$this->admin = Simple_Alert_For_Old_Post_Admin::get_instance();
		$this->admin_reflection = new ReflectionClass( $this->admin );

		$this->normal_params = array(
			'date'           => '1',
			'date_type'      => 'month',
			'theme'          => 'default',
			'icon'           => 'info',
			'message'        => 'この記事は%s以上前に書かれたもので、情報が古い場合があります。',
			'is_show_single' => '1',
			'is_show_page'   => '0'
		);
	}

	/**
	 * This is root function that call all test codes.
	 *
	**/
	function test_validation() {
		$this->validation_pass();
		$this->validation_null();
		$this->validation_selects();
		$this->validation_checkboxes();
	}

	/**
	 * Valid value pass test @ error check.
	 *
	**/
	private function validation_pass() {
		$wp_error = $this->admin->validation( $this->normal_params );

		$this->assertEquals(
			$wp_error->errors,
			array()
		);
	}

	/**
	 * All params null check.
	 *
	**/
	private function validation_null() {
		foreach ($this->normal_params as $key => $value) {
			$abnormal_params = $this->normal_params;
			$this->check_invalid_param( $abnormal_params, $key, null);
		}
	}

	/**
	 * Call validation_select many times...
	 *
	**/
	private function validation_selects(){
		$this->validation_select( 'date_type', 'date_types', 'error_text' );
		$this->validation_select( 'theme'    , 'themes'    , 'error_text' );
	}

	/**
	 * Checkbox validation
	 *
	**/
	private function validation_checkboxes() {
		$checks = array(
			'is_show_single',
			'is_show_page'
		);
		foreach ($checks as $check) {
			$check_params = $this->normal_params;

			for ($i=0; $i <= 3; $i++) {
				$check_params[$check] = (String)$i;
				$wp_error = $this->admin->validation( $check_params );
				$this->assertEquals(
					count( $wp_error->errors ),
					( ( $i >= 2 ) ? 1 : 0 )
				);
			}
		}
	}

	/**
	 * check select validation ( invalid param check and valid params check ).
	 *
	**/
	private function validation_select( $name, $property, $error_text ){
		$check_params = $this->normal_params;

		foreach ($this->get_property( $property ) as $value) {
			$check_params[$name] = $value;

			$wp_error = $this->admin->validation( $check_params );

			$this->assertEquals(
				$wp_error->errors,
				array()
			);
		}
		$this->check_invalid_param( $check_params, $name, $error_text);
	}

	/**
	 * 単一の不正な値がある場合に、エラー個数が正常に1つになるかどうかのテスト
	 *
	**/
	private function check_invalid_param( $params, $name, $val ) {
		$params[$name] = $val;

		$wp_error = $this->admin->validation( $params );

		$this->assertEquals(
			count($wp_error->errors),
			1
		);
	}

	/**
	 * get property shortcut.
	 *
	**/
	private function get_property( $name ){
		$property = $this->admin_reflection->getProperty( $name );
		$property->setAccessible( true );
		return $property->getValue( $this->admin );
	}
}

