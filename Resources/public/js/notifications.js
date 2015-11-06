/**
 * Created by ant4 on 30/10/15.
 */

var BoilerNotifications = (function($) {
    var notify = function(message){
        var tmpl = '<div>' + message + '</div><br/>';

        $('*[data-role="notificatons"]')
            .removeClass('hidden')
            .find('#message_header_center')
            .append($(tmpl));
    };

    return {
        notity: notify
    }
})(jQuery);

(function(window, sessionStorage){
    var userDataAjaPromise = null;

    /**
     * Returns a promise that gets the user data. The first time it is called, it creates the promise making the ajax request.
     * The other times the created promise is returned
     * @param apiEndpoint
     * @param token
     * @param userId
     * @returns {*}
     */
    var getUserData = function(apiEndpoint, token, userId){
        var url = apiEndpoint + '/api/users/' + userId;

        if(userDataAjaPromise == null){

            userDataAjaPromise = $.ajax({
                url: url,
                headers: {Authorization: 'Bearer ' + token},
            });
        }

        return userDataAjaPromise;
    };

    /**
     * Checks from the sessionStorage or calculates from the userData if a given parameter is setted to true
     * @param userId
     * @param apiEndpoint
     * @param token
     * @param storageKey
     * @param message
     * @param remoteVerifyer
     */
    var checkFromStorageOrRemote = function(userId, apiEndpoint, token, storageKey, message, remoteVerifyer){
        var doNotify = function(mustNotify){ // this is executed when we know we have the key in the storage
            if(!mustNotify){
                BoilerNotifications.notity(message);
            }
        };

        // if we have not the key in the session storage, we fetch data from the server to search if the user has a profile photo
        if(sessionStorage == null || sessionStorage.getItem(storageKey) == null){
            getUserData(apiEndpoint, token, userId).then(function(data){
                var mustNotify = remoteVerifyer(data) ? 1 : 0;

                if(sessionStorage !== null){
                    sessionStorage.setItem(storageKey, mustNotify);
                }

                //now that we have the key in the storage we do the logic
                doNotify(mustNotify == 1);
            });
        }else{ // we allready have the key on the storage, we do the logic
            var mustNotify = sessionStorage.getItem(storageKey) == 1;
            doNotify(mustNotify);
        }
    };

    /**
     * Notifies if a user has no profile photo
     * @param userId
     * @param apiEndpoint
     * @param token
     * @param message
     */
    var notifyIfUserHasNoProfilePhoto = function(userId, apiEndpoint, token, message){
        var storageKey = 'has_profile_photo';
        var remoteVerifyer = function(data){
            return typeof(data['profile_photo']) != 'undefined' ? 1 : 0;
        };

        checkFromStorageOrRemote(userId, apiEndpoint, token, storageKey, message, remoteVerifyer);
    };

    /**
     * Notifies if a user has no city
     * @param userId
     * @param apiEndpoint
     * @param token
     * @param message
     */
    var notifyIfUserHasNoCity = function(userId, apiEndpoint, token, message){
        var storageKey = 'has_city';
        var remoteVerifyer = function(data){
            return typeof(data['city']) != 'undefined' ? 1 : 0;
        };

        checkFromStorageOrRemote(userId, apiEndpoint, token, storageKey, message, remoteVerifyer);
    };

    /**
     * Sets the user data promise to null
     */
    var clearUserData = function(){
        userDataAjaPromise = null;
    };

    window.notifyIfUserHasNoProfilePhoto = notifyIfUserHasNoProfilePhoto;
    window.notifyIfUserHasNoCity = notifyIfUserHasNoCity;
    window.clearUserData = clearUserData;
})(
    window,
    typeof(window.sessionStorage) !== "undefined" ? window.sessionStorage : null //this way we don't have to use allways typeof every time we want to check if the browser support session storage
);