<?php
namespace DbTest\Client;
use SimpleORM\Db\Reference as Base;

class Reference extends Base
{
	public function getId()
	{
		return "Ref Base";
	}
}