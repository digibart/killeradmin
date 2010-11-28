<?php
/**
 * Model_User class.
 *
 * @package Killer-admin
 * @category Model
 * @extends Model_Auth_User
 */
class Model_User extends Model_Auth_User {

	protected $_filters = array (
		true       => array('Security::xss_clean' => array()),
	);

	/**
	 * validate_edit function.
	 * 
	 * @access public
	 * @param mixed & $array
	 * @return void
	 */
	public function validate_edit(& $array)
	{
		// Initialise the validation library and setup some rules
		$array = Validate::factory($array)
		->rules('password',  array('min_length' => array(5),'max_length' => array(42)))
		->rules('username', $this->_rules['username'])
		->rules('email', $this->_rules['email'])
		->rules('password_confirm', $this->_rules['password_confirm'])
		->filter('username', 'trim')
		->filter('email', 'trim')
		->filter('password', 'trim')
		->filter('password_confirm', 'trim');

		//Add Model_Auth_User callbacks
		foreach ($this->_callbacks as $field => $callbacks) {
			foreach ($callbacks as $callback) {
				$array->callback($field, array($this, $callback));
			}
		}

		return $array;
	}


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
