<?php
use DbTest\Client\Model as ClientModel;
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
	'type' => 'mysql');
$db = new Connector($configs);
/*
$oModel = new Model("client");
$oModel->getTable()->setPrimaryKey("client_id");
$mData = $oModel->createQuery()->where('client_id',1,'>')->select('*')->getAll();
if(count($mData))
{
	foreach($mData as $mRow)
	{
		$mRow->joined_time = time();
		$mRow->update();
	}
}
 */

$oModel = new ClientModel();
$oClientRow = $oModel->getOne(array('client_id',1,'>'));
$oClientRow->getRelation()->hasMany('apps',array(
	'source' => 'client_id',
	'target' => 'client_id',
	'table' => 'client_app'
));
var_dump($oClientRow->apps);
var_dump($oClientRow->apps);die();
/*$mData = $oModel->createQuery()
		->select('*')
		->where('client_id',1,'>')
		->getOne();
*/
var_dump($oClientRow->getRelation());
die();