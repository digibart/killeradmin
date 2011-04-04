<?php


/**
 * Controller_Admin_Core_Dashboard class.
 * 
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Dashboard extends Controller_Admin_Base {

	// whom has acces to the columns?
	public $secure_actions = array(
		'left' => 'login',
		'center' => 'login',
		'right' => 'login'		
		);
	
	/**
	 * left column
	 * 
	 * @access public
	 * @return void
	 */
	public function action_left() {
		$this->auto_render = false;
		
		echo View::factory('admin/dashboard_left')
			->set('user', Auth::instance()->get_user());
	}
	
	/**
	 * center column
	 * 
	 * @access public
	 * @return void
	 */
	public function action_center() {
		$this->auto_render = false;
		
		echo View::factory('admin/dashboard_center')
			->set('user', Auth::instance()->get_user());
	}
	

	/**
	 * right column
	 * 
	 * @access public
	 * @return void
	 */
	public function action_right() {
		$this->auto_render = false;
		
		echo View::factory('admin/dashboard_right')
			->set('user', Auth::instance()->get_user());
	}

}

?>
