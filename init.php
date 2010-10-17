<?php defined('SYSPATH') or die('No direct script access.');


	
Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
          'directory'  => 'admin',
          'controller' => 'main',
          'action'     => 'index',
      ));
      
      
      Route::set('admin/media', 'admin/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'directory'  => 'admin', 
		'controller' => 'media',
		'action'     => 'media',
		'file'       => NULL,
	));