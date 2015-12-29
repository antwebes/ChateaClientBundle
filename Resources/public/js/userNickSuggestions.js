//
// $('#element').donetyping(callback[, timeout=1000])
// Fires callback when a user has finished typing. This is determined by the time elapsed
// since the last keystroke and timeout parameter or the blur event--whichever comes first.
//   @callback: function to be called when even triggers
//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
//              caused by blur.
// Requires jQuery 1.7+
//
;(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 800; // 1 second default timeout = 1e3
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('change',function(e){
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too premptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && e.keyCode!=8) return;

                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

var emailCheckMinlength = 4;

function userNickSuggestions(messages) {
    $(document).ready(function () {
        $('span[data-id="email-suggestions"]').html("");

        var suggestionMinlength = 4;

        function transServerError(error, throwEvent){
        	var throwEvent = typeof throwEvent !== 'undefined' ? throwEvent : true;
        	if (throwEvent){
        		sendEvent("registration", "error", error);
        	}            
            if(typeof messages.server_errors[error] != 'undefined'){
                return messages.server_errors[error];
            }

            return error;
        }

        function checkEmail(emailInput) {
            var url_source = '/api/users/email-available';
            
            if(typeof window.homepage != 'undefined'){
                //maybe the path homepage, finish with /, we have to remove this / of url_source
                var lastChar = window.homepage.substr(window.homepage.length - 1);
                if (lastChar === '/'){
                    url_source = url_source.substring(1);
                }
                url_source = window.homepage + url_source;
            }

            $.ajax({
                url: url_source,
                data: {email: emailInput},
                dataType: "json",
                contentType: "application/json",
                success: function (response) {

                    $('span[data-id="email-suggestions"]').html('<p class="alert-success">' + messages.mail_is_aviable + '</p>');
                    if(response.smtp_valid != undefined) {
                        if(!response.smtp_valid) {
                            $('span[data-id="email-suggestions"]').html('<p class="alert alert-warning"><i class="icon-attention"></i> ' + messages.mail_is_available_but_not_validate + '</p>');
                            sendEvent("registration", "warning", "smtp_validation");
                        }
                    }
                },
                error: function (responseError) {
                    var response = $.parseJSON(responseError.responseText);
                    var messageError = response.errors.email.message;
                    $('span[data-id="email-suggestions"]').html('<p class="alert-danger">' + transServerError(messageError, false) + '</p>');
                    sendEvent("registration", "error", messageError);
                }

            });
        }

        function writeUsernameSuggestions(suggestions) {
            if(typeof suggestions == 'undefined'){
                return;
            }

            var suggestionsSpans = [];
            var i = 0;
            suggestions.forEach(function (entry) {
                i++;
                suggestionsSpans.push('<span data-id="suggestion-btn-link" data-suggestion-sources="username" data-num="'+i+'" class="btn-link suggestion-btn-link">' + entry + '</span>');
            });

            var suggestionMessage = messages.nick_suggestions + ': ' + suggestionsSpans.join(' | ');

            $('div[data-id="suggestions-username-block"]')
                .html(suggestionMessage)
                .show();
            $('.suggestion-btn-link').click(function () {
                    $("#user_registration_username").val($(this).text());
                    var username = $('input[data-id="registration_form_username"]').val();
                    var email = $('input[data-id="registration_form_emial"]').val();
                    findSuggestions(username, email);
                    sendEvent("registration", "use_suggestion", "suggestion-" +$(this).data("num"));
                });

        }

        //check if nick is available, and show suggestions if is not available
        function findSuggestions(usernameInput, emailInput) {
            var url_source = '/api/users/username-available';
            
            if(typeof window.homepage != 'undefined'){
                //maybe the path homepage, finish with /, we have to remove this / of url_source
                var lastChar = window.homepage.substr(window.homepage.length - 1);
                if (lastChar === '/'){
                    url_source = url_source.substring(1);
                }
                url_source = window.homepage + url_source;
            }

            $('div[data-id="suggestions-username-block"]').hide();
            $.ajax({
                url: url_source,
                data: {username: usernameInput, email: emailInput},
                dataType: "json",
                contentType: "application/json",
                success: function (response) {
                    $('span[data-id="username-validate"]').html('<p class="alert-success">' + messages.username_is_aviable + '</p>');
                },
                error: function (responseError) {
                    var response = $.parseJSON(responseError.responseText);
                    writeUsernameSuggestions(response.suggestion);
                    if (response.errors.username) {
                    	var message;
                    	if (response.suggestion){
                    		//Dont send event in translation error
                    		message = transServerError(response.errors.username.message, false);
                    		sendEvent("registration", "error", "username_not_available");
                        }else{
                    		//Send event in translation error
                        	message = transServerError(response.errors.username.message);
                        }
                        $('span[data-id="username-validate"]').html('<p class="alert-danger">' + message + '</p>');
                    } else if (response.errors.email) {
                        $('span[data-id="username-validate"]').html('<p class="alert-danger">' + transServerError(response.errors.email.message) + '</p>');
                    }
                }

            });
        }


        $('input[data-id="registration_form_username"]').donetyping(function () {
            var value = $(this).val();
            var email = $('input[data-id="form_email"]').val();
            if (value.length > suggestionMinlength) {
                $('span[data-id="username-validate"]').html("");
                findSuggestions(value, email);
            }
        });
        $('input[data-id="form_email"]').donetyping(function () {
            var value = $(this).val();
            if (value.length > emailCheckMinlength) {
                checkEmail(value);
                checkTwoFieldsEmailAndShowError(messages);
            }
        });
        // Check if two fields emails mismatch
        $('input[data-id="form_email_second"]').donetyping(function () {
            var value = $(this).val();
            if (value.length > emailCheckMinlength) {
                checkEmail(value);
                checkTwoFieldsEmailAndShowError(messages);
            }
        });
    });
}

function areSameTwoFieldsEmail(){
    // The two emails inputs
    var email           = $('input[data-id="form_email"]').val();
    var confirmEmail    = $('input[data-id="form_email_second"]').val();
 
    // Check for equality with the emails inputs
    if (email != confirmEmail ) {
        return false;
    } else {
        return true;
    }
}

function checkTwoFieldsEmailAndShowError(messages){
    if (!($('input[data-id="form_email"]').val().length === 0 || $('input[data-id="form_email_second"]').val().length === 0)){
        if (!areSameTwoFieldsEmail()){
            $('span[data-id="email-mismatch-error"]').html('<p class="alert-danger">' + messages.emails_not_mismatch + '</p>');
            sendEvent("registration", "error", "email_not_match");
        }else{
            $('span[data-id="email-mismatch-error"]').html('');
        }
    }
}

//this function is out of suggestions nick, so can I use this js in settings of user
function checkEmailsMismatch(messages){
    // Check if two fields emails mismatch
    $('input[data-id="form_email_second"]').donetyping(function () {
        var value = $(this).val();
        if (value.length > emailCheckMinlength) {
            checkTwoFieldsEmailAndShowError(messages);
        }
    });

    $('input[data-id="form_email"]').donetyping(function () {
        var value = $(this).val();
        if (value.length > emailCheckMinlength) {
            checkTwoFieldsEmailAndShowError(messages);
        }
    });
}


function sendEvent(category, action, label, value) {
    if (typeof ga !== 'undefined') {
        if(typeof value !== 'undefined') {
            ga('send', 'event', category, action, label, value);
        }else {
            ga('send', 'event', category, action, label);
        }
    }
}