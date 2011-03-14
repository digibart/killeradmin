<?php

foreach ($items as $title => $url) {
		$class = (Request::current()->directory() . "/" . Request::current()->controller() == $url) ? "positive" : "";
		$class .= " button";
		echo html::anchor($url, $title, array('class' => $class));

}

?>
<?php echo html::anchor('admin/main/logout', ucfirst(__('logout')), array('class' => 'button')); ?>
