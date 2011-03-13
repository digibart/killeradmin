<?php
/**
 * Controller_Admin_Users class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Users extends Controller_Admin_Base {

	protected $auth_required = 'admin';
	protected $secure_actions = false;

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
	 * @param int     $id. (default: null)
	 * @return void
	 */
	public function action_save($id = null)
	{
		$user = ORM::factory('user', (int) $id);

		$post = $_POST;
		if (!Arr::get($post, 'password')) {
			unset($post['password']);
			unset($post['password_confirm']);
		}

		$user->values($post);

		try
		{
			$extra_rules = $user->get_password_validation($post);

			if (Arr::get($post, 'password')) {
				$extra_rules->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
			}

			$user->save($extra_rules);

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
		catch (ORM_Validation_Exception $e) {
			$errorstring = "";
			$errors = $e->errors('admin');
			foreach ($errors as $key => $msg) {
				if (is_string($msg)) {
					$errorstring .= $msg . "<br />";
				} elseif (is_array($msg)) {
					foreach ($msg as $key2 => $msg2) {
						$errorstring .= $msg2 . "<br />";
					}
				}
			}
			Session::instance()->set('post_data_user', $_POST);
			Message::instance()->error($errorstring);
			$this->request->redirect(Request::current()->referrer());
		}

	}

	/**
	 * create's a default user when the user-table is empty
	 *
	 * @access public
	 * @return void
	 */
	public function action_setup()
	{
		$count = ORM::factory('user')->count_all();

		if ($count == 0) {
			$user = ORM::factory('user');
			$user->username = 'admin';
			$user->password = 'admin';
			$user->email = 'user@example.com';
			$user->save();
			$user->add('roles', new Model_Role(array('name' =>'admin')));
			$user->add('roles', new Model_Role(array('name' =>'login')));
			$user->save();

			Message::instance()->info(__('new user created:<br><strong>username</strong>: admin<br><strong>password</strong>: admin'));


		} else {
			Message::instance()->error(__('access denied'));
		}

		$this->request->redirect('/admin/main/login');

	}
}

?>
