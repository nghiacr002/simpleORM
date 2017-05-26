<?php
namespace SimpleORM\Helper;
class Validator
{
	const TYPE_STRING = "string";
	const TYPE_NUMBER = "number";
	const TYPE_ARRAY = "array";
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
				$this->setError("Field is not existed");
				$bReturn = false;
			}
		}
		if($bReturn && isset($aRules['regex']) && !empty($aRules['regex']))
		{
			if(!preg_match($aRules['regex'], $sValue))
			{
				$this->setError("Not pass regex rule");
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
				$this->setError("empty string");
				return false;
			}
		}
		if(isset($aRules['max_length']))
		{
			if(strlen($sValue) > $aRules['max_length'])
			{
				$this->setError("String is too long");
				return false;
			}
		}
		if(isset($aRules['min_length']))
		{
			if(strlen($sValue) < $aRules['min_length'])
			{
				$this->setError("String is too short");
				return false;
			}
		}
		return true;
	}
	protected function checkNumber($sValue = null, $aRules = array())
	{
		if(!is_number($sValue))
		{
			$this->setError("not the number");
			return false;
		}
		if(isset($aRules['max']))
		{
			if($sValue > $aRules['max'])
			{
				$this->setError("Number is greater than limitation");
				return false;
			}
		}
		if(isset($aRules['min']))
		{
			if(strlen($sValue) < $aRules['min'])
			{
				$this->setError("Number is less than limitation");
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

}