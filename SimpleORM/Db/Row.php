<?php
namespace SimpleORM\Db;
use SimpleORM\Helper\Query as Query;
use SimpleORM\Helper\Object;
class Row extends Object
{
    protected $_oTable;
    protected $_oValidator;
    protected $_aErrors;
    protected $_aData;
    public function __construct()
    {
		$this->init();
    }
    protected function init()
    {
    	return true;
    }
    public function getRelation()
    {
    	if(!$this->_oTable)
    	{
    		return null;
    	}
    	return $this->_oTable->getRelation();
    }
    public function setTable(\SimpleORM\Db\Table $oTable)
    {
    	$this->_oTable = $oTable;
    	return $this;
    }
    public function getTable()
    {
        return $this->_oTable;
    }

    public function mapFieldValues($aData)
    {
        foreach ($aData as $iKey => $mData)
        {
            if (isset($this->_aData[$iKey]))
            {
                $this->_aData[$iKey] = $mData;
            }
        }
        return $this;
    }
	public function getValues()
	{
		return $this->toArray();
	}
    public function setFieldValues($aData, $bIsMerge = false)
    {
    	if(!$bIsMerge)
    	{
    		$this->_aData = $aData;
    	}
    	else if (is_array($aData))
        {
            foreach ($aData as $key => $value)
            {
                $this->_aData[$key] = $value;
            }
        }
        return $this;
    }
    public function removeField($sKey)
    {
        if (isset($this->_aData[$sKey]))
        {
            unset($this->_aData[$sKey]);
        }
        return $this;
    }

    public function getErrors()
    {
        return $this->_aErrors;
    }

    public function setError($sError)
    {
        $this->_aErrors[] = $sError;
        return $this;
    }
    public function save()
    {
    	$this->beforeSave();
        $query = new Query();
        $query->setCommand("insert");
        $query->setTableData($this->_oTable->getTableName(), $this->_aData);
        $query->execute();
        return $mResult;
    }
    public function update()
    {
    	$this->beforeUpdate();
        $query = new Query();
        $query->setCommand("update");
        $query->setTableData($this->_oTable->getTableName(), $this->_aData);
        $this->_buildWherePrimary($query);
        $bResult = $query->execute();
        return $bResult;
    }
    public function delete()
    {
    	$this->beforeDelete();
        $query = new Query();
        $query->setCommand("delete");
        $this->_buildWherePrimary($query);
        $query->from($this->_oTable->getTableName());
        $bResult = $query->execute();
        return $bResult;
    }
    public function beforeSave()
    {
		return true;
    }
    public function beforeUpdate()
    {
    	return true;
    }
    public function beforeDelete()
    {
    	return true;
    }
    protected function catchError()
    {
        if ($this->_oTable->getAdapter()->hasError())
        {
            foreach ($this->_oTable->getAdapter()->getErrors() as $iKey => $aError)
            {
                $this->setError($aError);
            }
        }
        return $this;
    }

    protected function _buildWherePrimary($query)
    {
        $mPrimaryKey = $this->_oTable->getPrimaryKey();
        if (!is_array($mPrimaryKey))
        {
            $mPrimaryKey = array($mPrimaryKey);
        }
        foreach ($mPrimaryKey as $sPrimaryKey)
        {
            $query->where($sPrimaryKey, $this->{$sPrimaryKey});
        }
        return $query;
    }

    public function toArray()
    {
        return $this->_aData;
    }

    public function getURL()
    {
        return "#";
    }
    public function __get($sName)
    {
    	$oRelation = $this->getRelation();
    	if($oRelation)
    	{
    		$oObject = $oRelation->get($sName, $this->getValues());
			if($oObject)
			{
				return $oObject;
			}
    	}
    	return parent::__get($sName);
    }
}
