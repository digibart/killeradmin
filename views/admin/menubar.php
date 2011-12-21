<nav>
<?php

foreach ($items as $title => $url) {

	$class = (Route::get('admin/base_url')->uri(array('controller' => Request::current()->controller())) == $url) ? "primary" : "";
	$class .= " button";
	echo html::anchor($url, $title, array('class' => $class));

}

?>
</nav>

<div class="tools">
	<div class="background">
	<?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'settings', 'action' => 'index')), KillerAdmin::SpriteImg('cog') , array('title' => __('settings'), 'class' => 'tooltip')); ?>
	<?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'logout')), KillerAdmin::SpriteImg('open-door'), array('title' => __('logout'), 'class' => 'tooltip')); ?>
	</div>
</div>
