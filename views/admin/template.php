<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <?php echo HTML::style(Route::get('admin/media')->uri(array('file' => '/css/screen.css')), array('media' => 'screen')); ?>
    <?php echo HTML::style(Route::get('admin/media')->uri(array('file' => '/css/style.css')), array('media' => 'screen')); ?>
    <?php echo HTML::style(Route::get('admin/media')->uri(array('file' => '/css/print.css')), array('media' => 'print')); ?>
    <?php echo HTML::style(Route::get('admin/media')->uri(array('file' => '/css/datePicker.css')), array('media' => 'screen')); ?>
    <?php echo HTML::style(Route::get('admin/media')->uri(array('file' => '/css/jquery.tooltip.css')), array('media' => 'screen')); ?>
    <!--[if IE]><![if gte IE 6]><![endif]-->
    <?php
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery-1.4.2.min.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery.validate.min.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery.confirm-1.3.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery.tooltip.min.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/localization/messages_nl.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/localization/methods_nl.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/date.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery.datePicker.js')));
    echo HTML::script(Route::get('admin/media')->uri(array('file' => '/js/jquery.tooltip.min.js')));
    ?>
    <script type="text/javascript">
//<![CDATA[
        $(document).ready(function() {
            $("form.validate").validate({errorClass: 'invalid', errorElement: 'span'});

             $('a.delete').confirm({
                timeout:4000,
                msg: '<?php echo __('confirm.msg'); ?> ',
                dialogShow:'fadeIn',
                wrapper: '<div class="confirm_wrapper"><\/div>',
                buttons: {
                    wrapper:'<button><\/button>',
                    separator:' ',
                    cls: 'positive',
                    ok: '<?php echo __('confirm.ok'); ?>',
                    cancel: '<?php echo __('confirm.cancel'); ?>'
                }
            });
            
            $('.tooltip,img').tooltip({
            	track: false, 
			    delay: 500, 
			    showURL: false, 
			    showBody: " - ", 
			    fade: 250 
			    });
            
			Date.format = 'dd-mm-yyyy';
			$('.date-pick').datePicker({clickInput:true})

        });
    //]]>
    </script>
    
    <?php
    if (isset($scripts)) {
    	foreach ($scripts as $script) {
    		echo HTML::script($script);
    	}
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
