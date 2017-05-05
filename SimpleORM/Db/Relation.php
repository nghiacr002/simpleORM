<?php
namespace SimpleORM\Db;

class Relation
{
	protected $_aMappers = array();
	const ONE_TO_MANY = "OneToMany";
	const ONE_TO_ONE = "OneToOne";
	const MANY_TO_MANY = "ManyToMany";
	const MANY_TO_ONE = "ManyToOne";
	public function __construct()
	{
		$this->init();
	}
	protected function init()
	{
		return $this;
	}
	/**
	 *
	 * @param unknown $sName
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
	 * @param unknown $sName
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
	 * @param unknown $sName
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
	 * @param unknown $sName
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
	public function get($sName)
	{
		if(isset($this->{$sName}))
		{
			return $this->{$sName};
		}
		if(isset($this->_aMappers[$sName]))
		{
			$aParams = $this->_aMappers[$sName];
			//$this->{$sName} = new \stdClass();
			return $this->{$sName};

		}
		return null;
	}
	protected function set($sName, $aParams = array())
	{
		$this->_aMappers[$sName] = $aParams;
		return $this;
	}
}