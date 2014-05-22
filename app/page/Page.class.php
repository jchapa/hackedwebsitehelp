<?php

// Load up our deps
// INCLUDE CONFIG
require_once "config/core.config.php";
require_once "config/data.config.php";

// Setup entities
require_once "app/cookie/CookieJar.class.php";
require_once "app/user/User.class.php";

abstract class Page
{
    /**
     * @var CookieJar
     */
    protected $m_oCookieJar;

    /**
     * @var User
     */
    protected $m_oUser;

    /**
     * SQLite3
     */
    protected $m_oDataContext;

    function __construct()
    {
        // Setup DB context
        $this->m_oDataContext = new SQLite3(DATA_SQLITE3_DB_FILENAME);

        // Cookie processing
        $this->m_oCookieJar = new CookieJar();
        $aCookies = $this->m_oCookieJar->GetAllCookies();

        // Get our user
        $mUserId = null;
        if (isset($aCookies["USER"]))
        {
            $mUserId = $aCookies["USER"]->getCookieData();
        }
        $this->m_oUser = new User($this->m_oDataContext, $mUserId);
        $oUserCookie = new Cookie("USER", $this->m_oUser->strIdentifier, false, true);

        // Add to jar if needed
        if ($this->m_oUser->m_bUserIsNew)
        {
            $this->m_oCookieJar->AddCookie($oUserCookie);
        }
    }

    function __destruct()
    {
        // Store all our data
        $this->m_oCookieJar->storeCookies();
    }

    abstract function main();
}