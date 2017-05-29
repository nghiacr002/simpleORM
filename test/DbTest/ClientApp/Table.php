<?php 
namespace DbTest\ClientApp;
use SimpleORM\Db\Table as Base;
class Table extends Base
{
	protected $_sClassRow = "SimpleORM\Db\Row";
	protected $_mPrimaryKey = 'app_id';
	protected function config()
	{
		parent::config();
		//your custom code here
	}
}
