<?php
namespace SimpleORM\Helper;
use SimpleORM;

class Connector
{
	private $_oAdapter = null;
	public function __construct($aConfigs = array())
	{
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
					throw new \Exception("Not found adapter");
				}
		}
		$oAdapter->connect($aConfigs);
		$this->_oAdapter = $oAdapter;
	}
	public function getAdapter()
	{
		return $this->_oAdapter;
	}
}