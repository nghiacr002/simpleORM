<?php

namespace SimpleORM\Db;

use SimpleORM\Helper\Connector;
use SimpleORM;
use SimpleORM\Helper\Tool;

class Table
{
    protected $_sTableName;
    protected $_sClassRow = "\SimpleORM\Db\Row";
    protected $_sClassModel = "\SimpleORM\Db\Model";
    protected $_mPrimaryKey;
    protected $_sAlias;
	protected $_oModel;
	protected $_oAdapter;
	protected $_oRelation;
	protected $_aOptions = array();

    public function __construct($aOptions = array())
    {
        $this->_oRelation = new Relation($this);
		$this->_aOptions = $aOptions;
        $this->config();
    }
    public function setOptions($aOptions = array(), $bReconfigure = true)
    {
    	$this->_aOptions = $aOptions;
    	if($bReconfigure)
    	{
    		$this->config();
    	}
    	return $this;
    }
    public function getModel()
    {
    	$sClassModel = $this->_sClassModel;
    	$sName = "model_".md5($this->_sClassModel);
    	if($this->_oModel)
    	{
    		return $this->_oModel;
    	}
    	if(!empty($sClassModel) && class_exists($sClassModel))
    	{
			$oModel =  new $sClassModel();
    	}
    	if($oModel instanceof SimpleORM\Db\Model)
    	{
    		$oModel->setTable($this);
    		$this->_oModel = $oModel;
    		return $this->_oModel;
    	}
    	throw new \Exception("Model of table ". $this->getTableName() ." not found");
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
        return Tool::getTableColumns($this->_sTableName);
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
		if(!$this->_oAdapter)
		{
			$this->setAdapter(Connector::getInstance()->getAdapter());
		}
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
    protected function config()
    {
    	$aOptions = $this->_aOptions;
    	if(isset($aOptions['primary_keys']))
    	{
    		if(is_array($aOptions['primary_keys']) && count($aOptions['primary_keys']) ==  1)
    		{
    			$aOptions['primary_keys'] = $aOptions['primary_keys'][0];
    		}
    		$this->_mPrimaryKey = $aOptions['primary_keys'];
    	}
    	if(isset($aOptions['alias']))
    	{
    		$this->_sAlias = $aOptions['alias'];
    	}
    	if(isset($aOptions['relations']) && count($aOptions['relations']))
    	{

			foreach($aOptions['relations'] as $sKey => $aRelationConfig)
			{
				switch ($aRelationConfig['type'])
				{
					case Relation::ONE_TO_MANY:
						$this->_oRelation->hasMany($sKey,$aRelationConfig);
						break;
					case Relation::ONE_TO_ONE:
						$this->_oRelation->hasOne($sKey,$aRelationConfig);
						break;
					case Relation::MANY_TO_ONE:
						$this->_oRelation->belongsTo($sKey,$aRelationConfig);
						break;
					case Relation::MANY_TO_MANY:
						$this->_oRelation->hasManyToMany($sKey,$aRelationConfig);
						break;
				}
			}
    	}
    	return true;
    }
}
