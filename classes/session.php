<?php defined('SYSPATH') or die('No direct script access.');

abstract class Session extends Kohana_Session {

	public function get_once($key, $default = null) {
		$value = parent::get($key, $default);
		parent::delete($key);
		
		return $value;
	}

}
