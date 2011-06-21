$(document).ready(function() {
    $("form.validate").validate({
        errorClass: 'invalid',
        errorElement: 'span'
    });

    $('a.delete').confirm({
        timeout: 4000,
        msg: del_confirm,
        dialogShow: 'fadeIn',
        wrapper: '<div class="confirm_wrapper"><\/div>',
        buttons: {
            wrapper: '<button><\/button>',
            separator: ' ',
            cls: 'positive',
            ok: del_ok,
            cancel: del_cancel
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
    $('.date-pick').datePicker({
        clickInput: true
    })

});
