<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'menu' =>
	array(
		'admin/dashboard' => array(
			'name' => ucfirst(__('dashboard')),
			'secure_actions' => array(
				'index' => 'login'
			),
		),
	)
);
