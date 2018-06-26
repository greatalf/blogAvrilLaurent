<?php
namespace Laurent\App\Controllers;
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';


class ControllerConfirm
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

	public function confirm()
	{		
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/UsersManager.php';
		$this->_usersManager = new UsersManager($db);

		if(isset($_SESSION['auth']) && $_GET['user_id'] != $_SESSION['id'])
		{
			header('Location:articles');
			exit();
		}
		
		if(isset($_GET['user_id']) && isset($_GET['confirmation_token']))
		{					
			$user = $this->_usersManager->confirmUser();
			$token = htmlspecialchars($_GET['confirmation_token']);

			if($user)
			{
				if($user->confirmation_token == $token)
				{
					$this->_usersManager->userIsConfirmed();

					$_SESSION['auth'] = 1;
					$_SESSION['id'] = $user->id();
					$_SESSION['lastname'] = $user->lastname();
					$_SESSION['firstname'] = $user->firstname();
					$_SESSION['email'] = $user->email();
					$_SESSION['username'] = $user->username();
					$_SESSION['password'] = $user->password();
					$_SESSION['rank'] = 1;

					header('Location:articles');
					exit();
				}
				if($user->confirmation_token == NULL)
				{
					SESSION::setFlash('Ce token est invalide ou obsolète');
					header('Refresh:0, url=connexion');
					exit();
				}
			}
			else
			{
				header('Location:articles');
				exit();
			}
		}
		else
		{
			header('Location:articles');
			exit();
		}
	}
}