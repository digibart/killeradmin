<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php
	
	$media = Route::get('admin/media');
    
    echo KillerJS::instance('css','screen')->add_files(array(
	    	$media->uri(array('file' => '/css/screen.css')),
	    	$media->uri(array('file' => '/css/style.css')),
	    	$media->uri(array('file' => '/css/datePicker.css')),
	    	$media->uri(array('file' => '/css/jquery.tooltip.css')),
    	))->get_tag(array('media' => 'screen'));
    	
    echo KillerJS::instance('css','print')->add_files(array(
	    	$media->uri(array('file' => '/css/print.css')),
    	))->get_tag(array('media' => 'print'));

     
    echo KillerJS::instance('js')->add_files(array(
	    	$media->uri(array('file' => '/js/jquery-1.6.1.min.js')),
	    	$media->uri(array('file' => '/js/jquery.validate.min.js')),
	    	$media->uri(array('file' => '/js/jquery.confirm-1.3.js')),
	    	$media->uri(array('file' => '/js/jquery.tooltip.min.js')),
	    	$media->uri(array('file' => '/js/localization/messages_nl.js')),
	    	$media->uri(array('file' => '/js/localization/methods_nl.js')),
	    	$media->uri(array('file' => '/js/date.js',)),
	    	$media->uri(array('file' => '/js/jquery.datePicker.js')),
	    	$media->uri(array('file' => '/js/jquery.tooltip.min.js')),
	    	$media->uri(array('file' => '/js/functions.js')),  	
    	))->get_tag();

    ?>

	<script type="text/javascript">
		var del_confirm = '<?php echo __('confirm.msg'); ?>';
		var del_ok = '<?php echo __('confirm.ok'); ?>';
		var del_cancel = '<?php echo __('confirm.cancel'); ?>';
	</script>
    
    <?php
    if (isset($scripts) && count($scripts) > 0) {
    	echo KillerJS::instance('js','js-cust')->add_files($scripts)->get_tag();
    }
    
    ?>
    
</head>

<body>
	<div class="container">
	    <div id="head" class="span-23 prefix-1 last">
	    	<h1 id="head" class="fancy"><?php echo Kohana::config('admin.company_name'); ?></h1>
	    </div>
		<div id="menubar" class="span-23  prefix-1">			   
	        <?php echo $menu; ?>
	    </div>
	
	    <div id="content" class="span-22 prefix-1 suffix-1 last">
	    	<H2><?php echo $title; ?></H2>
	        <?php
	        
	        $msg = Message::instance()->get();
	
	        if (!empty($msg)) {
	            echo "<br>" . $msg;
	        }
	        ?><?php echo $content; ?>
	    </div>
    </div>
    <div class="container footer">
    	Designed, Developed and Created by <?php echo html::anchor('http://www.digibart.nl', 'Digibart'); ?>
    </div>
    
</body>
</html>
