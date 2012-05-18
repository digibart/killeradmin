<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Flash message handler, based on Kohana_Message
 *
 * @package Killer-admin
 * @category Helper
 */
abstract class Killeradmin_Killerflash
{

	/** Config */
	private $_config;

	/** Cached info message */
	private $info;

	/** Cached error message */
	private $error;

	public function __construct()
	{
		$this->_config = Kohana::$config->load('message');
	}

	public static function instance()
	{
		static $instance;
		if ( ! isset($instance))
		{
			$instance = new Killerflash;
		}
		return $instance;
	}

	public function info($msg, array $values=NULL, $lang='en-us')
	{
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_info', __($msg, $values, $lang));
	}

	public function succeed($msg, array $values=NULL, $lang='en-us')
	{
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_succeed', __($msg, $values, $lang));
	}

	public function error($msg, array $values=NULL, $lang='en-us')
	{
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_error', __($msg, $values, $lang));
	}

	public function get($type=NULL)
	{
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
