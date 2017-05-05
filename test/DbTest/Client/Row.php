<?php
namespace DbTest\Client;
use SimpleORM\Db\Row as Base;

class Row extends Base
{
	public function getClientId(){
		return $this->client_id;
	}
}