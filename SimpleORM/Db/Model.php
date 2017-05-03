<?php
namespace SimpleORM\Db;

use SimpleORM\Helper\Connector;

class Model
{
	protected $_oCurrentQuery = null;
	protected $_oTable = null;
	public function __construct($mTable)
	{
		$this->setTable($mTable);
		$this->init();
	}
	public function setTable($mTable)
	{
		if(is_string($mTable))
		{
			$this->_oTable = $this->createTable($mTable);
		}
		else if($mTable instanceof Table)
		{
			$this->_oTable = $mTable;
		}
	}
	public function createTable($mTable)
	{
		$sTableName = Connector::getTableName($mTable);
		$oTable = new Table($sTableName);
		$oTable->setAdapter(Connector::getInstance()->getAdapter());
		return $oTable;
	}
	public function getTable()
	{
		return $this->_oTable;
	}
	protected function init()
	{
		return true;
	}
	public function executeQuery(\SimpleORM\Helper\Query $query)
	{
		list($sSql, $aBindParams) = $query->build();
		return $this->_oTable->getAdapter()->execute($sSql, $aBindParams);
	}

	public function createQuery($sType = "SELECT")
	{
		$oQuery = new \SimpleORM\Helper\Query($sType);
		$oQuery->from($this->_oTable->getTableName(), $this->_oTable->getAlias());
		$oQuery->setAdapter($this->_oTable->getAdapter());
		if($sType == "SELECT")
		{
			$oQuery->select("*");
		}
		$this->_oCurrentQuery = $oQuery;
		return $oQuery;
	}
	public function __call($sName, $arguments = array())
	{
		if($this->_oCurrentQuery)
		{
			if (method_exists($this->_oCurrentQuery, $sName))
			{
				return call_user_func_array(array($this, $sName), $arguments);
			}

		}
		return null;
	}

}