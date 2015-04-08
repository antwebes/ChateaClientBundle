$(document).ready( function() {
    $('input[data-id="channel_name"]').bind('keyup keydown blur', function(){
        var text = $(this).val();
        var regex = /[,\cG]/gm; //detecta espacions, caracter "," y ctr+G //los que no permite contener IRC en canal

        $('input[data-id="channel_irc_channel"]').val("#"+text.replace(regex, '').replace(/[\s]/gm, '-'));
    });
});