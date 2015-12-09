/**
 * Created by ant4 on 30/10/15.
 */

var BoilerNotifications = (function($) {
    var notify = function(message, variables){
        var holder = '';

        // if we pass variables we replace in the message the %VARIABLE_NAME%
        if(typeof(variables) != 'undefined'){
            for(variable in variables){
                holder = '%' + variable + '%';
                message = message.replace(holder, variables[variable]);
            }
        }

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

(function(window, sessionStorage, localStorage){
    var userDataAjaxPromise = null;
    var userPhotoVotesAjaxPromise = null;

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

        if(userDataAjaxPromise == null){

            userDataAjaxPromise = $.ajax({
                url: url,
                headers: {Authorization: 'Bearer ' + token},
            });
        }

        return userDataAjaxPromise;
    };

    var getUserPhotos  = function(apiEndpoint, token, userId){
        var url = apiEndpoint + '/api/users/' + userId + '/photos?limit=1&order=numberVotes=desc';

        if(userPhotoVotesAjaxPromise == null){

            userPhotoVotesAjaxPromise = $.ajax({
                url: url,
                headers: {Authorization: 'Bearer ' + token},
            });
        }

        return userPhotoVotesAjaxPromise;
    };

    /**
     * Gets data stored under the given key as JSON or the default value {id: null, numberVotes: 0}
     * @param localKey
     * @returns {Outlayer.Item|*}
     */
    function getUserMostVotedPhotoFromStorage(localKey) {
        var photoData = localStorage.getItem(localKey);

        if (photoData !== null) {
            photoData = JSON.parse(photoData);
        } else {
            photoData = {id: null, numberVotes: 0};
        }

        return photoData;
    }

    /**
     * Checks from the sessionStorage or calculates from the userData if a given parameter is setted to true
     * @param userId
     * @param apiEndpoint
     * @param token
     * @param storageKey
     * @param message
     * @param remoteVerifyer
     */
    var checkFromStorageOrRemote = function(userId, apiEndpoint, token, storageKey, message, remoteVerifyer, dataFetcher){
		storageKey = storageKey + '_' + userId; // make sure the session belongs to the user

        var doNotify = function(mustNotify){ // this is executed when we know we have the key in the storage
            if(!mustNotify){
                BoilerNotifications.notity(message);
            }
        };

        // if we have not the key in the session storage, we fetch data from the server to search if the user has a profile photo
        if(sessionStorage == null || sessionStorage.getItem(storageKey) == null){
            dataFetcher(apiEndpoint, token, userId).then(function(data){
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
            return typeof(data['profile']) != 'undefined' && typeof(data['profile']['profile_photo']) != 'undefined' ? 1 : 0;
        };

        checkFromStorageOrRemote(userId, apiEndpoint, token, storageKey, message, remoteVerifyer, getUserData);
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

        checkFromStorageOrRemote(userId, apiEndpoint, token, storageKey, message, remoteVerifyer, getUserData);
    };

    /**
     * Notifies if a user has new voted photos
     * @param userId
     * @param apiEndpoint
     * @param token
     * @param message
     */
    var notifyPhotoWithMostPhotos = function(userId, apiEndpoint, token, message){
        var storageKey = 'have_notified_promoted_photo';
        var remoteVerifyer = function(data){
            var photoResource = data.resources[0];

            // if the user has no phtos we return
            if(typeof(photoResource) == 'undefined'){
                return true;
            }

            var photoId = photoResource.id;
            var numberVotes = photoResource.number_votes;
            var localKey = 'most_voted_photo_' + userId;
            var photoData = getUserMostVotedPhotoFromStorage(localKey);

            localStorage.setItem(localKey, JSON.stringify({id: photoId, numberVotes: numberVotes}));

            // only notify if votes > 0 and its a diferent photo as the stored in the local storage
            if(!(photoData.id == photoId && photoData.numberVotes == numberVotes) && numberVotes > 0){
                getUserData(apiEndpoint, token, userId).then(function(data){
                    BoilerNotifications.notity(message, {user: data['username'], votes: numberVotes});
                });
            }

            return true; // we don't need to notify again, its done only once logged in
        };

        checkFromStorageOrRemote(userId, apiEndpoint, token, storageKey, message, remoteVerifyer, getUserPhotos);
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
    window.notifyPhotoWithMostPhotos = notifyPhotoWithMostPhotos;
})(
    window,
    typeof(window.sessionStorage) !== "undefined" ? window.sessionStorage : null, //this way we don't have to use allways typeof every time we want to check if the browser support session storage
    typeof(window.localStorage) !== "undefined" ? window.localStorage : null
);