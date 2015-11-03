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


// a wrapper arround sessionStorage in case the browser dosn't have
var BoilerSessionStorage = (function(){
    var wraperStorage = {};
    var items = {};

    if(typeof(window.sessionStorage) !== "undefined"){
        wraperStorage.has = function(key){
            return sessionStorage.getItem(key) !== null;
        };

        wraperStorage.get = function(key){
            return sessionStorage.getItem(key);
        }

        wraperStorage.set = function(key, value){
            sessionStorage.setItem(key, value);
        }
    }else{ // in case we don't have sessionStorage
        wraperStorage.has = function(key){
            if(typeof(items[key]) !== "undefined"){
                return true;
            }

            return false;
        };

        wraperStorage.get = function(key){
            if(typeof(items[key]) !== "undefined"){
                return true;
            }

            return items[key];
        }

        wraperStorage.set = function(key, value){
            items[key] = value;
        }
    }

    return wraperStorage;
})();

/**
 * Notifies if a user has no profile photo
 * @param userId
 * @param apiEndpoint endpoint to make ajax calls
 * @param token Token to make ajax calls
 */
var notifyIfUserHasNoProfilePhoto = function(userId, apiEndpoint, token, message){
    var storageKey = 'has_profile_photo';

    var doNotifyIfUserHasNoProfilePhoto = function(){ // this is executed when we know we have the key in the storage
        if(BoilerSessionStorage.get(storageKey) == 0){
            BoilerNotifications.notity(message);
        }
    };

    // if we have not the key in the session storage, we fetch data from the server to search if the user has a profile photo
    if(!BoilerSessionStorage.has(storageKey)){
        $.ajax({
            url: apiEndpoint + '/api/users/' + userId + '/profiles?access_token='+token,
            success: function(data){
                BoilerSessionStorage.set(storageKey, typeof(data['profile_photo']) != 'undefined' ? 1 : 0);
                //now that we have the key in the storage we do the logic
                doNotifyIfUserHasNoProfilePhoto();
            }
        });
    }else{ // we allready have the key on the storage, we do the logic
        doNotifyIfUserHasNoProfilePhoto();
    }
};