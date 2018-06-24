<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;


require_once 'app/views/View.php';


class ControllerCommentaire
{
	private $_commentsManager,
			$_view;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
	}

	public function comment()
	{
		// $db = DBFactory::getConnexionPDO();

		//Mettre ici le tout pour gérer les soumissions des commentaires... avec ou sans connexion
		if($_SESSION['auth'])
        {
			require_once 'app/models/CommentsManager.php';
			$this->_commentsManager = new CommentsManager($db);
			$comment = $this->_commentsManager->add($_GET['id']);
		}
		else
		{
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

					header('Location:admin');
				}
				else
				{
					SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
					header('Location:articles');
				}
			}
		}		
	}
}
