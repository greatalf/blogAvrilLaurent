<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;

require_once 'app/views/View.php';


class ControllerAdmin
{
	private $_view,
			$_user;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
	}

	public function admin()
	{
		if(isset($_SESSION['auth']))
		{
			$db = DBFactory::getConnexionPDO();
			require_once 'app/models/PostsManager.php';
			require_once 'app/models/UsersManager.php';


			$this->_usersManager = new UsersManager($db);
			$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);

			if($_SESSION['rank'] == 2)
			{
				$this->_postsManager = new PostsManager($db);
				$manager = $this->_postsManager->getList(0,25);

				$allUsers = $this->_usersManager->getUsersList();		

				//Effacement article
				if($_SESSION['rank'] == 2)
				{
					if(isset($_GET['post_delete']))
					{
						//HYDRATATION POUR DELETE
						$this->_post = new Posts
						([
							'id' => $_GET['post_delete']
						]);

						$this->_postsManager->delete($this->_post);
						//Affichage du message msg flash
						SESSION::setFlash('L\'article a bien été supprimé.', 'success');

						header('Refresh:0, url=admin');
						exit();
					}
				}

				//Effacement User
				if(isset($_GET['user_delete']))
					{
						//HYDRATATION POUR DELETE
						$this->_user = new Users
						([
							'id' => $_GET['user_delete']
						]);
						
						$this->_usersManager->delete($this->_user);
						//Affichage du message msg flash
						SESSION::setFlash('L\'utilisateur a bien été bannit.', 'success');

						header('Location:admin');
						exit();
					}
			}


			
			if($_SESSION['rank'] == 1)
			{
				$this->_postsManager = new PostsManager($db);
				$manager = $this->_postsManager->getMyList(0,10);

				$this->_view = new View('Admin');
				if($manager != NULL)
				{
					$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos));	
				}
				else
				{
					$this->_view->generate(array('userInfos' => $userInfos));
				}
			}
		}
		else
		{
			SESSION::setFlash('Vous n\'êtes pas autorisé à accéder à cette page');
			header('Location:connexion');
			exit();
		}

		if(!headers_sent())
		{
			$this->_view = new View('Admin');
			$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos, 'allUsers' => $allUsers));
		}
	}
}
