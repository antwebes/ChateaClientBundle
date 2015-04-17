var api_endpoint = window.api_endpoint;
$("#btn-resend-confirmation-email").on('click',function(event){
    $("#div-resend-confirmation-email").removeClass('hide');
});

// if in template there are messages to translate, it translate otherwise show the error original
function transServerError(error){
    if(typeof messages != 'undefined' && typeof messages.server_errors[error] != 'undefined'){
        return messages.server_errors[error];
    }
    return error;
}


$("#form-resend-confirmation-email" ).submit(function( event ) {
    event.preventDefault();
    var userEmail = $("#inputEmailResend").val();
    jQuery.ajax({
        url: api_endpoint +'/api/me/resetting/confirmed-email?access_token=' + window.token + '&email='+ userEmail,
        type: 'get',
        dataType:'json',
        success: function (response) {
            $("#succes-resend-confirmation-email").html('<div class="alert alert-success">'+messages.success+'</div>');
            $("[data-id=submit-confirm-email]").prop('disabled', true);
        },
        error: function(error){
            var apiError = JSON.parse(error.responseText);
            apiError.errors = transServerError(apiError.errors);
            $("#succes-resend-confirmation-email").html('<div class="alert alert-danger">'+apiError.errors+'</div>');
        }
    });
});