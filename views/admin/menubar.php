<?php

foreach ($items as $title => $url) {

	$class = (Route::get('admin/base_url')->uri(array('controller' => Request::current()->controller())) == $url) ? "positive" : "";
	$class .= " button";
	echo html::anchor($url, $title, array('class' => $class));

}

?>
<?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'logout')), ucfirst(__('logout')), array('class' => 'button')); ?>
