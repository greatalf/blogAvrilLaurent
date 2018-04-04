<?php
class DBFactory
{
	public static function getConnexionPDO()
	{
		$db = new PDO('mysql:host=localhost;dbname=my_blog;charset=utf8', 'root', '');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $db;
	}
}
