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
        user_id INT PRIMARY KEY NOT NULL,
        identifier TEXT UNIQUE NOT NULL
    );
EOF;

    public $strIdentifier;

    public function __construct($oDataObject, $iUserId = null)
    {
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
            $this->strIdentifier = $this->GenerateUserId();
            if (!$this->InsertRecord())
            {
                throw new Exception("Unable to save new user!");
            }
            else
            {
                // Now save this user's cookie
                $oUserCookie = new Cookie("USER", $this->strIdentifier);
            }
        }

        parent::__construct($oDataObject);
    }

    private function GenerateUserId()
    {
        return uniqid();
    }
}