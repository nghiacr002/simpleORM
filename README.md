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
⋅⋅⋅ Look the database sql file in folder "test/d2_test2.sql". 

### New Model
```
$oModel = new Model("client");
$oModel->getTable()->setPrimaryKey("client_id"); // should set primary key for table
```
### Create Query

`$query = $oModel->createQuery();`

### How to get all "clients" with filter
```
$mData = $oModel->createQuery()->where('client_id',1,'>')->select('*')->getAll();
```
### Setup relationship tables
```
$oRelation = new Relation($this);
		// 1-1
		$oRelation->hasOne('info',array(
			'source' => 'client_id', // from table column
			'target' => 'client_id', // to target table column
			'table' => 'client_info' // target table
		));
		// 1-n
		$oRelation->hasMany('apps',array(
			'source' => 'client_id',
			'target' => 'client_id',
			'table' => 'client_app'
		));
		// n-1
		$oRelation->belongsTo('client_type',array(
			'source' => 'level',
			'target' => 'id',
			'table' => 'client_type',
		));
		// n- n with bride(junction) table client_group
		$oRelation->hasManyToMany('groups',array(
				'source' => 'client_id', // from table column
				'target' => 'id', // to target table column
				'table' => 'group', // target table
				'option' => array(
						'bridge' => array(
								'table' => 'client_group', // junction table
								'source' => array(
									'client_id' => 'client_id' // mapping junction table & source table
								),
								'target' => array(
										'id' => 'group_id',// mapping junction table & target table
								)
						)
				)
		));
```
### Advanced usage
- It's allowed to extend all of class such as: Model, Table, Reference and Relation, Even the Row Data. 
- Look more at the folder "test/DbTest/Client". 
- You can extend and write any advanced functions. 



