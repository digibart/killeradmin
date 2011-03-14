<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'company_name' => 'myKillerAdmin',
	'menu' =>
	array(
		'admin/main' => array(
			'name' => ucfirst(__('dashboard')),
			'secure_actions' => array(
				'default' => 'login'
			),
		),
		'admin/users' => array(
			'name' => ucfirst(__('users')),	// name shown in menu
			'secure_actions' => array(		// Controls access to specified actions
				'default' => 'login',		// default role required to access the actions in this controller
				'index' => 'login',			// required role to list users 
				'add' => 'admin',			// required role to add new users
				'edit' => 'login',			// required role to edit users
				'delete' => 'admin'			// required role to delete users
			),
		),
		'admin/settings' => array(
			'name' => ucfirst(__('settings')),
			'secure_actions' => array(
				'default' => 'login'
			),
		),
	)
);
