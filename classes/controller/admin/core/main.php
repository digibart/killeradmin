<?php

/**
 * Controller_Admin_Main class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Main extends Controller_Admin_Base {

	public $auth_required = false;
	public $secure_actions = array(
		'index' => 'login'
	);

	/**
	 * user login
	 * 
	 * @access public
	 * @return void
	 */
	public function action_login()
	{
		$this->template->title = ucfirst(__('login'));
		$this->template->content = View::factory('admin/form_login');

		if ($_POST) {
			//Instantiate a new user
			$user = ORM::factory('user');
			//Check Auth
			$status = $user->login($_POST);

			//If the post data validates using the rules setup in the user model
			if ($status) {
				Message::instance()->succeed(ucfirst(__('access granted')));
				Request::instance()->redirect('admin/main');
			} else {
				Message::instance()->error(__('username or password incorrect'));
			}

		}

	}

	/**
	 * reset password and email it to user
	 * 
	 * @access public
	 * @return void
	 */
	public function action_forgot()
	{
		$this->template->title = ucfirst(__('forgot password'));
		$this->template->content = View::factory('admin/form_password');


		if ($_POST) {
			$post = new Validate($_POST);
			$post->rule('username', 'not_empty')
				->rule('username', 'min_length', array(5))
				->rule('username', 'max_length', array(42))
				->rule('email', 'email', array());

			if ($post->check()) {
				$user = ORM::factory('user')
					->where('username', '=', (string) $_POST['username'])
					->where('email', '=', (string) $_POST['email'])
					->find();

				if ($user->loaded()) {
					$user->resetPassword();
					Request::instance()->redirect('admin/main/login');
				} else {
					Message::instance()->error(__(':object not found', array(':object' => __('user'))));
				}


			} else {
				$errorstring = "";
				foreach ($post->errors('validate') as $key => $error) {
					$errorstring .= $error . "<br>";
					echo $error;
				}
				Message::instance()->error($errorstring);
			}
		}
	}

	/**
	 * logout user
	 * 
	 * @access public
	 * @return void
	 */
	public function action_logout()
	{
		Auth::instance()->logout();
		Message::instance()->info(ucfirst(__('access terminated')));
		Request::instance()->redirect('admin/main/login');
	}

	/**
	 * if no page is loaded, show the dashboard
	 * 
	 * @access public
	 * @return void
	 */
	public function action_index()
	{

		$this->template->title = "Dashboard";
		$this->template->content = View::factory('admin/dashboard');

	}


}

?>
