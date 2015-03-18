$(document).ready( function() {
    $('input[data-id="channel_name"]').stringToSlug({
        callback: function(text){
            $('input[data-id="channel_irc_channel"]').val("#"+text)
        }
    });
});