$(document).ready(function() {
    $("form.validate").validate({
        errorClass: 'error',
        errorElement: 'label'
    });

    $('a.delete').confirm({
        timeout: 40000,
        msg: del_confirm,
        dialogShow: 'fadeIn',
        wrapper: '<div class="confirm"><\/div>',
        buttons: {
            wrapper: '<button><\/button>',
            separator: '',
            cls: 'primary',
            ok: del_ok,
            cancel: del_cancel
        }
    });
    
    //init tooltips
    var tipsy_default = {};
    
	$('.tooltip.topwards').tipsy($.extend(tipsy_default, {gravity: 'n'}));
    $('.tooltip.rightwards').tipsy($.extend(tipsy_default, {gravity: 'w'}));
    $('.tooltip.downwards').tipsy($.extend(tipsy_default, {gravity: 'n'}));
	$('.tooltip.leftwards').tipsy($.extend(tipsy_default, {gravity: 'e'}));
	
    Date.format = 'dd-mm-yyyy';
});
