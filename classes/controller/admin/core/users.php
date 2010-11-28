<?php
/**
 * Controller_Admin_Users class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Users extends Controller_Admin_Base {

	public $auth_required = true;
	public $secure_actions = array(
		'index' => 'admin',
		'add'  => 'admin',
		'delete' => 'admin'

	);

	protected $orm_name = 'user';

	public function before()
	{
		parent::before();

		$this->template->title = __('users');
	}


	/**
	 * save the user
	 * 
	 * @access public
	 * @param int $id. (default: null)
	 * @return void
	 */
	public function action_save($id = null)
	{
		$user = ORM::factory('user')->find((int) $id);

		$post = $_POST;
		if (!Arr::get($post, 'password')) {
			unset($post['password']);
			unset($post['password_confirm']);
		}

		$user->values($post);
		
		//validate
		if (!$user->id) {
			$valid = $user->check();
			if (!$valid) {
				$errors = $user->validate()->errors('register');
			}
		} else {
			$post = $user->validate_edit($post);
			$valid = $post->check();
			if (!$valid) {
				$errors = $post->errors('register');
			}
		}

		if ($valid) {

			$user->save();
			
			//save selected user roles
			if (Arr::get($_POST, 'role')) {
				//first, remove all roles
				foreach ($user->roles->find_all() as $role) {
					$user->remove('roles', $role);
				}
				
				//then add the new roles
				foreach ($_POST['role'] as $role_name => $checked) {
					if (!$user->has('roles', ORM::factory('role')->where('name', '=', $role_name)->find())) {
						$login_role = new Model_Role(array('name' =>$role_name));
						$user->add('roles', $login_role);
					}
				}

			}

			Message::instance()->succeed(__(':object saved'),  array(':object' => __($this->orm_name)));

			$this->request->redirect('admin/users');
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
