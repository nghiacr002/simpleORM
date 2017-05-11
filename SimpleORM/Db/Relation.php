<?php
namespace SimpleORM\Db;

use SimpleORM\Helper\Exception;

class Relation
{
	protected $_aMappers = array();
	const ONE_TO_MANY = "OneToMany";
	const ONE_TO_ONE = "OneToOne";
	const MANY_TO_MANY = "ManyToMany";
	const MANY_TO_ONE = "ManyToOne";
	protected $_oSource;
	public function __construct(\SimpleORM\Db\Table $oSource = null)
	{
		$this->_oSource = $oSource;
		$this->init();
	}
	protected function init()
	{
		return $this;
	}
	public function getSource()
	{
		return $this->_oSource;
	}
	/**
	 *
	 * @param unknown $sName: Name of reference
	 * @param array $aParams. Example:
	 * array(
	 * 	'source' => '',
	 *  'target' => '',
	 *  'table' => '',
	 *  'options' => array(),
	 * )
	 * @return \SimpleORM\Db\Relation
	 */
	public function hasMany($sName,$aParams = array())
	{
		$aParams['type'] = Relation::ONE_TO_MANY;
		return $this->set($sName,$aParams);
	}
	/**
	 *
	 * @param unknown $sName: Name of reference
	 * @param array $aParams. Example:
	 * array(
	 * 	'source' => '',
	 *  'target' => '',
	 *  'table' => '',
	 *  'options' => array(),
	 * )
	 * @return \SimpleORM\Db\Relation
	 */
	public function hasOne($sName,$aParams = array())
	{
		$aParams['type'] = Relation::ONE_TO_ONE;
		return $this->set($sName,$aParams);
	}
	/**
	 *
	 * @param unknown $sName: Name of reference
	 * @param array $aParams. Example:
	 * array(
	 * 	'source' => '',
	 *  'target' => '',
	 *  'table' => '',
	 *  'options' => array(),
	 * )
	 * @return \SimpleORM\Db\Relation
	 */
	public function hasManyToMany($sName,$aParams = array())
	{
		$aParams['type'] = Relation::MANY_TO_MANY;
		return $this->set($sName,$aParams);
	}
	/**
	 *
	 * @param string $sName: Name of reference
	 * @param array $aParams. Example:
	 * array(
	 * 	'source' => '',
	 *  'target' => '',
	 *  'table' => '',
	 *  'options' => array(),
	 * )
	 * @return \SimpleORM\Db\Relation
	 */
	public function belongsTo($sName,$aParams = array())
	{
		$aParams['type'] = Relation::MANY_TO_ONE;
		return $this->set($sName,$aParams);
	}
	public function getInfo()
	{
		return $this->_aMappers;
	}
	/**
	 * Return the Reference class of this relation
	 * @param unknown $sName
	 * @param array $aRefData
	 */
	public function getRef($sName, $aRefData = array())
	{
		if(isset($this->{$sName}))
		{
			return $this->{$sName};
		}
		if(isset($this->_aMappers[$sName]))
		{
			$aParams = $this->_aMappers[$sName];
			$aParams['ref_data'] = $aRefData;
			$this->{$sName} = $this->_get($sName,$aParams);
			return $this->{$sName};
		}
	}
	/**
	 * Return the reference object(s) list value
	 * @param unknown $sName
	 * @param array $aRefData
	 * @return NULL
	 */
	public function get($sName, $aRefData = array())
	{
		$oRef = $this->getRef($sName,$aRefData);
		if($oRef)
		{
			return $oRef->getData();
		}
		return null;
	}
	protected function _get($sName, $aParams = array())
	{
		$sTable = isset($aParams['table']) ? $aParams['table']: "";
		if(empty($sTable))
		{
			throw new Exception("Related Table Destination could not be empty");
		}
		if(class_exists($sTable))
		{
			$oTable =  new $sTable($aParams);
		}
		else
		{
			$oTable =  Model::createTable($sTable,"",$aParams);
		}
		$oRef = $this->_createReference($aParams);
		$oRef->setSource($this->getSource());
		$oRef->setDestination($oTable);
		return $oRef;
	}

	protected function _createReference($aParams)
	{
		$oRef = new Reference($aParams);
		return $oRef;
	}
	protected function set($sName, $aParams = array())
	{
		$this->_aMappers[$sName] = $aParams;
		return $this;
	}
}