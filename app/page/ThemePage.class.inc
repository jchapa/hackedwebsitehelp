<?php

require_once "app/page/ThemePage.class.php";


abstract class ThemePage extends Page
{
    /**
     * @var Theme
     */
    protected $m_oTheme;

    protected $m_bThemeNewOrChanged;

    function __construct()
    {
        parent::__construct();

        $aCookies = $this->m_oCookieJar->GetAllCookies();

        // Theming
        $iThemeId = isset($aCookies["THEME"]) ? $aCookies["THEME"] : null;
        // Will either load up the theme for this user, or set a new one
        $this->m_oTheme = new Theme($this->m_oDataContext, $iThemeId);

        if (null == $iThemeId)
        {
            $this->m_bThemeNewOrChanged = true;
            // Set a new random theme for this user
        }

        // If the theme was changed, we need to set a cookie

        // If user has no theme, or theme has been disabled, give him a new one

        // If user does not exist, create a new one and give him a theme
    }

}