<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::$config->load('admin');

Route::set($config->base_url, $config->base_url . '(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'main',
		'action'     => 'index',
	));

Route::set('admin/base_url', $config->base_url . '(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'main',
		'action'     => 'index',
	));

Route::set('admin/media', $config->base_url .'/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'media',
		'action'     => 'media',
		'file'       => NULL,
	));

Route::set('admin/mini', $config->base_url .'/mini(/<dir>(/<file>))', array('file' => '.+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'media',
		'action'     => 'minify',
		'file'       => NULL,
	));