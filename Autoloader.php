<?php

class Autoloader
{
	static function register()
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	static function autoload($class)
	{
		$classArray = explode('\\', $class);
		$class = lcfirst($classArray[1]) . '\\' . lcfirst($classArray[2]) . '\\' . $classArray[3];
		// var_dump($class); die();
		// $class = str_replace('Laurent\\App\\Controllers', 'app\\controllers', $class);
		// $class = str_replace('\\', '/', $class);
		require $class . '.php';
	}
}
//require 'app\controllers\ControllerApp.php';