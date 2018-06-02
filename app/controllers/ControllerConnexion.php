<?php
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerConnexion
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
			$this->connect(); 
		}
	}

	public function connect()
	{		
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/UsersManager.php';
		$this->_usersManager = new UsersManager($db);
		$user = $this->_usersManager->checkUser();


		if(isset($_POST['connect_email']) && isset($_POST['connect_pass']))
		{
			if($user != false && !isset($_SESSION['auth']))
			{
				$_SESSION['auth'] = 1;
				$_SESSION['id'] = $user->id();
				$_SESSION['lastname'] = $user->lastname();
				$_SESSION['firstname'] = $user->firstname();
				$_SESSION['email'] = $user->email();
				$_SESSION['username'] = $user->username();
				$_SESSION['password'] = $user->password();
				$_SESSION['rank'] = $user->rank();

				// die('ok 1');
				header('Location:admin');
			}
			elseif(isset($_SESSION['auth']))
			{
				// die('ok 2');
				header('Location:admin');
			}
			else
			{
				// die('ok 3');
				SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
				$this->_view = new View('Connexion');
				$this->_view->generate(NULL);
			}
		}
		else
		{
			$this->_view = new View('Connexion');
			$this->_view->generate(NULL);
		}
	}
}
