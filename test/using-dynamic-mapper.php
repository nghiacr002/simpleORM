<?php
use SimpleORM\Helper\Connector;
require_once 'DbTest/autoload.php';
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
$sConfigDBFile = dirname(__FILE__) .DIRECTORY_SEPARATOR . 'dbconfig.php';
if(file_exists($sConfigDBFile))
{
	require_once $sConfigDBFile;
	$db->setTableConfigs($DB_TABLES);
}
$oModel = new Model("client");
$mData = $oModel->createQuery()->where('client_id',1,'>')->select('*')->getAll();
d($mData);
exit;


function d($mInfo, $bVarDump = false)
{
	$bCliOrAjax = (PHP_SAPI == 'cli');
	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
	($bVarDump ? var_dump($mInfo) : print_r($mInfo));
	(!$bCliOrAjax ? print '</pre>' : false);
}