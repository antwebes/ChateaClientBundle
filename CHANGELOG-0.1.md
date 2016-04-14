CHANGELOG for 0.1.x
===================

This changelog references the relevant changes (bug and security fixes) done
in 0.1 minor versions.

To get the diff for a specific change, go to https://github.com/antwebes/ChateaClientBundle/commit/XXX where XXX is the change hash
To get the diff between two versions, go to https://github.com/antwebes/ChateaClientBundle/compare/v0.1.0...v0.1.1

* 0.1.2 (2015-03-18)

 * separate route api.xml, and include window.homepage in UserNickSuggestions to customize call to api

* 0.1.3 (2015-03-19)
 * hotfix, method onKernelException must listen only apiException

* 0.1.4 (2015-03-19)
 * translate errors in reset password
 
* 0.1.5 (2015-03-19)
 * translate errors in change email
 * the ircChannel also can contain the ñ char
 * fixed some translations spanish
 * traslated pending server errors
 * Restyle nick suggestions, in one file
 * Separate template register in two templates ( _register_body.html.twig )
 
* 0.1.6 (2015-03-23)
 * translate server side error errors in user registration
 * translate server side error in channel registration
 * Send object user to template registerSuccess.html.twig

* 0.1.7 (2015-03-26)
 * Include contentType: "application/json" call ajax userNickSuggestion
 * Update documentation
 * Create event USER_REGISTER_SUCCESS, to can modify client to register user
 * Email field required in form resetting password
 * Path resset password method GET
 * Combo countries in template

* 0.1.8 (2015-03-29)
 * Include captcha in page reset password
 * Include constraint to language of user

* 0.1.9 (2015-04-08)
 * Fix error with file language.yml
 * Irc channel name can not containt the & char

* 0.1.10 (2015-04-10)
 * Update version ChateaSecureBundle v0.1.2
 * remove function jquery.stringToSlug, used in function channelName->channelIrcName

* 0.1.11 (2015-04-15)
 * Include repeat form email to register and settings users
 * Include check that two inputs of email are same with jquery

* 0.1.12(2015-04-17)
 * Include translations to page re-send email confirmation & disable button when success this action

* 0.1.13(2015-04-22)
 * Update version chateaSecureBundle 0.1.3, users locked cannot login

* 0.1.14(2015-05-07)
 * Photos user can to be paginated
 
* 0.1.15(2015-05-1)
 * Update version captch to branch 2.3
 
* 0.1.16(2015-05-20)
 * Fix error with translations in confirm-email page
 * Include branch userProfile, now user register and register profile, and photo with ajax
 * Update version ChateaSecureBundle
 * When API calls the comfirmed URL now the data of the user is taken by refresh token from the API
 * Update version chateaClientLib v0.1.2
 
* 0.1.17(2015-05-28)
 * Include link to user settings in page confirmation email
 * change version recaptcha 2.3.*@dev -> 2.3.*
 * include manage of channels
 
* 0.1.18(2015-06-10)
 * Added ChannelManager::addFanToChannel method
 * refix error in ProfileController, send param userId to create profile

* 0.1.19(2015-07-09)
 * Implemented getUserVisits method
 * Added findOutstandingUsers method
 * When editing the profile phot we get the whole user
 * Redirect to index when user is logged in register page
 * improve_upload_photo
 * update version ChateaClientLib v0.1.3
 
* 0.1.20(2015-07-22)
 * change url resetear-contraseña -> resetear-contrasena
 * refix desing page profile and edit profile photo
 * send events to analytics in form register
 * spinner in button save of form create profile
 * check max photo length 1 Mb
 * show error customize when user register channel without validate
 * In register profile get id Of user session
 * externalize to template twig, js that manage show/hide button photo profile upload, and this template use prettyBundle
 * hotfix with show visit, check if is array
 * include spin in button upload photo profile
 
* 0.1.21(2015-07-27)
 * update version chateaSecureBundle to 0.1.5 
 * Marked UserController::confirmedAction as deprecated
  
* 0.1.22(2015-08-31)
 * update version chateaClientLib to 0.1.4 
 * Added report photo method
 * add messages not sexual content in photos
 
* 0.1.23(2015-09-07)
 * add page who saw my profile
 * init organize folders and template 
 * Update version chateaClientLib v0.1.5
 
* 0.1.24(2015-09-15)
 * Include data-behat to test go to page update photo profile
 * Include photo actually in form update photo profile
 * add method searchUserByNamePaginated in usermanager
 * remove refresh token now to do autologin we use a parameter autologin
 * update method searchUserByNamePaginated now call findAll with filter partial_name and add basic test
 * ATTENTION! This version has dependeces with foreign bundles
 
* 0.1.25 (2015-09-16)
 * Remove dependeces with foreign bundles
 * Include filter twig UserProfilePhotoUrlExtension
 * Hotfix From edit profile sent page confirm email
 
* 0.1.26 (2015-09-22)
 * Added support to authenticate as guest 
 * Implemented update of user city 
 * antwebes/chatea-client-lib 0.1.6
 
* 0.1.27 (2015-10-05)
 * Version recaptcha 2.3.x-dev
 * Inform error in profile register if photo can not be uploaded
 * updatePhotoAction require @ApiUser
 
* 0.1.28 (2015-10-09)
 * Added chatea_client.countries_file parameter
 
* 0.1.29 ( 2015-10-26)
 * Added filters to outstanding users when find with searcher
 * AccessDeniedHttpException in page register confirmed, if user not logged
 * Update version chateaSecureBundle 0.1.6
 
* 0.1.30 ( 2015-11-12)
 * Include notification if User has not photo profile
 * Include method wrap to Throw exception when api not return an object, the method is in "BaseManager" with name "executeAndHandleApiException"
 * Combo register and change city include field language

* 0.1.31 ( 2015-11-13)
 * Implemente findAll of PhotoManager 
 * Update version 0.1.7 chateaClientLib
 * include phpunit version >=4.7.6
 * Add translated fields of city 
 
* 0.1.32 ( 2015-12-01)
 * Include notifications to user, as use has not photo profile or user has not city
 * Save info to show notifications in session storage
 * Range Year to edit profile between 90 and 18
 * Include new bundle of captcha
 
* 0.1.33 (2015-12-10)
 * Added notifyPhotoWithMostPhotos method in notifications.js 
 
* 0.1.34 (2015-12-28)
 * ResetPassword: Include template footer to can include data in templat. Include ResetPassword:_reset_footer
 
* 0.1.35 (2015-12-30)
 * Page welcome
 * Refix error in js check register availab   le nick and email, send duplicated events analytics

* 0.1.36 (2016-01-08)
 * Force user tu upload photo (optional with parameter) and can skip profile.
 * Hotfix error in form register, when username is not valid, now translate message correctly
 * Improve form register with text in rigthbar
 * RENAME folder r->R : ChateaClientBundle:User:register -> ChateaClientBundle:User:Register
 
* 0.1.37 (2016-01-28)
 * url from register, when email is used, to login and reset-password with params type=notify_register
 * delete text customize, now separate in templates, so client can override section of template register
 * hotfix in template register and rightbar register var client is not defined
 
* 0.1.38 (2016-03-15)
 * The api change response "This value should be the user current password." -> "This value should be the user's current password."
 * Redirect when user logued join in /register
 * Change way to create profile, before redirect but now always use form to edit profile and if user has not profile create profile or if user has profile so, edit profile
 
* 0.1.39 (2016-03-18)
 * Include param root_route, to redirect to route root of web client
 
* 0.1.40 (2016-04-04)
 * Fix error in process register, if user select and unselect imagen, now disable button register again, before unselect image
 * Show error when user with photo disabled, want upload other photo
 
* 0.1.41 (2016-04-14)
 * Show globalStatistics, as cont disabled photos