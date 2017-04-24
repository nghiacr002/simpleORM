<?php
namespace SimpleORM\Db;
class Relation
{
	protected $_aMappers = array();
	public function __construct()
	{
		$this->init();
	}
	protected function init()
	{
		return $this;
	}
	public function addOneToMany($sSourceColumn, $sTargetColumn,$sTargetTable, $aOptions = array())
	{
		return $this->set("hasOneToMany",$sColumn,$sTargetColumn,$sTargetTable,$aOptions = array());
	}
	public function addOneToOne($sSourceColumn, $sTargetColumn,$sTargetTable,$aOptions = array())
	{
		return $this->set("hasOneToOne",$sColumn,$sTargetColumn,$sTargetTable,$aOptions = array());
	}
	public function addManyToMany($sSourceColumn, $sTargetColumn,$sTargetTable,$aOptions = array())
	{
		return $this->set("hasManyToMany",$sColumn,$sTargetColumn,$sTargetTable);
	}
	public function addManyToOne($sSourceColumn, $sTargetColumn,$sTargetTable,$aOptions = array())
	{
		return $this->set("hasManyToOne",$sColumn,$sTargetColumn,$sTargetTable,$aOptions = array());
	}
	protected function set($sType, $sSourceColumn, $sTargetColumn, $sTargetTable, $aOptions = array())
	{
		$this->_aMappers[$sTargetColumn] = array();
		return $this;
	}

}