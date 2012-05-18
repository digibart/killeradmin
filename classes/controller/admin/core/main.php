<?php

/**
 * Controller_Admin_Main class.
 *
 * @extends Controller_Admin_Base
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Main extends Controller_Admin_Base {

	/**
	 * user login
	 *
	 * @access public
	 * @return void
	 */
	public function action_login()
	{

		$failures = (int) Cache::instance()->get(md5($_SERVER['REMOTE_ADDR']));
		$captcha = Captcha::instance('admin');

		$this->template->title = ucfirst(__('login'));
		$this->template->content = View::factory('admin/form_login');

		// if visitor got the wrong username/password 3 times, then validate visitor with a captcha
		if ($failures > 3)
		{
			$this->template->content->set('captcha', $captcha->render());
		}

		if ($this->request->post())
		{
			$post = new Validation($this->request->post());
			$post->rule('username', 'not_empty')
				->rule('captcha', 'Captcha::valid', array($this->request->post('captcha')));


			if ($post->check())
			{
				//Instantiate a new user
				$user = ORM::factory('user');
				$status = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $this->request->post('remember'));


				//If user is logged then redirect
				if ($status)
				{
					Message::instance()->succeed(ucfirst(__('access granted')));
					Cache::instance()->set(md5($_SERVER['REMOTE_ADDR']), 0 , 300);
					Request::current()->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main')));
				}
				else
				{
					//save the login failure
					Cache::instance()->set(md5($_SERVER['REMOTE_ADDR']), $failures + 1, 300);

					Message::instance()->error(__('username or password incorrect'));
				}

			}
			else
			{
				$errorstring = "";
				foreach ($post->errors('validate') as $key => $error)
				{
					$errorstring .= $error . "<br>";
				}
				Message::instance()->error($errorstring);
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
		$failures = (int) Cache::instance()->get(md5($_SERVER['REMOTE_ADDR']));
		$captcha = Captcha::instance('admin');

		$this->template->title = ucfirst(__('forgot password'));
		$this->template->content = View::factory('admin/form_password');

		if ($failures > 3)
		{
			$this->template->content->set('captcha', $captcha->render());
		}

		if ($this->request->post())
		{
			$post = new Validation($this->request->post());
			$post->rule('username', 'not_empty')
			->rule('username', 'min_length', array('username', 5))
			->rule('username', 'max_length', array('username', 42))
			->rule('email', 'email', array($this->request->post('email')))
			->rule('captcha', 'Captcha::valid', array($this->request->post('captcha')));


			if ($post->check())
			{
				$user = ORM::factory('user')
				->where('username', '=', $this->request->post('username'))
				->where('email', '=', $this->request->post('email'))
				->find();

				if ($user->loaded())
				{
					$user->resetPassword();
					Request::current()->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')));
				} else
				{
					Cache::instance()->set(md5($_SERVER['REMOTE_ADDR']), $failures + 1, 300);
					Message::instance()->error(__(':object not found', array(':object' => __('user'))));
				}

			} else
			{
				$errorstring = "";
				foreach ($post->errors('validate') as $key => $error)
				{
					$errorstring .= $error . "<br>";
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
		Request::current()->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')));
	}

	/**
	 * if no page is loaded, show the dashboard
	 *
	 * @access public
	 * @return void
	 */
	public function action_index()
	{

		$this->template->title = null;
		$this->template->content = View::factory('admin/dashboard');

	}


}

?>
