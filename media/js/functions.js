$(document).ready(function() {
    $("form.validate").validate({
        errorClass: 'error',
        errorElement: 'label'
    });

    $('a.delete').confirm({
        timeout: 40000,
        msg: del_confirm,
        dialogShow: 'fadeIn',
        wrapper: '<div id="tooltip" class="confirm"><\/div>',
        buttons: {
            wrapper: '<button><\/button>',
            separator: '',
            cls: 'primary',
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
});
