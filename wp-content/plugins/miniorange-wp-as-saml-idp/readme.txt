=== SAML & WSFED IDP ( SSO using WordPress Users ) ===
Contributors: cyberlord92
Donate link: https://miniorange.com/
Tags: Login Using WordPress, IDP, WordPress SSO, Single Sign-On, WSFED, WordPress IDP
Requires at least: 3.5
Tested up to: 5.4
Stable tag: 1.12.3
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Login using Wordpress users to any application. SAML SSO or WSFED SSO into Tableau, Zoho, Moodle LMS, etc using WP users. [ACTIVE SUPPORT]

== Description ==
SAML & WSFED IDP ( SSO using WordPress Users ) provides SAML functionality for WordPress.

This SAML WordPress SSO solution provides SAML SSO capability to your WordPress site, converting it to a SAML compliant Identity Provider which can be configured with any SAML compliant Service Provider.

Login With WordPress allows users residing in your WordPress site to login to your SAML 2.0 or WS-FED compliant Service Provider. We support all known Service Providers that support SAML Authentication , WSFED authentication and JWT authentication.

SAML & WSFED IDP ( SSO using WordPress Users ) allows SSO login with Tableau, Zoho CRM, Moodle LMS, miniOrange, Thinkific, Canvas LMS, Absorb LMS, iPipeline, Mendix, NextCloud, Zendesk, WordPress and all SAML 2.0 capable Service Providers.

WordPress as IdP SAML / WS-FED /JWT SSO Plugin acts as a SAML 2.0 or WS-FED or JWT Identity Provider which can be configured to establish the trust between the plugin and various SAML 2.0 or WS-FED or JWT supported Service Providers to securely authenticate the user using the WordPress site. 

= List of Supported Service Providers =
*	**Tableau** (Login to Tableau)
*	**Zoho CRM** (Login to Zoho)
*	**Moodle LMS** (Login to Moodle)
*	**miniOrange** (Login to miniOrange)
*	**Thinkific** (Login to Thinkific)
*	**Canvas LMS** (Login to Canvas)
*	**Absorb LMS** (Login to Absorb) - _Requires <a href="https://plugins.miniorange.com/wordpress-saml-idp" target="_blank">Premium version</a>_
*	**iPipeline** (Login to iPipeline)
*	**Mendix** (Login to Mendix)
*	**NextCloud** (Login to NextCloud) - _Requires <a href="https://plugins.miniorange.com/wordpress-saml-idp" target="_blank">Premium version</a>_
*	**Zendesk** (Login to Zendesk)
*	**WordPress** (Login to WordPress)
and practically any SAML compliant Service Provider.

_**Note** : Some Service Providers require additional attributes to configure SSO. This is a Premium feature. Feel free to ask us for a trial version of the Premium plugin to test the SSO._

To know more about the plugin, please visit <a href="https://plugins.miniorange.com/wordpress-saml-idp" target="_blank">this</a> page.

If you are looking to SSO into your WordPress site with any SAML compliant Identity Provider then we have a separate plugin for that. <a href="https://wordpress.org/plugins/miniorange-saml-20-single-sign-on/" target="_blank"> Click Here </a> to learn more.

If you require any Single Sign On application or need any help with installing this plugin, please feel free to email us at info@xecurify.com. You can also submit your query from plugin's configuration page.

**Tableau views inside your WordPress site** - Now your users can see Tableau views inside your WordPress site without the user ever leaving your site! miniOrange Wordpress IDP Single Sign on plugin can make that happen and make it look seamless!

This is one such use case out of hundreds of use cases that are supported by our plugin!

= Features :- =

*	Login to any SAML 2.0 or WS-FED or JWT compliant Service Provider like Tableau, Zoho CRM, Moodle LMS, miniOrange, Thinkific, Canvas LMS, Absorb LMS, iPipeline, Mendix, NextCloud, Zendesk, etc. using your WordPress site.
*   Easily configure the Identity Provider by providing just the Issuer, ACS URL and NameID format.
*	Make user login more secure by signing and encrypting response to Service Provider.
*	Easily integrate the login link from your WordPress site using shortcode for IdP initiated SSO. Just drop it in a desirable place in your site.
*	Use the Attribute Mapping feature to map WordPress user profile attributes to your SP attributes.
*	Use the Role Mapping feature to send roles assigned to users from your WordPress site to your Service Provider.
*	Supports multiple Service Providers from the same WordPress instance.

= Website - =
Check out our website for other plugins <a href="http://miniorange.com/plugins" >http://miniorange.com/plugins</a> or <a href="https://wordpress.org/plugins/search.php?q=miniorange" >click here</a> to see all our listed WordPress plugins.
For more support or info email us at info@xecurify.com. You can also submit your query from plugin's configuration page.

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `WordPress SAML IDP`. Find and Install `WordPress SAML 2.0 IDP`.
3. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download WordPress SAML 2.0 IDP plugin.
2. Unzip and upload the `miniorange-wp-saml-idp` directory to your `/wp-content/plugins/` directory.
3. Activate WordPress as IdP SAML Single Sign-On from your Plugins page.

== Frequently Asked Questions ==

= I am not able to configure the Identity Provider with the settings provided by Service Provider =
Please email us at info@xecurify.com. You can also submit your app request from plugin's configuration page.

= For any query/problem/request =
Visit Help & FAQ section in the plugin OR email us at info@xecurify.com or <a href="http://miniorange.com/contact">Contact us</a>. You can also submit your query from plugin's configuration page.

== Screenshots ==
1. SAML Settings page
2. WSFED Settings Page
3. JWT Settings Page
4. Attribute and Role mapping

== Changelog ==

= 1.12.3 =
* Bug fixes

= 1.12.2 =
* New License Plans

= 1.12.1 =
* Updated certificates and metadata
* Updated Guide links
* Improved UI

= 1.11.1 = 
* Support Contact changes
* Certificate Updates

= 1.11.0 =
* Added Visual Tour
* Bug Fixes
* UI Refresh

= 1.10.9 =
* Security Fixes

= 1.10.8 =
* Bug Fixes - User Session Check during IDP initiated flow
* Added support for JWT token authentication
* Security Fix

= 1.10.7 =
* Compatibility with version WordPress 4.9
* Bug Fixes

= 1.10.6 =
* Metadata XML file fixes
* Fix for showing all distinct user attributes

= 1.10.5 =
* Documentation Link Fixes

= 1.10.4 =
* Support Query Form Fix

= 1.10.3 =
* Constant File Changes

= 1.10.2 =
* Added Support for Ws-Fed

= 1.10.1 =
* API fixes

= 1.6 =
* Changes in detecting Service Provider

= 1.5 =
* Bug Fixes

= 1.4 =
* Added documentation for new users.

= 1.3.5 =
*	Bug Fix while reading AuthnRequest

= 1.3.3 =
*	Added Custom Static Attributes
*	Bug Fixes

= 1.3.2 =
*	Bug Fixes

= 1.3.1 =
*	Tested Compatibility with WordPress 4.5.1
*	Updated Policies

= 1.3 =
*	Added Single Logout
*	Licensing Changes

= 1.2 =
*	Changes & fixes for WordPress 4.5
*	Bug fix for reading SAML Assertion

= 1.1 =
*	Bug Fixes

= 1.0.5 =
*	Major Security Fix

= 1.0.4 =
*	Bug Fixes

= 1.0.3 =
*	Change login URL of IDP
*	Fix for fetching SP Configuration

= 1.0.2 =
*	UI Changes
*	Addition of Encryption in Premium options
*	Table Update

= 1.0.1 =
*	Added Premium Options
*	Bug fixes

= 1.0.0 =
*	this is the first release.

== Upgrade Notice ==
= 1.12.3 =
* Bug fixes

= 1.12.2 =
* New License Plans

= 1.12.1 =
* CRITICAL UPDATE - Please update your certificates.
* Guide links updated.
* UI improvements.

= 1.11.0 =
* Added Visual Tour
* Bug Fixes
* UI Refresh

= 1.10.9 =
* Security Fixes

= 1.10.8 =
* Bug Fixes - User Session Check during IDP initiated flow
* Added support for JWT token authentication
* Security Fix

= 1.10.7 =
* Compatibility with version WordPress 4.9
* Bug Fixes

= 1.10.6 =
* Metadata XML file fixes
* Fix for showing all distinct user attributes

= 1.10.5 =
* Documentation Link Fixes

= 1.10.4 =
* Support Query Form Fix

= 1.10.3 =
* Constant File Changes

= 1.10.2 =
* Added Support for Ws-Fed

= 1.10.1 =
* API fixes

= 1.6 =
* Changes in detecting Service Provider

= 1.5 =
* Bug Fixes

= 1.4 =
* Added documentation/guide for new users.

= 1.3.5 =
*	Bug Fix while reading AuthnRequest

= 1.3.3 =
*	Added Custom Static Attributes
*	Bug Fixes

= 1.3.2 =
*	Bug Fixes

= 1.3.1 =
*	Tested Compatibility with WordPress 4.5.1
*	Updated Policies

= 1.3 =
* Added Single Logout
* Licensing Changes

= 1.2 =
*	Changes & fixes for WordPress 4.5
*	Bug fix for reading SAML Assertion

= 1.1 =
*	Bug Fixes

= 1.0.5 =
*	Major Security Fix

= 1.0.4 =
*	Bug Fixes

= 1.0.3 =
*	Change login URL of IDP
*	Fix for fetching SP Configuration

= 1.0.2 =
*	UI Changes
*	Addition of Encryption in Premium options
*	Table Update

= 1.0.1 =
*	Added Premium Options
*	Bug fix

= 1.0.0 =
*	this is the first release.