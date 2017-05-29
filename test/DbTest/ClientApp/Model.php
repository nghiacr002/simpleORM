<?php 
namespace DbTest\ClientApp;
use SimpleORM\Db\Model as Base;
class Model extends Base
{
	protected $_sTableName = "client_app";
	protected $_sClassTable = "DbTest\ClientApp\Table";
}
