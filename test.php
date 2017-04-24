<?php
use SimpleORM\Helper\Connector;

require_once 'SimpleORM/autoload.php';
@error_reporting(E_ALL);
@ini_set('display_errors', -1);
$configs = array( 'host' => 'localhost',
    'name' => 'd2_test',
    'user' => 'root',
    'pwd' => '123456',
    'port' => 3306,
    'prefix' => 'tbl_',
    'adapter' => 'mysqli',
    'charset' => 'utf8',
	'type' => 'mysql');
$db = new Connector($configs);

var_dump($db);die();