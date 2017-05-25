<?php
namespace SimpleORM\Helper;
use SimpleORM\Db\Relation;

class Tool
{
	/**
	 * Generate Table Classes
	 * @param string $sTable
	 * @param array $aOptions. Available options
	 * array(
	 * 	'save_path' => '',
	 *  'folder' => '',
	 * )
	 */
	public static function generateTable($sTable, $aOptions = array())
	{
		$aRelatedTables = array();
		$aReturn = array(
			'columns' => array(),
			'primary_keys' => isset($aOptions['primary_keys']) ? $aOptions['primary_keys'] : array(),
			'relations' => array(),
			'options' => array()
		);
		if(isset($aOptions['columns']) && count($aOptions['columns']))
		{
			foreach($aOptions['columns'] as $sKey => $aColumn)
			{
				$aReturn['columns'][] = $sKey;
			}
		}
		if(isset($aOptions['relations']) && count($aOptions['relations']))
		{
			foreach($aOptions['relations'] as $aRelationInformation)
			{
				$aInfo = array(
					'source' => $aRelationInformation['COLUMN_NAME'],
					'target' => $aRelationInformation['REFERENCED_COLUMN_NAME'],
					'table' => $aRelationInformation['REFERENCED_TABLE_NAME'],
					'type' => Relation::MANY_TO_ONE
				);
				$aReturn['relations'][$aRelationInformation['CONSTRAINT_NAME']] = $aInfo;
				$aInfo2 = array(
					'source' => $aRelationInformation['REFERENCED_COLUMN_NAME'],
					'target' => $aRelationInformation['COLUMN_NAME'],
					'table' => $sTable,
					'type' => Relation::ONE_TO_MANY
				);
				$aRelatedTables[$aRelationInformation['REFERENCED_TABLE_NAME']][$aRelationInformation['CONSTRAINT_NAME']] = $aInfo2;
			}
		}
		return array($aReturn,$aRelatedTables);
	}
	/**
	 * Generate XML Mappers for all classes in database
	 * @param Connector $connector
	 */
	public static function generateConfigFile(Connector $connector , $sSavePath = null)
	{
		if(!is_writable($sSavePath))
		{
			die("\"".$sSavePath ."\" is not writable folder");
		}
		$aConfigs = $connector->getConfigs();
		//fetch all database tables
		$oAdapter = $connector->getAdapter();
		$sDatabaseName = $aConfigs['name'];
		$aResults = $oAdapter->execute("SHOW TABLES;");
		$aTables = array();
		if(count($aResults))
		{
			foreach($aResults as $iKey => $aTable)
			{
				$sTable = isset($aTable["Tables_in_".$sDatabaseName]) ? $aTable["Tables_in_".$sDatabaseName] : "";
				if(!empty($sTable))
				{
					$aColumns = self::getTableColumns($sTable);
					$aPrimaryKeys = array();
					foreach($aColumns as $iKey => $aColumn)
					{
						if($aColumn['Key'] == "PRI")
						{
							$aPrimaryKeys[] = $iKey;
						}
					}
					$aTables[$sTable] = array(
							'columns' => $aColumns,
							'relations' => array(),
							'primary_keys' => $aPrimaryKeys,
					);
				}

			}
			//get all schema foreign keys

			$sSQL = "SELECT *
			FROM `INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE`
			WHERE `TABLE_SCHEMA` = '".$sDatabaseName."'
 			AND `REFERENCED_TABLE_NAME` IS NOT NULL;";
			$aRelations = $oAdapter->execute($sSQL);
			if(count($aRelations))
			{
				foreach($aRelations as $iKey => $aKey)
				{
					$sTable = $aKey['TABLE_NAME'];
					if(isset($aTables[$sTable]))
					{
						$aTables[$sTable]['relations'][] = $aKey;
					}
				}
			}
		}
		//generate to file
		if(count($aTables))
		{
			$aTableConfigs = array();
			$aRelatedTables = array();
			foreach($aTables as $sTableName => $aParams)
			{
				list($aTableConfig,$aRelatedTable) = self::generateTable($sTableName,$aParams);
				$aTableConfigs[$sTableName] = $aTableConfig;
				if(count($aRelatedTable))
				{
					$aRelatedTables[] = $aRelatedTable;
				}
			}
			if(count($aRelatedTables))
			{
				foreach($aRelatedTables as $iKey => $aRelatedTable)
				{
					foreach($aRelatedTable as $sTableKeyName => $aTmpTable)
					{
						foreach($aTmpTable as $sContraintKey => $aInfo)
						{
							$aTableConfigs[$sTableKeyName]['relations'][$sContraintKey]  = $aInfo;
						}
					}
				}
			}
			$sFileName = $sSavePath . "dbconfig.php";
			@file_put_contents($sFileName, "<?php \$DB_TABLES = " . var_export($aTableConfigs,true). ";");
			@chmod($sFileName, 0777);
		}
		echo "Done";exit;
	}
	public static function getTableColumns($sTable, Connector $connector = null)
	{
		if($connector == null)
		{
			$connector = Connector::getInstance();
		}
		$results = $connector->getAdapter()->execute("SHOW COLUMNS FROM " . $sTable);

		$columns = array();
		foreach ($results as $result)
		{
			$field = $result['Field'];
			unset($result['Field']);
			$columns[$field] = $result;
		}
		return $columns;
	}
}