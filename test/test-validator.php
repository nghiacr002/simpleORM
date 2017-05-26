<?php
use SimpleORM\Helper\Connector;
use SimpleORM\Db\Model;
use SimpleORM\Helper\Validator;
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
//create new Row;
$oNewRow = $oModel->getTable()->createRow();

$oNewRow->setValidateRules(array(
	'client_name' => array(
		'required' => true,
		'type' => Validator::TYPE_STRING
	)
));
if(!$oNewRow->isValid())
{
	var_dump($oNewRow->getErrors());
}
else
{
	die('no error');
}
exit;


function d($mInfo, $bVarDump = false)
{
	$bCliOrAjax = (PHP_SAPI == 'cli');
	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
	($bVarDump ? var_dump($mInfo) : print_r($mInfo));
	(!$bCliOrAjax ? print '</pre>' : false);
}