<?php
/**
 * Controller_Admin_Users class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Settings extends Controller_Admin_Base {

	protected $auth_required = 'login';
	protected $secure_actions = false;

	protected $orm_name = 'user';

	public function before()
	{
		parent::before();

		$this->template->title = __('settings');
	}
	
	public function action_index() {
		$this->template->content = View::factory('admin/settings_index')
			->set('user', $this->user)
			->set('referrer', Session::instance()->get('requested_url'));
	}


	/**
	 * save the user
	 *
	 * @access public
	 * @param int     $id. (default: null)
	 * @return void
	 */
	public function action_save($id = null)
	{
		$user = $this->user;

		$post = $_POST;
		if (!Arr::get($post, 'password')) {
			unset($post['password']);
			unset($post['password_confirm']);
		}
		
		$post['username'] = $user->username;
		
		$user->values($post);

		$post = $user->validate_edit($post);
		$valid = $post->check();
		if (!$valid) {
			$errors = $post->errors('register');
		}
	

		if ($valid) {

			$user->save();	

			Message::instance()->succeed(__(':object saved'),  array(':object' => __('settings')));

			$this->request->redirect('admin/settings');
		}
		else {
			//if user is not validated, show the errors
			$errorstring = "";
			foreach ($errors as $key => $msg) {
				$errorstring .= $msg . "<br />";
			}

			Session::instance()->set('post_data_user', $_POST);
			Message::instance()->error($errorstring);
			$this->request->redirect(Request::$referrer);

		}

	}
}

?>
