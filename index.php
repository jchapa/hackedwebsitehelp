<?php

/****************************************************************************************
 * Simple application with one goal - get users into our pipeline (CTA or contact us directly)
 * - A/B testing
 *   - Two options - random theme or chosen one (e.g. for ad campaigns)
 *   - Track user, ensure always consistent theme via cookie. Fallback if no cookies allowed
 * - Store data on users in DB (as validation to GA)
 * - Store conversion data in DB (map to user)
 * - No payment
 * - No cart
 ***************************************************************************************/

// INCLUDE CONFIG
require_once "config/core.config.php";

// Setup entities
require_once "app/User/CookieJar.class.php";
require_once "app/user/User.class.php";


// Setup Cookie Jar
$oCookieJar = new CookieJar();

// See if user exists, load if he does


// If user exists, see if he has a theme


// If user has no theme, or theme has been disabled, give him a new one


// If user does not exist, create a new one and give him a theme

