<?php
namespace DbTest\Client;
use SimpleORM\Db\Model as Base;

class Model extends Base
{
	protected $_sTableName = "client";
	protected $_sClassTable = "DbTest\\Client\\Table";
}