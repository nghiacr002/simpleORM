<?php
use SimpleORM\Helper\Connector;
require_once '../SimpleORM/autoload.php';
@error_reporting(E_ALL);
@ini_set('display_errors', -1);
$configs = array( 'host' => 'localhost',
		'name' => 'd2_test2',
		'user' => 'root',
		'pwd' => '123456',
		'port' => 3306,
		'prefix' => 'tbl_',
		'adapter' => 'mysqli',
		'charset' => 'utf8',
		'type' => 'mysql'
);
//file saved config name is "dbconfig.php"
$db = new Connector($configs);
$sSavePath = dirname(__FILE__) .DIRECTORY_SEPARATOR;
$sFilePath = SimpleORM\Helper\Tool::generateConfigFile($db,$sSavePath);
echo $sFilePath;
exit;