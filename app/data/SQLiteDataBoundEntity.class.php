<?php

abstract class SQLiteDataBoundEntity
{
    /** THESE MUST BE OVERIDDEN **/

    /**
     * Array of "column" => "local variable"
     * @var array
     */
    protected $m_aColumns = array();
    protected $m_strTableName;
    protected $m_strEntityIdColumn;
    protected $m_strCreateTable;

    /** INTERNAL VARS **/
    protected $m_bDirty = false;
    protected $m_iEntityId;

    /**
     * @var SQLite3
     */
    protected $m_oDataObject;

    public function __construct(SQLite3 $oDataObject)
    {
        $this->m_oDataObject = $oDataObject;
        // Create the table if needed
        if (!$this->CreateTable())
        {
            throw new Exception("Could not create table!");
        }
    }

    protected function CreateTable()
    {
        $bRetval = true;
        // Check if table exists
        if (!$this->DoesTableExist())
        {
            // Create it if not
            $strSQL = $this->m_strCreateTable;
            $strSQL = str_replace("{table_prefix}", DATA_SQLITE3_DB_TABLE_PREFIX, $strSQL);
            $strSQL = SQLite3::escapeString($strSQL);

            $bRetval = $this->m_oDataObject->exec($strSQL);
        }
        return $bRetval;
    }

    /**
     * Loads up the object
     * @param $iEntityId
     *
     * @return DataBoundEntity
     */
    protected function LoadRecord($iEntityId, $strColumn = null)
    {
        $bRetval = true;

        if ($strColumn == null)
        {
            $strColumn = $this->m_strEntityIdColumn;
        }

        $strSQL = $this->GetSelectSQL();

        $aReplacements = array(
            "{entity_id}" => $iEntityId,
            "{table_name}" => $this->m_strTableName,
            "{where_column}" => $strColumn,
        );

        $strSQL = str_replace(array_keys($aReplacements), array_values($aReplacements), $strSQL);
        $strSQL = $this->m_oDataObject->escapeString($strSQL);

        // TODO: Change to querySingle
        $oData = $this->m_oDataObject->query($strSQL);

        // We only want the first one back (if there's more we're stupid

        if (false == $oData || 0 >= $oData->numColumns())
        {
            $bRetval = false;
        }
        else
        {
            $bFirstRow = true;
            while ($oRow = $oData->fetchArray())
            {
                if ($bFirstRow)
                {
                    $bFirstRow = false;

                    $this->m_iEntityId = $oRow[$this->m_strEntityIdColumn];

                    foreach($this->m_aColumns as $strColumn => $strVar)
                    {
                        if (isset($oRow[$strColumn]))
                        {
                            $this->${$strVar} = $oRow[$strColumn];
                        }
                        unset($strColumn, $strVar);
                    }
                }
                else
                {
                    break;
                }
            }
        }

        return $bRetval;
    }

    /**
     * Returns a collection of this object
     * @param $aRestrictions
     *
     * @return array(DataBoundEntity)
     */
    protected function SelectDataSet($aRestrictions)
    {
        throw new BadMethodCallException("Not yet implemented");
    }

    /**
     * Inserts the current record
     * @return mixed
     */
    protected function InsertRecord()
    {
        $strSQL = $this->GetInsertSQL();

        $bRetval = $this->m_oDataObject->exec($strSQL);
        return $bRetval;
    }

    /**
     * Updates the current record
     * @return mixed
     */
    protected function UpdateRecord()
    {

    }

    private function DoesTableExist()
    {
        $strSQL = "SELECT name FROM sqlite_master WHERE type='table' AND name='{table_name}';";
        $strSQL = str_replace("{table_name}", DATA_SQLITE3_DB_TABLE_PREFIX . $this->m_strTableName, $strSQL);
        $strSQL = SQLite3::escapeString($strSQL);

        $bRetval = null !== $this->m_oDataObject->querySingle($strSQL);
        return $bRetval;
    }

    protected function GetSelectSQL()
    {
        $strRetval = "SELECT * FROM {table_name} WHERE {where_column} = {key_id}";
        return $strRetval;
    }

    protected function GetInsertSQL()
    {
        $strRetval = "INSERT INTO {table_name} (";

        foreach ($this->m_aColumns as $strColumn => $strVar)
        {
            $strRetval .= "`$strColumn`,";
        }
        $strRetval = rtrim($strRetval, ",");

        $strRetval .= ") VALUES (";

        foreach ($this->m_aColumns as $strColumn => $strVar)
        {
            $strRetval .= "'$this->${strVar}'" . ",";
        }

        $strRetval = rtrim($strRetval, ",");

        $strRetval .= ");";

        $strRetval = SQLite3::escapeString($strRetval);

        return $strRetval;
    }

    /**
     * Deletes the current record
     * @return mixed
     */
    protected function DeleteRecord()
    {
        throw new BadMethodCallException("Not authorized");
    }

}