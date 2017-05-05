<?php

namespace SimpleORM\Db;

use SimpleORM\Helper\Connector;

class Table
{
    protected $_sTableName;
    protected $_sClassRow = "\SimpleORM\Db\Row";
    protected $_mPrimaryKey;
    protected $_sAlias;

	protected $_oAdapter;
	protected $_oRelation;

    public function __construct()
    {
        $this->_oAdapter = Connector::getInstance()->getAdapter();
        $this->_oRelation = new Relation();

        $this->config();
    }
    protected function config()
    {
		return true;
    }
    public function setPrimaryKey($mKey)
    {
        $this->_mPrimaryKey = $mKey;
        return $this;
    }
    public function setTableName($sName)
    {
    	$sName = Connector::getTableName($sName);
    	$this->_sTableName = $sName;
    	return $this;
    }
    public function setAlias($sAlias)
    {
    	$this->_sAlias = $sAlias;
    	return $this;
    }
    public function createRow($mData = array())
    {
    	$oRow = null;
    	if(!empty($this->_sClassRow) && class_exists($this->_sClassRow))
    	{
    		$oRow = new $this->_sClassRow();
    	}
    	else
    	{
    		$oRow = new Row();
    	}
    	if($oRow)
    	{
    		$oRow->setTable($this);
    		$oRow->setFieldValues($mData);
    	}
        return $oRow;
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
    public function getRelation()
    {
    	return $this->_oRelation;
    }
    public function getPrimaryKey()
    {
    	return $this->_mPrimaryKey;
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


}
