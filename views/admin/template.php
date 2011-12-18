<?php

/* Message::instance()->succeed(ucfirst(__('access granted'))); */
/* Message::instance()->info(ucfirst(__('access granted')) . "<a href='#'>ga teruf</a>"); */
/* Message::instance()->error(ucfirst(__('access granted'))); */

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo Kohana::$config->load('admin.company_name'); ?>  &#9733; <?php echo $title; ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php
	
	$media = Route::get('admin/media');
    
    echo KillerFile::instance('css','screen')->add_files(array(
	    	$media->uri(array('file' => '/css/bakplaat.nosprite.css')),
    	))->get_tag(array('media' => 'screen'));
    	
    echo KillerFile::instance('css','print')->add_files(array(
	    	$media->uri(array('file' => '/css/print.css')),
    	))->get_tag(array('media' => 'print'));

     
    echo KillerFile::instance('js')->add_files(array(
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
    	echo KillerFile::instance('js','js-cust')->add_files($scripts)->get_tag();
    }
    
    ?>
    
</head>

<body>
	<div id="wrap">
		<header>
			<div class="row">
				<div class="twelve columns">
					<h1 class="fancy"><?php echo $title; ?></h1>
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
					<?php echo $content; ?>			
				</div>
			</div>
		</div>
	</div>
	<footer class="container">
		<div class="row footer">
			<div class="twelve columns last">
		    	Designed, Developed and Created by <?php echo html::anchor('http://www.digibart.nl', 'Digibart'); ?>
			</div>
		</div>
	</footer>		
</body>
</html>
