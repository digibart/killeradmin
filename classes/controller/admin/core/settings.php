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
		$user = ORM::factory('user', $this->user->id);

		if (!Arr::get($_POST, 'password')) {
			unset($_POST['password']);
			unset($_POST['password_confirm']);
		}

		$_POST['username'] = $user->username;

		$user->values($_POST);
		
		try
		{
			$extra_rules = $user->get_password_validation($_POST);

			if (Arr::get($_POST, 'password')) {
				$extra_rules->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
			}

			$user->save($extra_rules);
			
			Message::instance()->succeed(__(':object saved'),  array(':object' => __('settings')));

			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'settings')));
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
			$this->request->redirect($this->request->referrer());
		}
	}
}

?>
