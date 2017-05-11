# simpleORM
PHP ORM Database in simplest way. 
## Installation: 
Include autoload file of SimpleORM to your project
`require_once '../SimpleORM/autoload.php';`
## Config Database Connector
```
$configs = array( 
    'host' => 'localhost', // host name
    'name' => 'd2_test2', //db name
    'user' => 'root', //user db
    'pwd' => '123456',//password db
    'port' => 3306, // port connector
    'prefix' => 'tbl_', // prefix for tables
    'adapter' => 'mysqli', // adapter, supported MySQLi and PDO
    'charset' => 'utf8', // charset of connector
	  'type' => 'mysql' // type of connection in case using PDO
 );
$db = new Connector($configs);
```
## Usage
Update later


