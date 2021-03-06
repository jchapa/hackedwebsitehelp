<?php
require_once "app/encryption/EncryptionLibrary.class.php";

class Cookie
{
    protected $m_strCookieType;
    protected $m_mCookieData;
    protected $m_bIsDirty = false;
    protected $m_bShouldDestroy = false;
    protected $m_bIsNew;

    public function __construct($strCookieType, $mCookieData, $bShouldDestroy = false, $bIsNew = false)
    {
        $this->m_strCookieType = $strCookieType;
        $this->m_mCookieData = $mCookieData;
        $this->m_bIsNew = $bIsNew;
        $this->m_bShouldDestroy = $bShouldDestroy;
    }

    /**
     * Builds a cookie's name, using the COOKIE_PREFIX
     * @param $strCookie
     *
     * @return string
     */

    public function getCookieName($bWithPrefix = false)
    {
        $strRetval = "";

        if ($bWithPrefix)
        {
            $strRetval .= COOKIE_PREFIX;
        }
        $strRetval .= $this->m_strCookieType;

        return $strRetval;
    }

    public function destroyCookie()
    {
        $this->m_bIsDirty = true;
        $this->m_bShouldDestroy = true;
    }

    /**
     * Actually save the cookie, prepending the prefix
     */
    public function storeCookie()
    {
        // Only do work if the cookie is dirty
        if ($this->m_bIsDirty || $this->m_bIsNew)
        {
            $strCookieName = $this->getCookieName($this->m_strCookieType, true);
            $strCookieData = $this->m_mCookieData;
            $strCookieExp = time() + (86400 * 365);
            $strCookiePath = "/";
            $strCookieDomain = ".hackedwebsitehelp.com";

            if ($this->m_bShouldDestroy)
            {
                $strCookieData = null;
                $strCookieExp = -1;
            }
            else
            {
                //$oEncrypt = new EncryptionLibrary();

                //$strCookieData = $oEncrypt->encrypt($strCookieData);
            }

            setcookie($strCookieName,
                $strCookieData,
                $strCookieExp,
                $strCookiePath,
                $strCookieDomain
            );

            $this->m_bIsDirty = false;
        }
    }

    /**
     * Set the Cookie data
     * @param $mData
     */
    public function setCookieData($mData)
    {
        if ($this->m_mCookieData !== $mData)
        {
            $this->m_bIsDirty = true;
            $this->m_mCookieData = $mData;
        }
    }

    public function getCookieData()
    {
        return $this->m_mCookieData;
    }

    /**
     * Returns the cookie name without our prefix
     * @param $strCookie
     */
    public static function stripCookiePrefix($strCookie)
    {
        $strRetval = "";
        $iPrefixLength = strlen(COOKIE_PREFIX);
        if (COOKIE_PREFIX == substr($strCookie, 0,$iPrefixLength))
        {
            // This cookie has our prefix
            $strRetval = substr($strCookie, $iPrefixLength);
        }
        // Else this cookie doesn't have our current prefix

        return $strRetval;
    }
}