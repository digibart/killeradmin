<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Application defaults
	'admin' => array(
		'current_page'   => array('source' => 'query_string', 'key' => 'page'), // source: "query_string" or "route"
		'total_items'    => 0,
		'items_per_page' => 20,
		'view'           => 'pagination/admin',
		'auto_hide'      => TRUE,
	),

);
