<?php
/**
 * Controller_Admin_Users class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Users extends Controller_Admin_Base {

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
	public function action_save()
	{
		$id = $this->request->param('id');
		$user = ORM::factory('user', (int) $id);

		if (!$this->request->post('password'))
		{
			$this->request->post('password', null);
			$this->request->post('password_confirm', null);
		}

		//set the values
		if (isset($object->save_columns) && is_array($object->save_columns))
		{
			$object->values(Arr::extract($post, $object->save_columns));
		}
		else
		{
			$object->values($post);
		}

		try
		{
			$extra_rules = $user->get_password_validation($this->request->post());

			if ($this->request->post('password'))
			{
				$extra_rules->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
			}

			$user->save($extra_rules);

			if ($this->request->post('role'))
			{
				//first, remove all roles
				foreach ($user->roles->find_all() as $role)
				{
					$user->remove('roles', $role);
				}

				//then add the new roles
				foreach ($this->request->post('role') as $role_name => $checked)
				{
					if (!$user->has('roles', ORM::factory('role')->where('name', '=', $role_name)->find()))
					{
						$login_role = new Model_Role(array('name' =>$role_name));
						$user->add('roles', $login_role);
					}
				}

			}

			Message::instance()->succeed(__(':object saved'),  array(':object' => __($this->orm_name)));

			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'users')));

		}
		catch (ORM_Validation_Exception $e)
		{
			$errorstring = "";
			$errors = $e->errors('admin');
			foreach ($errors as $key => $msg)
			{
				if (is_string($msg))
				{
					$errorstring .= $msg . "<br />";
				} elseif (is_array($msg))
				{
					foreach ($msg as $key2 => $msg2)
					{
						$errorstring .= $msg2 . "<br />";
					}
				}
			}
			Session::instance()->set('post_data_user', $this->request->post());
			Message::instance()->error($errorstring);
			$this->request->redirect(Request::current()->referrer());
		}

	}
}

?>
