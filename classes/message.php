<?php defined('SYSPATH') or die('No direct script access.');

class Message extends Kohana_Message {

	function __construct() {
		parent::__construct();
		$this->_config = Kohana::config('message');
	}

	public function succeed($msg, array $values=NULL, $lang='en-us')
	{
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_succeed', __($msg, $values, $lang));
	}
	
	public function get($type=NULL) {
		if ($type === NULL)
		{
			return $this->get('succeed').$this->get('info').$this->get('error');
		}
		else
		{
			$session = Session::instance($this->_config['session_type']);
			$msg = $session->get('flash_msg_'.$type, FALSE);
			if ($msg !== FALSE)
			{
				$session->delete('flash_msg_'.$type);
				$this->{$type} = $msg;

				$return = $this->_config['tags'][$type]['open'];
				$return .= $msg;
				$return .= $this->_config['tags'][$type]['close'];
				return $return;
			}
			else
			{
				return '';
			}
		}
	}

}
