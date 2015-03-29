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