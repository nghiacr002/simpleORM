<?php
namespace DbTest\Client;
use SimpleORM\Db\Relation as Base;

class Relation extends Base
{
	protected function _createReference($aParams)
	{
		$oRef = new Reference($aParams);
		return $oRef;
	}
}