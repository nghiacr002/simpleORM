<?php
namespace DbTest\Client;
use SimpleORM\Db\Table as Base;

class Table extends Base
{
	protected $_sClassRow = "DbTest\Client\Row";
	protected $_mPrimaryKey = "client_id";
	protected function config()
	{
		$this->_oRelation = new Relation($this);
	}
}