<?php defined('SYSPATH') or die('No direct script access.');
return array
(
	'KillerFile'    => array
	(
		'driver'             => 'file',
		'cache_dir'          => APPPATH.'cache',
		'default_expire'     => 86400*7,
	)
);