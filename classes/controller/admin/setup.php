<?php

/**
 * Check modules and config, and create a first user
 *
 * @extends Controller_Template
 * @package
 * @category
 */
class Controller_Admin_Setup extends Controller_Template {

	public $template = 'admin/template';

	/**
	 * check if you're allowd to see this page
	 *
	 * Denie access if there are already users in the database, or $envirement != DEVELOPMENT
	 *
	 * @access public
	 * @return void
	 */
	public function before()
	{
		parent::before();

		// denie access if there are already users found, or $enviroment is not DEVELOPMENT
		$user_count = (int) ORM::factory('user')->count_all();

		if (Kohana::$environment != KOHANA::DEVELOPMENT || $user_count !== 0)
		{
			Kohana::$log->add(Log::ERROR, 'Access to Killeradmin setup denied: ~ :reason', array(':reason' => 'Kohana::$environment != KOHANA::DEVELOPMENT or ORM::factory(\'user\')->count_all() !== 0'));
			Message::instance()->error(ucfirst(__('access denied. More info in logs')));
			$this->request->redirect(Route::get('admin/base_url')->uri());
		}
	}

	/**
	 * action_index function.
	 *
	 * @access public
	 * @return void
	 */
	public function action_index()
	{

		$this->template->title = __('Setup KillerAdmin');
		$this->template->menu = null;

		$errors = 0;

		/**************************************/
		/* check the modules
		/**************************************/

		$modules = array('ORM' => false, 'AUTH' => false, 'CACHE' => false, 'DATABASE' => false, 'PAGINATION' => false, 'CAPTCHA' => false);

		foreach ($modules as $module => $exists)
		{
			if (class_exists($module))
			{
				$modules[$module] = true;
			}
			else
			{
				$modules[$module] = false;
				$errors++;
			}
		}

		/**************************************/
		/* check the config
		/**************************************/

		$config = array();

		// check if auth hash_key is set
		if (strlen(Kohana::$config->load('auth.hash_key')) < 30)
		{
			$config['auth'] = KillerAdmin::spriteImg('cross') . Kohana::message('admin', 'no hash_key');
			$errors++;
		}
		else
		{
			$config['auth'] = KillerAdmin::spriteImg('tick');
		}

		// and if killeradmin config is set
		if (!Kohana::$config->load('admin'))
		{
			$config['admin'] = KillerAdmin::spriteImg('cross') . Kohana::message('admin', 'copy config');
		}
		else
		{
			$config['admin'] = KillerAdmin::spriteImg('tick');
		}


		/**************************************/
		/* roundup
		/**************************************/

		if ($errors > 0)
		{
			Message::instance()->error(__('fix the errors first'));
		}

		$this->template->content = View::factory('admin/setup')
		->set('modules', $modules)
		->set('config', $config)
		->set('post_data', Session::instance()->get_once('post_data'))
		->set('errors', $errors);

	}

	/**
	 * Create a user, and redirect to login page
	 *
	 * @access public
	 * @return void
	 */
	public function action_create_user()
	{

		$user = ORM::factory('user');
		$password = Text::random('distinct', 8);

		$user->username = Arr::get($_POST, 'username');
		$user->email = Arr::get($_POST, 'email');
		$user->password = $password;


		try
		{
			$user->save();
			$user->add('roles', ORM::factory('role', array('name' => 'login')));
			$user->add('roles', ORM::factory('role', array('name' => 'admin')));

			Message::instance()->succeed('user created. Password: <b>' . $password . '</b>');
			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')));
		}
		catch (ORM_Validation_Exception $e)
		{
			$errorstring = "";
			$errors = $e->errors('register');

			foreach ($errors as $key => $msg)
			{
				$errorstring .= $msg . "<br />";
			}
			Session::instance()->set('post_data', $_POST);
			Message::instance()->error($errorstring);
			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'setup')));

		}


	}
}

?>
