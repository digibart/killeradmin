<?php
/**
 * Controller_Admin_Users class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Settings extends Controller_Admin_Base {

	protected $orm_name = 'user';

	public function before()
	{
		parent::before();

		$this->template->title = __('settings');
	}

	public function action_index()
	{
		//get the view
		if (is_object($this->template->content) && get_class($this->template->content) == "View")
		{
			$view = $this->template->content;
		}
		else
		{
			$view = View::factory('admin/settings_index');
		}
		
		$this->template->content = $view
			->set('user', $this->user)
			->set('referrer', Session::instance()->get('requested_url'));
	}


	/**
	 * save the settings
	 *
	 * @access public
	 * @return void
	 */
	public function action_save()
	{
		$user = ORM::factory('user', $this->user->id);

		if ($this->request->post('password'))
		{
			$this->request->post('password', null);
			$this->request->post('password_confirm', null);
		}

		//set the values
		if (isset($user->save_columns) && is_array($user->save_columns))
		{
			$user->values(Arr::extract($this->request->post(), $user->save_columns));
		}
		else
		{
			$user->values($this->request->post());
		}

		//try saving it
		try
		{
			$extra_rules = $user->get_password_validation($this->request->post());

			if ($this->request->post('password'))
			{
				$extra_rules->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
			}

			$user->save($extra_rules);

			Killerflash::instance()->succeed(__(':object saved'),  array(':object' => __('settings')));

			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'settings')));
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
			Killerflash::instance()->error($errorstring);
			$this->request->redirect($this->request->referrer());
		}
	}
}

?>
