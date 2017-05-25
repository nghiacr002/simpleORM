<?php

$DB_TABLES = array (
		'tbl_client' => array (
				'columns' => array (
						0 => 'client_id',
						1 => 'client_name',
						2 => 'level'
				),
				'primary_keys' => array (
						0 => 'client_id'
				),
				'relations' => array (
						'level_type_fk' => array (
								'source' => 'level',
								'target' => 'id',
								'table' => 'tbl_client_type',
								'type' => 'ManyToOne'
						),
						'client_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client_app',
								'type' => 'OneToMany'
						),
						'client_group_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client_group',
								'type' => 'OneToMany'
						),
						'client_infor_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client_info',
								'type' => 'OneToMany'
						)
				),
				'options' => array ()
		),
		'tbl_client_app' => array (
				'columns' => array (
						0 => 'app_id',
						1 => 'client_id',
						2 => 'app_name'
				),
				'primary_keys' => array (
						0 => 'app_id'
				),
				'relations' => array (
						'client_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client',
								'type' => 'ManyToOne'
						)
				),
				'options' => array ()
		),
		'tbl_client_group' => array (
				'columns' => array (
						0 => 'auto_id',
						1 => 'client_id',
						2 => 'group_id'
				),
				'primary_keys' => array (
						0 => 'auto_id'
				),
				'relations' => array (
						'client_group_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client',
								'type' => 'ManyToOne'
						),
						'group_id_fk' => array (
								'source' => 'group_id',
								'target' => 'id',
								'table' => 'tbl_group',
								'type' => 'ManyToOne'
						)
				),
				'options' => array ()
		),
		'tbl_client_info' => array (
				'columns' => array (
						0 => 'info_id',
						1 => 'client_id',
						2 => 'passcode',
						3 => 'visa'
				),
				'primary_keys' => array (
						0 => 'info_id'
				),
				'relations' => array (
						'client_infor_id_fk' => array (
								'source' => 'client_id',
								'target' => 'client_id',
								'table' => 'tbl_client',
								'type' => 'ManyToOne'
						)
				),
				'options' => array ()
		),
		'tbl_client_type' => array (
				'columns' => array (
						0 => 'id',
						1 => 'level_name',
						2 => 'description'
				),
				'primary_keys' => array (
						0 => 'id'
				),
				'relations' => array (
						'level_type_fk' => array (
								'source' => 'id',
								'target' => 'level',
								'table' => 'tbl_client',
								'type' => 'OneToMany'
						)
				),
				'options' => array ()
		),
		'tbl_group' => array (
				'columns' => array (
						0 => 'id',
						1 => 'name',
						2 => 'description'
				),
				'primary_keys' => array (
						0 => 'id'
				),
				'relations' => array (
						'group_id_fk' => array (
								'source' => 'id',
								'target' => 'group_id',
								'table' => 'tbl_client_group',
								'type' => 'OneToMany'
						)
				),
				'options' => array ()
		)
);