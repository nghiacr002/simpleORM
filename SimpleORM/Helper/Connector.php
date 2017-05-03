<?php
namespace SimpleORM\Helper;
use SimpleORM;

class Connector
{
	private $_oAdapter = null;
	private $_aConfigs = array();
	private static $instance;
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
	public static function getTableName($sName)
	{
		return self::$instance->_aConfigs['prefix'] . $sName;
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