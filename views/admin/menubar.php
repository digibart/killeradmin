<?php

foreach ($items as $title => $url) {

	$class = (Request::instance()->directory . "/" . Request::instance()->controller == $url) ? "positive" : "";
	$class .= " button";
/* 	echo "<li>" . html::anchor($url, $title, array('class' => $class)) . "</li>"; */
	echo html::anchor($url, $title, array('class' => $class));
	
}

?>
<?php echo html::anchor('admin/main/logout', __('logout'), array('class' => 'button')); ?>
