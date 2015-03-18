var api_endpoint = window.api_endpoint;
$("#btn-resend-confirmation-email").on('click',function(event){
    $("#div-resend-confirmation-email").removeClass('hide');
});
$("#form-resend-confirmation-email" ).submit(function( event ) {
    event.preventDefault();
    var userEmail = $("#inputEmailResend").val();
    jQuery.ajax({
        url: api_endpoint +'/api/me/resetting/confirmed-email?access_token=' + window.token + '&email='+ userEmail,
        type: 'get',
        dataType:'json',
        success: function (response) {
            $("#succes-resend-confirmation-email").html('<div class="alert alert-success">'+response+'</div>');
        },
        error: function(error){
            var apiError = JSON.parse(error.responseText);
            $("#succes-resend-confirmation-email").html('<div class="alert alert-danger">'+apiError.errors+'</div>');
        }
    });
});