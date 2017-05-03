<?php

namespace SimpleORM\Db;

class Table
{
    protected $_sTableName;
    protected $_mPrimaryKey;
    protected $_oQuery;
    protected $_aValidateRules;
    protected $_sRowClass;
    protected $_sAlias;
	protected $_oAdapter;
	protected $_oRelation;

    public function __construct($sTableName = "", $mPrimaryKey = null)
    {
        if (!empty($sTableName))
        {
            $this->_sTableName = $sTableName;
        }
        if ($mPrimaryKey)
        {
            $this->_mPrimaryKey = $mPrimaryKey;
        }
        $this->_oRelation = new Relation();
        $this->config();
    }
    protected function config()
    {
		return true;
    }
	public function getRelation()
	{
		return $this->_oRelation;
	}
    public function getRowClass()
    {
        return $this->_sRowClass;
    }

    public function businessValidate(\SimpleORM\Db\Row $mData)
    {
        return true;
    }

    public function getValidateRules()
    {
        return $this->_aValidateRules;
    }

    public function getPrimaryKey()
    {
        return $this->_mPrimaryKey;
    }

    public function setPrimaryKey($mKey)
    {
        $this->_mPrimaryKey = $mKey;
        return $this;
    }
    public function createRow($data = array())
    {
        $mRow = new \SimpleORM\Db\Row($this);
        $mRow->setData($data);
        return $mRow;
    }

    public function getColumns()
    {
        $adapter = $this->getAdapter();
        $results = $adapter->execute("SHOW COLUMNS FROM " . $this->_sTableName);

        $columns = array();
        foreach ($results as $result)
        {
            $field = $result['Field'];
            unset($result['Field']);
            $columns[$field] = $result;
        }
        return $columns;
    }
    public function setAdapter($oAdapter)
    {
    	$this->_oAdapter = $oAdapter;
    	return $this;
    }
	public function getAdapter()
	{
		return $this->_oAdapter;
	}
    public function getTableName()
    {
        return $this->_sTableName;
    }

    public function getAlias()
    {
        if (!$this->_sAlias)
        {
            $this->_sAlias = $this->_sTableName;
        }
        return $this->_sAlias;
    }

    public function setAlias($sAlias)
    {
        $this->_sAlias = $sAlias;
        return $this;
    }
}
