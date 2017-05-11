<?php
namespace DbTest\Client;
use SimpleORM\Db\Table as Base;

class Table extends Base
{
	protected $_sClassRow = "DbTest\Client\Row";
	protected $_mPrimaryKey = "client_id";
	protected function config()
	{
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
		$this->_oRelation = $oRelation;
	}
}