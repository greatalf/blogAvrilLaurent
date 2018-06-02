<?php
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerConfirm
{
	private $_view,
			$_usersManager;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->confirm(); 
		}
	}

	public function confirm()
	{		
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/UsersManager.php';
		$this->_usersManager = new UsersManager($db);

		$user = $this->_usersManager->confirmUser();
		
			if($user != false && !isset($_SESSION['auth']))
			{
				$_SESSION['auth'] = 1;
				$_SESSION['id'] = $user->id();
				$_SESSION['lastname'] = $user->lastname();
				$_SESSION['firstname'] = $user->firstname();
				$_SESSION['email'] = $user->email();
				$_SESSION['username'] = $user->username();
				$_SESSION['password'] = $user->password();
				$_SESSION['rank'] = 1;

				header('Location:admin');
			}
			elseif(isset($_SESSION['auth']))
			{
				header('Location:admin');
			}
			else
			{
				SESSION::setFlash('Veuillez remplir les champs de connexion');
				$this->_view = new View('Connexion');
				$this->_view->generate(NULL);
			}
	}
}