<?php
namespace SimpleORM\Db;
use SimpleORM\Db\Relation;
use SimpleORM\Helper\Exception;
use SimpleORM\Helper\Connector;

class Reference
{
	protected $_sHashID;
	protected $_oDestination;
	protected $_oSource;
	protected $_aParams = array();
	public function __construct($aParams = array())
	{
		$this->_aParams = $aParams;
	}
	public function setSource(\SimpleORM\Db\Table $oTable)
	{
		$this->_oSource = $oTable;
		return $this;
	}
	public function setDestination(\SimpleORM\Db\Table $oTable)
	{
		$this->_oDestination = $oTable;
		return $this;
	}
	public function getSource()
	{
		return $this->_oSource;
	}
	public function getDestination()
	{
		return $this->_oDestination;
	}
	public function getHashId()
	{
		if(!$this->_sHashID)
		{
			$this->_sHashID = $this->_oSource->getTableName() . "_" .$this->_oDestination->getTableName();
		}
		return $this->_sHashID;
	}
	public function getData()
	{
		$sType = isset($this->_aParams['type']) ? $this->_aParams['type'] : "";
		switch ($sType)
		{
			case Relation::ONE_TO_ONE:
				return $this->_getOneToOne();
			case Relation::ONE_TO_MANY:
				return $this->_getOneToMany();
			case Relation::MANY_TO_ONE:
				return $this->_getManyToOne();
			case Relation::MANY_TO_MANY:
				return $this->_getManyToMany();
		}
	}
	protected function _getManyToMany()
	{
		$oModelDestination = $this->getDestination()->getModel();
		//$oModelSource = $this->getSource()->getModel();
		$aOption = isset($this->_aParams['option']) ? $this->_aParams['option'] : array();

		if(!isset($aOption['bridge']) || !isset($aOption['bridge']['table']))
		{
			throw new Exception("No bridge table found");
		}
		if(!isset($aOption['bridge']['target']) || !isset($aOption['bridge']['source']))
		{
			throw new Exception("No bridge condition found");
		}

		$mRefData = isset($this->_aParams['ref_data']) ? $this->_aParams['ref_data'] : array();
		$oQuery = $oModelDestination->createQuery("SELECT");
		$sAlias = $aOption['bridge']['table'];
		$sDesAlias = $this->getDestination()->getAlias();
		$sCondition = $sAlias. '.'.$aOption['bridge']['target'][$this->_aParams['target']]. ' = '
					.  $sDesAlias . '.'. $this->_aParams['target'];
		$oQuery->join(Connector::getTableName($aOption['bridge']['table']), $sCondition, $sAlias);
		$oQuery->select($sDesAlias.'.*');
		$oQuery->where($this->_aParams['source'], isset($mRefData[$this->_aParams['source']]) ? $mRefData[$this->_aParams['source']] : null);
		$mResult = $oQuery->getAll();
		return $mResult;
	}
	protected function _getManyToOne()
	{
		$oModel = $this->getDestination()->getModel();
		if($oModel)
		{
			$aParams = $this->_aParams;
			$aConds = $this->buildCondRefs();
			return $oModel->getOne($aConds);
		}
	}
	protected function _getOneToMany()
	{
		$oModel = $this->getDestination()->getModel();
		if($oModel)
		{
			$aParams = $this->_aParams;
			$aConds = $this->buildCondRefs();
			return $oModel->getAll($aConds);
		}
		//$oModel->
		return array();
	}
	protected function _getOneToOne()
	{
		$oModel = $this->getDestination()->getModel();
		if($oModel)
		{
			$aParams = $this->_aParams;
			$aConds = $this->buildCondRefs();
			return $oModel->getOne($aConds);
		}
		return null;
	}
	protected function buildCondRefs()
	{
		$aParams = $this->_aParams;

		$aConds = array();
		$mSource = isset($this->_aParams['source']) ? $this->_aParams['source'] : array();
		$mTarget = isset($aParams['target']) ? $aParams['target'] : array();
		$mRefData = isset($aParams['ref_data']) ? $aParams['ref_data'] : array();
		if(!is_array($mTarget))
		{
			$mTarget = array($mTarget);
		}
		if(!is_array($mSource))
		{
			$mSource = array($mSource);
		}
		if(count($mTarget))
		{

			foreach($mTarget as $iIndex => $sKey)
			{
				$sKeySource = isset($mSource[$iIndex]) ? $mSource[$iIndex] : null;
				if($sKeySource)
				{
					$mValue = isset($mRefData[$sKeySource]) ? $mRefData[$sKeySource] : null;
					$aConds[] = array(
							$sKey, $mValue
					);
				}

			}
		}
		return $aConds;
	}
	public function __call($sName, $aParams = array())
	{
		$oDestinationTable = $this->getDestination();
		if($oDestinationTable && method_exists($oDestinationTable, $sName))
		{
			return call_user_func_array(array($oDestinationTable, $sName), $aParams);
		}
		return null;
	}
}