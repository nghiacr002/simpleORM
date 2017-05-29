<?php
use SimpleORM\Helper\Connector;
use SimpleORM\Helper\Tool;
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
$sSaveModelPath = dirname(__FILE__) .DIRECTORY_SEPARATOR . 'DbTest' . DIRECTORY_SEPARATOR;
$sTableName = get_param('table');
if(get_param('path'))
{
	$sSaveModelPath = get_param('path');
}
if(is_writable($sSaveModelPath) && is_dir($sSaveModelPath))
{
	if(!empty($sTableName))
	{
		Tool::generateModelTable($sTableName,$sSaveModelPath,false,"DbTest\\");
	}
}
else
{
	echo "is not writable folder";
}

function get_param($sName)
{
	if(isset($_GET[$sName]) && !empty($_GET[$sName]))
	{
		return $_GET[$sName];
	}
	return null;
}
function d($mInfo, $bVarDump = false)
{
	$bCliOrAjax = (PHP_SAPI == 'cli');
	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
	($bVarDump ? var_dump($mInfo) : print_r($mInfo));
	(!$bCliOrAjax ? print '</pre>' : false);
}