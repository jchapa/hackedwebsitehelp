<?php

require_once "app/data/SQLiteDataBoundEntity.class.php";

class Theme extends SQLiteDataBoundEntity
{
    protected $m_aColumns = array(
        "name" => 'strName',
        "platform" => "strPlatform",
        "dir" => "strDir"
    );
    protected $m_strTableName = "THEME";
    protected $m_strEntityIdColumn = "theme_id";
    protected $m_strCreateTable  =<<<EOF
    CREATE TABLE {table_prefix}THEME
    (
        theme_id INTEGER PRIMARY KEY NOT NULL,
        name TEXT UNIQUE NOT NULL,
        platform TEXT
        dir TEXT
    );
EOF;

    protected $m_aAllThemes = array();

    /** PROPERTIES **/
    public $strName;
    public $strPlatform;
    public $strDir;

    public function __construct($oDataObject, $iThemeId = null)
    {
        parent::__construct($oDataObject);
        if (null !== $iThemeId)
        {
            if (!parent::LoadRecord($iThemeId))
            {
                throw new Exception("Unable to load Theme");
            }
        }
    }

    // TODO: Make this more dynamic
    protected function LoadThemes()
    {
        $oRecords = parent::SelectAll();
        foreach ($oRecords as $oThemeRecord)
        {
            $oTheme = new Theme($this->m_oDataObject);
            $oTheme->PopulateObjectFromData($oThemeRecord);
            $this->m_aAllThemes[] = $oTheme;
        }
    }

    /**
     * @param $strName
     * @param $strPlatform
     * @param $strDir
     *
     * @throws Exception
     */
    public function CreateTheme($strName, $strPlatform, $strDir)
    {
        $this->strName = $strName;
        $this->strPlatform = $strPlatform;
        $this->strDir = THEME_ROOT_DIR . "/" . $strDir;

        if ($this->InsertRecord())
        {
            $this->m_iEntityId = $this->m_oDataObject->lastInsertRowID();
        }
        else
        {
            throw new Exception("Unable to save Theme");
        }
    }
}