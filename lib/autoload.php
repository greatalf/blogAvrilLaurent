<?php
function autoload($class_name)
{
	$file = __DIR__.'/'.$class_name.'.php';
	if(file_exists($file))
	{
		require $file;
	}
}

spl_autoload_register('autoload');
