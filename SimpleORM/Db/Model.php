<?php
namespace SimpleORM\Db;

use SimpleORM\Helper\Connector;
use DbTest\Client\Table;

class Model
{
	protected $_oTable = null;
	protected $_sClassTable = "";
	protected $_sTableName = "";
	public function __construct()
	{
		$this->init();
	}
	public function getOne($aConds, $sSelect = "*")
	{
		$oQuery = $this->createQuery("SELECT");
		$oQuery->select($sSelect);
		if(isset($aConds[0]) && !is_array($aConds[0]))
		{
			$aConds = array($aConds);
		}
		foreach($aConds as $aCond)
		{
			$params = isset($aCond[0]) ? $aCond[0] : array();
			$values = isset($aCond[1]) ? $aCond[1] : array();
			$operator = isset($aCond[2]) ? $aCond[2] : "=";
			$cond = isset($aCond[3]) ? $aCond[3] : "AND";
			$oQuery->where($params,$values,$operator, $cond);
		}
		return $oQuery->getOne();
	}
	public function setTable($mTable, $sClassName = "")
	{
		if(is_string($mTable))
		{
			$this->_oTable = self::createTable($mTable, $sClassName);
		}
		else if($mTable instanceof Table)
		{
			$this->_oTable = $mTable;
		}
	}
	public static function createTable($sTableName, $sClassName = "")
	{
		$oTable = null;
		if(!empty($sClassName) && !class_exists($sClassName))
		{
			$oTable = new $sClassName();
		}
		else
		{
			$sTableName = Connector::getTableName($sTableName);
			$oTable = new Table();
			$oTable->setTableName($sTableName);
		}
		return $oTable;
	}
	public function getTable()
	{
		if(!$this->_oTable)
		{
			$this->_oTable = self::createTable($this->_sTableName, $this->_sClassTable);
		}
		return $this->_oTable;
	}
	protected function init()
	{
		if(!empty($this->_sTableName))
		{
			$this->_oTable = self::createTable($this->_sTableName);
		}
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
		$oTable = $this->getTable();
		$oQuery->from($oTable->getTableName(), $oTable->getAlias());
		$oQuery->setAdapter($oTable->getAdapter());
		$oQuery->setTable($oTable);
		return $oQuery;
	}
}