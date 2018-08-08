<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\Posts;
use \Laurent\App\Models\Comments;
use \Laurent\App\Models\Users;
use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use \Laurent\App\Controllers\ControllerArticle;
use \Laurent\App\Views\View;
use \Laurent\App\Service\Flash;
use \Laurent\App\Service\Security;
use \Laurent\App\Service\Profile;

class ControllerAdmin extends ControllerMain
{
	public function admin()
    {
    	if(!isset($_SESSION['auth']))
    	{
    		FLASH::setFlash('Une connexion est requise pour accéder à l\'espace d\'administration!');
			header('Location: connexion');
			exit();    	
		}

		if(isset($_POST['profil_modify']))
		{
			$this->_profile->registerProfile();
		}
		$this->renderViewAdmin();			
		exit();
    }    

    public function writeAPost()
    {
		if(isset($_POST['post_submit']))
		{				
			$author = isset($_POST['post_author']) ? htmlspecialchars($_POST['post_author']) : '';
			$title = isset($_POST['post_title']) ? htmlspecialchars($_POST['post_title']) : '';
			$chapo = isset($_POST['post_chapo']) ? htmlspecialchars($_POST['post_chapo']) : '';
			$content = isset($_POST['post_content']) ? htmlspecialchars($_POST['post_content']) : '';
		    	    		
			if(empty($author) && empty($title) && empty($chapo) && empty($content))
			{
				FLASH::setFlash('Veuillez remplir tous les champs  correctement!');
				header('Location: articles');
				exit();
			}
		}	
	    
    	$this->_security->securizationCsrf();

		$this->_post = new Posts
			([
				'author' => htmlspecialchars($_POST['post_author']),
				'title' => htmlspecialchars($_POST['post_title']),
				'chapo' => htmlspecialchars($_POST['post_chapo']),
				'content' => htmlspecialchars($_POST['post_content']),
				'addDate' => new \DateTime()
			]);

		$this->_postsManager->add($this->_post);

		FLASH::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
		header('Refresh:0, url=article&post_id=' . $post_id);
		exit();
	}	

    public function renderViewAdmin()
	{		
		$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);
    	$allUsers = $this->_usersManager->getUsersList();
    	$postList = $this->_postsManager->getList(0,25);

    	$validateComment = $this->_commentsManager->validateCommentList();
		$usersCount = $this->_usersManager->count();
		$postCount = $this->_postsManager->count();
		$commentValidateCount = $this->_commentsManager->count();
		$getCommentById = $this->_commentsManager->getCommentByUserId();
		$allComments = $this->_commentsManager->getAllComments();

		if(!headers_sent())
		{		
			if($_SESSION['rank'] == 2)	
			{				
				$this->_view = new View('admin');		
				$this->_view->generate(array('postList' => $postList, 'userInfos' => $userInfos, 'allUsers' => $allUsers, 'validateComment' => $validateComment, 'usersCount' => $usersCount, 'postCount' => $postCount, 'commentValidateCount' => $commentValidateCount, 'allComments' => $allComments));		
			}	
			if(isset($getCommentById))
			{
				$this->_view = new View('admin');		
				$this->_view->generate(array('postList' => $postList, 'userInfos' => $userInfos, 'getCommentById' => $getCommentById, 'allComments' => $allComments));
			}	
			$this->_view = new View('admin');		
			$this->_view->generate(array('postList' => $postList, 'userInfos' => $userInfos));	
		}
	}

	public function postDelete()
	{
		if(isset($_GET['postDelete']))
		{	
			$post = $this->_postsManager->getUnique($_GET['postDelete']);

			$this->_security->securizationCsrf();

			$this->_post = new Posts
			([
				'id' => $_GET['postDelete']
			]);

			$this->_postsManager->delete($this->_post);

			FLASH::setFlash('L\'article ainsi que ses commentaires associés ont bien été supprimé', 'success');
			header('Location: admin');
			exit();
		}
	}

	public function postUpdate()
	{
		if(isset($_GET['postUpdate']))
		{
			$this->_security->securizationCsrf();

			if(isset($_POST['post_update']))
			{	
				if(empty($_POST['post_author']) || empty($_POST['post_title']) || empty($_POST['post_chapo']) || empty($_POST['post_content']))
				{
					FLASH::setFlash('Remplissez correctement tous les champs!');
					header('Location: articles');
					exit();
				}				
				$this->_post = new Posts
				([
					'author' => htmlspecialchars($_POST['post_author']),
					'title' => htmlspecialchars($_POST['post_title']),
					'chapo' => htmlspecialchars($_POST['post_chapo']),
					'content' => htmlspecialchars($_POST['post_content'])
				]);

				$this->_postsManager->update($this->_post);

				FLASH::setFlash('L\'article a bien été modifié.', 'success');
				header('Location: articles');
				exit();		
			}
			$updatePost = $this->_postsManager->getUnique($_GET['postUpdate']);
			$posts = $this->_postsManager->getList(0, 5);
			$this->_view = new View('articles');
			$this->_view->generate(array('posts' => $posts, 'updatePost' => $updatePost));
			exit();
		}
	}

	public function commentValidate()
	{
		$this->_commentsManager->turnValidateCommentOk(new Comments([
			'id' => htmlspecialchars($_GET['commentValidate'])
		]));
		FLASH::setFlash('Le commentaire a bien été validé.', 'success');
		header('Location: admin');
		exit();	
	}	

	public function commentNoValidate()
	{
		$this->_commentsManager->turnNoValidateComment(new Comments([
			'id' => htmlspecialchars($_GET['commentNoValidate'])
		]));
		FLASH::setFlash('Le commentaire a bien été supprimé.', 'success');
		header('Location: admin');
		exit();
	}

	public function userBanish()
	{
		$this->_usersManager->delete(new Users([
			'id' => htmlspecialchars($_GET['userBanish'])
		]));
		FLASH::setFlash('L\'utilisateur a bien été supprimé.', 'success');
		header('Location: admin');
		exit();
	} 

	public function upgradeUser()
	{
		$this->_usersManager->upgradeUser(new Users([
			'id' => htmlspecialchars($_GET['upgradeUser'])
		]));
		FLASH::setFlash('L\'utilisateur a bien été upgradé au rang d\'administrateur.', 'success');
		header('Location: admin');
		exit();
	}

	public function downgradeUser()
	{
		$this->_usersManager->downgradeUser(new Users([
			'id' => htmlspecialchars($_GET['downgradeUser'])
		]));
		FLASH::setFlash('L\'utilisateur a bien été downgradé au rang de contributeur.', 'success');
		header('Location: admin');
		exit();
	}
}
