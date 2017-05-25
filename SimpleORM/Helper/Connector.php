<?php
namespace SimpleORM\Helper;
use SimpleORM;

class Connector
{
	private $_oAdapter = null;
	private $_aConfigs = array();
	private static $instance;
	private $_aTableConfigs = array();
	public function __construct($aConfigs = array())
	{
		self::$instance = $this;
		$this->_aConfigs = $aConfigs;
		$sAdapterType = isset($aConfigs['adapter']) ? strtolower($aConfigs['adapter']) : "mysqli";
		$oAdapter = null;
		switch ($sAdapterType)
		{
			case 'mockup':
				$oAdapter = new SimpleORM\Adapter\DbMockup();
				break;
			case 'pdo':
				$oAdapter = new SimpleORM\Adapter\PDO();
				break;
			case 'mysqli':
				$oAdapter = new SimpleORM\Adapter\Mysqli();
				break;
			default:
				$sClassName = "\\SimpleORM\\Adapter\\". $sAdapterType;
				if(class_exists($sClassName))
				{
					$oAdapter = new $sClassName();
				}
				else
				{
					throw new SimpleORMException("Not found adapter");
				}
		}
		$oAdapter->connect($aConfigs);
		$this->_oAdapter = $oAdapter;
	}
	public function setTableConfigs($aParams = array())
	{
		$this->_aTableConfigs = $aParams;
		return $this;
	}
	public function getTableConfig($sTableName)
	{
		return isset($this->_aTableConfigs[$sTableName]) ? $this->_aTableConfigs[$sTableName] : array();
	}
	public function getConfigs()
	{
		return $this->_aConfigs;
	}
	public static function getTableName($sName)
	{
		$sPrefix = self::$instance->_aConfigs['prefix'];
		if(!empty($sPrefix))
		{
			if(strpos($sName,$sPrefix ) !== 0)
			{
				$sName = $sPrefix. $sName;
			}
		}
		return $sName;
	}
	public function getAdapter()
	{
		return $this->_oAdapter;
	}
	public static function getInstance()
	{
		return self::$instance;
	}
}