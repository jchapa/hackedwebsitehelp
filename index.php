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
require_once "app/page/ThemePage.class.php";

class IndexPage extends ThemePage
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function main()
    {

    }
}

$oPage = new IndexPage();
$oPage->main();