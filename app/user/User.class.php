<?php

require_once "app/data/SQLiteDataBoundEntity.class.php";

class User extends SQLiteDataBoundEntity
{
    protected $m_aColumns = array(
        "identifier" => 'strIdentifier'
    );
    protected $m_strTableName = "USER";
    protected $m_strEntityIdColumn = "user_id";
    protected $m_strCreateTable  =<<<EOF
    CREATE TABLE {table_prefix}USER
    (
        user_id INTEGER PRIMARY KEY NOT NULL,
        identifier TEXT UNIQUE NOT NULL
    );
EOF;

    public $m_oUserCookie;
    public $m_bUserIsNew;

    //** PRPOPERTIES */
    public $strIdentifier;

    public function __construct($oDataObject, $iUserId = null)
    {
        parent::__construct($oDataObject);
        if (null !== $iUserId)
        {
            if (!parent::LoadRecord($iUserId, "identifier"))
            {
                throw new Exception("Unable to load user");
            }
        }
        else
        {
            // Make a new user!
            $this->m_bUserIsNew = true;
            $this->strIdentifier = $this->GenerateUserId();
            if (!$this->InsertRecord())
            {
                throw new Exception("Unable to save new user!");
            }
            else
            {
                $this->m_oUserCookie = new Cookie("USER", $this->strIdentifier, false, true);
            }
        }
    }

    private function GenerateUserId()
    {
        return uniqid();
    }
}