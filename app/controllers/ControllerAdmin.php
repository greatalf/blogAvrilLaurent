<?php
session_start();
require_once 'app/views/View.php';
class ControllerAdmin
{
	private $_view;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->admin(); 
		}
	}

	public function admin()
	{
		if(isset($_SESSION['auth']) && $_SESSION['auth'] == 1)
		{
			$db = DBFactory::getConnexionPDO();
			require_once 'app/models/PostsManager.php';
			$this->_postsManager = new PostsManager($db);
			$manager = $this->_postsManager->getList(0,5);

			$this->_view = new View('Admin');
			$this->_view->generate(array('manager' => $manager));			
		}
		else
		{
			setcookie("denied", $denied = 'Vous n\'êtes pas autorisé à accéder à cette page', time()+(5));
			header('Location:connection');
		}
	}
}
