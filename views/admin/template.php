<!DOCTYPE html>
<html>
<head>
    <title><?php echo Kohana::$config->load('admin.company_name'); ?> | <?php echo $title; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<?php
	
	$media = Route::get('admin/media');
    
    echo KillerFile::instance('css','screen')->add_files(array(
	    	$media->uri(array('file' => '/css/reset.css')),
	    	$media->uri(array('file' => '/css/killeradmin.css')),
    	))->get_tag(array('media' => 'screen,handheld'));
    	
    echo KillerFile::instance('css','print')->add_files(array(
	    	$media->uri(array('file' => '/css/print.css')),
    	))->get_tag(array('media' => 'print'));

    ?>
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->


	<script type="text/javascript">
		var del_confirm = '<?php echo __('confirm.msg'); ?>';
		var del_ok = '<?php echo __('confirm.ok'); ?>';
		var del_cancel = '<?php echo __('confirm.cancel'); ?>';
	</script>
    
    <?php
    if (isset($scripts) && count($scripts) > 0) {
    	echo KillerFile::instance('js','js-cust')->add_files($scripts)->get_tag();
    }
    
    ?>
    
</head>

<body>
	<div id="wrap">
		<header>
			<div class="row">
				<div class="twelve columns">
					<h1 class="fancy"><?php echo Kohana::$config->load('admin.company_name'); ?></h1>
				</div>
				<div class="last twelve columns">
					<?php echo $menu; ?>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="row">
				<div class="twenty columns prefix-2">			
					<?php				
					$msg = Message::instance()->get();
				
					if (!empty($msg)) {
						echo $msg;
					}
					?>
				</div>	
				<div class="row">
					<?php if ($title) : ?><h2 class="fancy"><?php echo $title; ?></h2><?php endif; ?>
					<?php echo $content; ?>			
				</div>
			</div>
		</div>
	</div>
	<footer class="container">
		<div class="row footer">
			<div class="twentyfour columns last">
		    	Designed, Developed and Created by <?php echo html::anchor('http://pixelbakkerij.nl', 'Pixel Bakkerij'); ?>
			</div>
		</div>
	</footer>	
	<?php
	    echo KillerFile::instance('js')->add_files(array(
	    	$media->uri(array('file' => '/js/libs/jquery-1.6.1.min.js')),
	    	$media->uri(array('file' => '/js/libs/jquery.validate.min.js')),
	    	$media->uri(array('file' => '/js/libs/jquery.confirm-1.3.js')),
	    	$media->uri(array('file' => '/js/libs/jquery.tooltip.min.js')),
	    	$media->uri(array('file' => '/js/localization/messages_nl.js')),
	    	$media->uri(array('file' => '/js/localization/methods_nl.js')),
	    	$media->uri(array('file' => '/js/libs/date.js',)),
	    	$media->uri(array('file' => '/js/libs/jquery.datePicker.js')),
	    	$media->uri(array('file' => '/js/libs/jquery.tooltip.min.js')),
	    	$media->uri(array('file' => '/js/functions.js')),  	
    	))->get_tag();
    ?>    	
</body>
</html>
