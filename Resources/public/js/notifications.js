/**
 * Created by ant4 on 30/10/15.
 */

var BoilerNotifications = (function($) {
    var notify = function(message){
        var tmpl = '<div class="col-xs-12" id="message_header_center"><div class="alert alert-warning">' + message + '</div></div>';

        $('*[data-role="notificatons"]')
            .removeClass('hidden')
            .append($(tmpl));
    };

    return {
        notity: notify
    }
})(jQuery);

(function(window, sessionStorage){
    /**
     * Notifies if a user has no profile photo
     * @param userId
     * @param apiEndpoint endpoint to make ajax calls
     * @param token Token to make ajax calls
     */
    var notifyIfUserHasNoProfilePhoto = function(userId, apiEndpoint, token, message){
        var storageKey = 'has_profile_photo';

        var doNotifyIfUserHasNoProfilePhoto = function(hasProfilePhoto){ // this is executed when we know we have the key in the storage
            if(!hasProfilePhoto){
                BoilerNotifications.notity(message);
            }
        };

        // if we have not the key in the session storage, we fetch data from the server to search if the user has a profile photo
        if(sessionStorage == null || sessionStorage.getItem(storageKey) == null){
            $.ajax({
                url: apiEndpoint + '/api/users/' + userId + '/profiles?access_token='+token,
                success: function(data){
                    var hasProfilePhoto = typeof(data['profile_photo']) != 'undefined' ? 1 : 0;

                    if(sessionStorage !== null){
                        sessionStorage.setItem(storageKey, hasProfilePhoto);
                    }

                    //now that we have the key in the storage we do the logic
                    doNotifyIfUserHasNoProfilePhoto(hasProfilePhoto == 1);
                }
            });
        }else{ // we allready have the key on the storage, we do the logic
            var hasProfilePhoto = sessionStorage.getItem(storageKey) == 1;
            doNotifyIfUserHasNoProfilePhoto(hasProfilePhoto);
        }
    };

    window.notifyIfUserHasNoProfilePhoto = notifyIfUserHasNoProfilePhoto;
})(
    window,
    typeof(window.sessionStorage) !== "undefined" ? window.sessionStorage : null //this way we don't have to use allways typeof every time we want to check if the browser support session storage
);