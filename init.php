<?php defined('SYSPATH') or die('No direct script access.');

Route::set(Kohana::config('admin.base_url'), Kohana::config('admin.base_url') . '(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
          'directory'  => 'admin',
          'controller' => 'main',
          'action'     => 'index',
      )); 

Route::set('admin/base_url', Kohana::config('admin.base_url') . '(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
          'directory'  => 'admin',
          'controller' => 'main',
          'action'     => 'index',
      ));       
      
Route::set('admin/media', Kohana::config('admin.base_url') .'/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'directory'  => 'admin', 
		'controller' => 'media',
		'action'     => 'media',
		'file'       => NULL,
	));
	
Route::set('admin/mini', Kohana::config('admin.base_url') .'/mini(/<file>)', 
	array(
		'file' => '.+'
	))
	->defaults(array(
		'directory'  => 'admin', 
		'controller' => 'media',
		'action'     => 'minify',
		'file'       => NULL,
	));