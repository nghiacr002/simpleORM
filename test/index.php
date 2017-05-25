<?php
//use DbTest\Client\Model as ClientModel;
use SimpleORM\Helper\Connector;
use SimpleORM\Db\Model;
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
$db = new Connector($configs);
//create the database configuration mapper file
/*
	$sSavePath = dirname(__FILE__) .DIRECTORY_SEPARATOR;
	SimpleORM\Helper\Tool::generateConfigFile($db,$sSavePath);
	exit;
*/

$sConfigDBFile = dirname(__FILE__) .DIRECTORY_SEPARATOR . 'dbconfig.php';
if(file_exists($sConfigDBFile))
{
	require_once $sConfigDBFile;
	$db->setTableConfigs($DB_TABLES);
}
$oModel = new Model("client");
//$oModel->getTable()->setPrimaryKey("client_id");
$mData = $oModel->createQuery()->where('client_id',1,'>')->select('*')->getOne();
die();
// custom table
/*$oModel = new ClientModel();
$oClientRow = $oModel->getOne(array('client_id',1,'>'));
$oClientRow->getRelation()->hasOne('info',array(
	'source' => 'client_id',
	'target' => 'client_id',
	'table' => 'client_info'
));
$oClientRow->getRelation()->hasMany('apps',array(
	'source' => 'client_id',
	'target' => 'client_id',
	'table' => 'client_app'
));
$oClientRow->getRelation()->belongsTo('client_type',array(
	'source' => 'level',
	'target' => 'id',
	'table' => 'client_type',
));
$oClientRow->getRelation()->hasManyToMany('groups',array(
	'source' => 'client_id',
	'target' => 'id',
	'table' => 'group',
	'option' => array(
		'bridge' => array(
			'table' => 'client_group',
			'source' => array(
				'client_id' => 'client_id'
			),
			'target' => array(
				'id' => 'group_id',
			)
		)
	)
));*/




function d($mInfo, $bVarDump = false)
{
	$bCliOrAjax = (PHP_SAPI == 'cli');
	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
	($bVarDump ? var_dump($mInfo) : print_r($mInfo));
	(!$bCliOrAjax ? print '</pre>' : false);
}