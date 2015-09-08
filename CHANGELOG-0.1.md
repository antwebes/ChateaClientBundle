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
 
* 0.1.23(2015-09-XX)
 * Include data-behat to test go to page update photo profile