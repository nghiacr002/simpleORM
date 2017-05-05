<?php

namespace SimpleORM\Helper;

class Object
{
    protected $_aData;
    public function __construct()
    {
        $this->_aData = array();
    }
	public static function factory($sClassName, $mData = null)
	{
		try{
			$oObject = new $sClassName($mData);
			return $oObject;
		}
		catch(\Exception $ex)
		{
			throw new Exception($ex->getMessage());
		}
		return null;
	}
    public function __set($sName, $mValue)
    {
        $this->_aData[$sName] = $mValue;
        return $this;
    }
    public function __isset($sName)
    {
        return isset($this->_aData[$sName]);
    }

    public function __get($sName)
    {
        if (array_key_exists($sName, $this->_aData))
        {
            return $this->_aData[$sName];
        }
        return null;
    }

    public function __call($sName, $arguments = array())
    {
        if (method_exists($this, $sName))
        {
            call_user_func_array(array($this, $sName), $arguments);
        }
        return null;
    }

    public static function __set_state($array)
    {
        $obj = new static();
        foreach ($array as $key => $value)
        {
            $obj->{$key} = $value;
        }
        return $obj;
    }
    public function toArray()
    {
        return $this->_aData;
    }
    public function toString()
    {
    	return get_class($this);
    }
    public function dump($bExist = false)
    {
    	$bVarDump = false;
    	$bCliOrAjax = (PHP_SAPI == 'cli');
    	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
    	($bVarDump ? var_dump($this->_aData) : print_r($this->_aData));
    	(!$bCliOrAjax ? print '</pre>' : false);
    	if($bExist)
    	{
    		exit;
    	}
    }
}
