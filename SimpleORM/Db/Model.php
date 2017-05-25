<?php
namespace SimpleORM\Db;

use SimpleORM\Helper\Connector;
use SimpleORM\Db\Table;

class Model
{
	protected $_oTable = null;
	protected $_sClassTable = "";
	protected $_sTableName = "";
	protected $_sHashID = "";
	public function __construct($sTableName = "")
	{
		if(!empty($sTableName))
		{
			$this->_sTableName = $sTableName;
		}
		$this->init();
	}
	public function getHashId()
	{
		if(!$this->_sHashID)
		{
			$this->_sHashID = "Model_". $this->_sTableName;
		}
		return $this->_sHashID;
	}
	public function getOne($aConds, $sSelect = "*")
	{
		$oQuery = $this->createQuery("SELECT");
		$oQuery->select($sSelect);
		$this->buildConds($oQuery,$aConds);
		return $oQuery->getOne();
	}
	public function getAll($aConds, $sSelect = "*", $iPage = null, $iLimit = null, $mOrder = null)
	{
		$oQuery = $this->createQuery("SELECT");
		$oQuery->select($sSelect);
		$oQuery->limit($iPage, $iLimit);
		if(is_array($mOrder) && count($mOrder) == 2)
		{
			$oQuery->order($mOrder[0],$mOrder[1]);
		}
		return $oQuery->getAll();
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
	public static function createTable($sTableName, $sClassName = "", $aOptions = array())
	{
		$oTable = null;
		$sTableName = Connector::getTableName($sTableName);
		if(!count($aOptions))
		{
			$aOptions = Connector::getInstance()->getTableConfig($sTableName);
		}
		if(!empty($sClassName) && class_exists($sClassName))
		{
			$oTable = new $sClassName($aOptions);
		}
		else
		{
			$oTable = new Table($aOptions);
		}
		$oTable->setTableName($sTableName);
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
	protected function buildConds($oQuery, $aConds = array())
	{
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
		return $oQuery;
	}
	protected function init()
	{
		if(!empty($this->_sTableName))
		{
			$this->_oTable = self::createTable($this->_sTableName, $this->_sClassTable);
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