<?php
/**
 * Model_User class.
 *
 * @package Killer-admin
 * @category Model
 * @extends Model_Auth_User
 */
class Model_User extends Model_Auth_User {

	protected $_sorting = array('username' => 'asc');


	/**
	 * resetPassword and send email
	 *
	 * @access public
	 * @return void
	 */
	public function resetPassword()
	{
		$password = Text::random('distinct', rand(8, 10));

		$this->password = $password;
		$this->save();

		$company_name =  Kohana::$config->load('admin.company_name');

		$to = array($this->email => $this->username);
		$from = array($this->email => $company_name);

		$subject = __('new password for :company_name', array(':company_name' =>$company_name));

		$message = View::factory('admin/email_password')
		->set('password', $password)
		->set('user', $this)
		->set('company_name', $company_name);

		if (Killeradmin::email($to, $from, $subject, $message))
		{
			Killerflash::instance()->succeed(__('password send to :email', array(':email' => $this->email)));
		}
		else
		{
			Killerflash::instance()->error(__('could not send email to :email', array(':email' => $this->email)));
		}
	}
}

?>
