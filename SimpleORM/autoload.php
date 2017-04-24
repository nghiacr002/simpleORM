<?php

function simpleORM_autoload($class)
{
	$prefix = 'SimpleORM\\';
	// base directory for the namespace prefix
	$base_dir = dirname(__FILE__);
	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0)
	{
		// no, move to the next registered autoloader
		return;
	}
	// get the relative class name
	$relative_class = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . DIRECTORY_SEPARATOR. str_replace('\\', '/', $relative_class) . '.php';
	// if the file exists, require it
	if (file_exists($file))
	{
		require $file;
	}
}
spl_autoload_extensions(".php");
spl_autoload_register('simpleORM_autoload');