<?php
/**
 * Class Autoloader
 * @package Tutoriel
 */
class Autoloader
{
	// $DS = DIRECTORY_SEPARATOR;

    /**
     * Enregistre notre autoloader
     */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    public static function autoload($class)
    {
        // if (strpos($class, __NAMESPACE__ . '\\') === 0)
        // {
            require $class . '.php';
        // }
    }        
}
