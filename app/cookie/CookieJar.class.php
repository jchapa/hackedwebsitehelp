<?php
require_once "config/cookie.config.php";
require_once "app/cookie/Cookie.class.php";
require_once "app/cookie/CookieTypes.enum.php";

class CookieJar
{
    /**
     * The cookies in the jar
     * @var array
     */
    protected $m_aCookies = array();

    public function __construct()
    {
        // We're only allowed to modify our own cookies
        $aAllCookies = $_COOKIE;
        // Decryption Lib
        //$oDecrypt = new EncryptionLibrary();

        $aCookies = array_filter($_COOKIE, function($strData) use (&$aAllCookies) {
                $bRetval = false;
                if (false !== array_search(Cookie::stripCookiePrefix(key($aAllCookies)), CookieTypes::getValidCookies()))
                {
                    $bRetval = true;
                }
                next($aAllCookies);
                return $bRetval;
            });

        // Let's go through any existing cookies
        foreach ($aCookies as $strCookie => $mData)
        {
            //$oCookie = new Cookie(Cookie::stripCookiePrefix($strCookie), $oDecrypt->decrypt($mData));
            $oCookie = new Cookie(Cookie::stripCookiePrefix($strCookie), $mData);
            $this->m_aCookies[Cookie::stripCookiePrefix($strCookie)] = $oCookie;

            unset ($strCookie, $mData, $oCookie);
        }
    }

    public function getCookieData($strCookie)
    {
        $strRetval = false;
        if (isset($this->m_aCookies[$strCookie]))
        {
            $strRetval = $this->m_aCookies[$strCookie]->getCookieData();
        }

        return $strRetval;
    }

    public function AddCookie(Cookie $oCookie)
    {
        $this->m_aCookies[$oCookie->getCookieName()] = $oCookie;
    }

    /**
     * Stores all cookies in the jar.
     * Should only call this once
     */
    public function storeCookies()
    {
        foreach ($this->m_aCookies as $oCookie)
        {
            $oCookie->storeCookie();
        }
    }

// TODO: MAke this suck less (e.g. GetCookieInJar($strCookie)
    public function GetAllCookies()
    {
        return $this->m_aCookies;
    }
}