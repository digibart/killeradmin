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

		$to = $this->email;
		$from = $this->email;
		$subject = __('password new');
		$message = View::factory('admin/email_password')
			->set('password', $password)
			->set('user', $this);

		Email::send($to, $from, $subject, strip_tags($message), $message);

		Message::instance()->succeed(__('password send to :email', array(':email' => $this->email)));
	}
}

?>
