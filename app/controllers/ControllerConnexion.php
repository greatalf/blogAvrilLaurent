<?php
namespace Laurent\App\Controllers;
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';


class ControllerConnexion
{
	private $_view,
			$_usersManager;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
	}

	public function connect()
	{		
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/UsersManager.php';
		$this->_usersManager = new UsersManager($db);
		
}
