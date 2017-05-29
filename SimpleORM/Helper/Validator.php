<?php
namespace SimpleORM\Helper;
class Validator
{
	const TYPE_STRING = "string";
	const TYPE_NUMBER = "number";
	const TYPE_ARRAY = "array";
	const TYPE_DATE = "date";
	const TYPE_OBJECT = "object";
	private $_aErrors = array();

	public function check($sValue, $sType = "", $aRules = array())
	{
		$this->_aErrors = array();
		$sType = ucfirst($sType);
		$sFunction = "check".$sType;
		$bReturn = true;
		if(method_exists($this, $sFunction))
		{
			$bReturn =  $this->{$sFunction}($sValue,$aRules);
		}

		if($bReturn && isset($aRules['required']) && $aRules['required'] == true)
		{
			if($sValue === null || $sValue == "")
			{
				$this->setError("is not existed");
				$bReturn = false;
			}
		}
		if($bReturn && isset($aRules['regex']) && !empty($aRules['regex']))
		{
			if(!preg_match($aRules['regex'], $sValue))
			{
				$this->setError("is not passed regex rule");
				$bReturn = false;
			}
		}
		if($bReturn && isset($aRules['function']) && is_callable($aRules['function']))
		{
			$bReturn = call_user_func_array($aRules['function'], $aRules);
		}
		return $bReturn;
	}
	public function setError($sMessage)
	{
		$this->_aErrors[] = $sMessage;
		return $this;
	}
	public function getErrors()
	{
		return $this->_aErrors;
	}
	protected function checkString($sValue = null, $aRules = array())
	{
		if(!is_string($sValue))
		{
			$this->setError("is not string");
			return false;
		}
		if(isset($aRules['empty']) && $aRules['empty'] == false)
		{
			if(empty($sValue))
			{
				$this->setError("is empty string");
				return false;
			}
		}
		if(isset($aRules['max_length']))
		{
			if(strlen($sValue) > $aRules['max_length'])
			{
				$this->setError("is too long");
				return false;
			}
		}
		if(isset($aRules['min_length']))
		{
			if(strlen($sValue) < $aRules['min_length'])
			{
				$this->setError("is too short");
				return false;
			}
		}
		return true;
	}
	protected function checkNumber($sValue = null, $aRules = array())
	{
		if(!is_numeric($sValue))
		{
			$this->setError("is not the number");
			return false;
		}
		if(isset($aRules['max']))
		{
			if($sValue > $aRules['max'])
			{
				$this->setError("is greater than limitation");
				return false;
			}
		}
		if(isset($aRules['min']))
		{
			if(strlen($sValue) < $aRules['min'])
			{
				$this->setError("is less than limitation");
				return false;
			}
		}
		if(isset($aRules['max_length']))
		{
			if(strlen($sValue) > $aRules['max_length'])
			{
				$this->setError("length is too long");
				return false;
			}
		}
		return true;
	}
	protected function checkArray($mValues = null, $aRules = array())
	{
		if(!is_array($mValues))
		{
			$this->setError("not array");
			return false;
		}
		if(isset($aRules['required_fields']))
		{
			$aRules['required_fields'] = explode(',', $aRules['required_fields']);
			if(is_array($aRules['required_fields']) && count($aRules['required_fields']))
			{
				foreach($aRules['required_fields'] as $sField)
				{
					if(!isset($mValues[$sField]))
					{
						$this->setError($sField." is not existed in array");
						return false;
					}
				}
			}
		}
		return true;
	}
	protected function checkEmail($mValues = null, $aRules = array())
	{
		if($mValues && filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		$this->setError($mValues . " is invalid email format");
		return false;
	}
	public static function getType($sString)
	{
		$mLimit = null;
		$sType = self::TYPE_STRING;
		$sPredictType = "TEXT";
		$sString = strtoupper($sString);
		if(preg_match('/(.*?)\((.*?)\)/si', $sString,$aMatches))
		{
			if(count($aMatches) == 3)
			{
				$sPredictType = $aMatches[1];
				$mLimit = $aMatches[2];
			}
		}
		//type https://www.techonthenet.com/mysql/datatypes.php
		$aLists = array(
				self::TYPE_STRING => array(
						'CHAR','VARCHAR','TINYTEXT','TEXT','MEDIUMTEXT','LONGTEXT','BINARY','VARBINARY','LONGTEXT'
				),
				self::TYPE_NUMBER => array(
						'BIT','TINYINT','SMALLINT','MEDIUMINT','INT','INTEGER','BIGINT','DECIMAL','DEC','NUMERIC',
						'FIXED','FLOAT','DOUBLE','BOOL','BOOLEAN',
				),
				self::TYPE_DATE => array(
						'DATE','DATETIME','TIMESTAMP','TIME','YEAR'
				),
				self::TYPE_OBJECT => array(
						'TINYBLOB','BLOB','MEDIUMBLOB'
				)
		);

		foreach($aLists as $sTypeTmp => $aListSupportTypes)
		{
			if(in_array($sPredictType, $aListSupportTypes))
			{
				$sType = $sTypeTmp;break;
			}
		}
		return array($sType, $mLimit);
	}

}