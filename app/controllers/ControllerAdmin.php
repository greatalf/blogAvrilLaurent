<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use \Laurent\App\Controllers\ControllerArticle;
use Laurent\App\Views\View;
use Laurent\App\Service\Flash;

class ControllerAdmin
{
	private $_view,
			$_post,
			$_user;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
		$this->_controllerArticle = new ControllerArticle();
	}


	public function admin()
    {
    	if(isset($_SESSION['auth']))
    	{
    		if(isset($_SESSION['rank']) && $_SESSION['rank'] == 1)
	    	{
	    		
	    	}
    		
    		if(isset($_SESSION['rank']) && $_SESSION['rank'] == 2)
	    	{
	    		$this->writeAPost();
	    	}	    	
		$this->renderViewAdmin();			
		exit();
		}
		else
		{
			FLASH::setFlash('Une connexion est requise pour accéder à l\'espace d\'administration!');
			header('Location: connexion');
			exit();
		}
    }
    

    public function writeAPost()
    {
		if(isset($_POST['post_submit']))
		{				
			$author = isset($_POST['post_author']) ? htmlspecialchars($_POST['post_author']) : '';
			$title = isset($_POST['post_title']) ? htmlspecialchars($_POST['post_title']) : '';
			$chapo = isset($_POST['post_chapo']) ? htmlspecialchars($_POST['post_chapo']) : '';
			$content = isset($_POST['post_content']) ? htmlspecialchars($_POST['post_content']) : '';
		    	    		
			if(!empty($author) && !empty($title) && !empty($chapo) && !empty($content))
			{
				$this->addAPost();
			}	
			else
			{
				FLASH::setFlash('Veuillez remplir tous les champs  correctement!');
				header('Location: articles');
				exit();
			}
	    }
	}    	

    public function addAPost()
    {    
		$this->_post = new Posts
			([
				'author' => $_POST['post_author'],
				'title' => $_POST['post_title'],
				'chapo' => $_POST['post_chapo'],
				'content' => $_POST['post_content'],
				'addDate' => new \DateTime()
			]);

		$this->_postsManager->add($this->_post);

		FLASH::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
		header('Refresh:0, url=article&post_id=' . $post_id);
		exit();
	}	

    // public function getUserInfos()
    // {
    // 	$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);
    // 	$allUsers = $this->_usersManager->getUsersList();
    // 	$manager = $this->_postsManager->getList(0,25);
    // }

    public function renderViewAdmin()
	{		
		$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);
    	$allUsers = $this->_usersManager->getUsersList();
    	$postList = $this->_postsManager->getList(0,25);

		if(!headers_sent())
		{						
				$this->_view = new View('admin');		
				// $this->_view->generate(NULL);
				$this->_view->generate(array('postList' => $postList, 'userInfos' => $userInfos, 'allUsers' => $allUsers));		
		}
	}
	// public function admin()
	// {
	// 	if(isset($_SESSION['auth']))
	// 	{
	// 		$db = DBFactory::getConnexionPDO();
	// 		require_once 'app/models/PostsManager.php';
	// 		require_once 'app/models/UsersManager.php';


	// 		$this->_usersManager = new UsersManager($db);
	// 		$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);

	// 		if($_SESSION['rank'] == 2)
	// 		{
	// 			$this->_postsManager = new PostsManager($db);
	// 			$manager = $this->_postsManager->getList(0,25);

	// 			$allUsers = $this->_usersManager->getUsersList();		

	// 			//Effacement article
	// 			if($_SESSION['rank'] == 2)
	// 			{
	// 				if(isset($_GET['post_delete']))
	// 				{
	// 					//HYDRATATION POUR DELETE
	// 					$this->_post = new Posts
	// 					([
	// 						'id' => $_GET['post_delete']
	// 					]);

	// 					$this->_postsManager->delete($this->_post);
	// 					//Affichage du message msg flash
	// 					FLASH::setFlash('L\'article a bien été supprimé.', 'success');

	// 					header('Refresh:0, url=admin');
	// 					exit();
	// 				}
	// 			}

	// 			//Effacement User
	// 			if(isset($_GET['user_delete']))
	// 				{
	// 					//HYDRATATION POUR DELETE
	// 					$this->_user = new Users
	// 					([
	// 						'id' => $_GET['user_delete']
	// 					]);
						
	// 					$this->_usersManager->delete($this->_user);
	// 					//Affichage du message msg flash
	// 					FLASH::setFlash('L\'utilisateur a bien été bannit.', 'success');

	// 					header('Location:admin');
	// 					exit();
	// 				}
	// 		}


			
	// 		if($_SESSION['rank'] == 1)
	// 		{
	// 			$this->_postsManager = new PostsManager($db);
	// 			$manager = $this->_postsManager->getMyList(0,10);

	// 			$this->_view = new View('Admin');
	// 			if($manager != NULL)
	// 			{
	// 				$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos));	
	// 			}
	// 			else
	// 			{
	// 				$this->_view->generate(array('userInfos' => $userInfos));
	// 			}
	// 		}
	// 	}
	// 	else
	// 	{
	// 		FLASH::setFlash('Vous n\'êtes pas autorisé à accéder à cette page');
	// 		header('Location:connexion');
	// 		exit();
	// 	}

	// 	if(!headers_sent())
	// 	{
	// 		$this->_view = new View('Admin');
	// 		$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos, 'allUsers' => $allUsers));
	// 	}
	// }
}
