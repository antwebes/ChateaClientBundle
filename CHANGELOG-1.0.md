CHANGELOG for 1.0.x
===================

This changelog references the relevant changes (bug and security fixes) done
in 1.0 minor versions.

To get the diff for a specific change, go to https://github.com/antwebes/ChateaClientBundle/commit/XXX where XXX is the change hash
To get the diff between two versions, go to https://github.com/antwebes/ChateaClientBundle/compare/v1.0.0...v1.0.1

After of year, this bungle is stable.
We create verson 1.0.0 because now user can registered with country and whitout city.
And use chateaClientLib version 1.0.0

* 1.0.0 (2016-04-26)

 * Country is required in the register form
 * When editing user, country is setted
 * Fixed init country select selection on edit city & Fixed cities autocomplete
 * Changed label of edit city to edit your localization
 * Retain country when errors happen in register
 
* 1.0.1 (2016-04-??)
 * Include extension to check if profile of user is empty, with parameters by default to check fields of profile array('gender', 'youWant', 'about', 'seeking')