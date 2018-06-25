<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\Posts;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\Comments;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use \Laurent\App\Views\View;
use \Laurent\App\Session;

class ControllerArticle
{
	private $_comment;
	private	$_post;
	private	$_view;
	private $_db;
	private $_postsManager;
    private $_usersManager;
    private $_commentsManager;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_db = $_db;
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
    }

    public function getAllPosts()
    {
    	$GLOBALS['posts'] = $this->_postsManager->getList(0, 5);
    	$this->renderViewArticles();
		// $this->_view = new View('Articles');			
		// $this->_view->generate(array('posts' => $posts));
		// $this->verifyConnectUser();
		// exit();			
    }    

    public function getUniquePost()
    {    	
	    $post_id = htmlspecialchars($_GET['post_id']);
	    $onePost = $this->_postsManager->getUnique($post_id);

		if($onePost[0]->id() != 0)
		{
			if(isset($post_id) && $onePost)
			{
				$comments = $this->_commentsManager->getListComments($post_id);

				$this->addComment();

				if(!headers_sent())
				{
					$this->_view = new View('Article');	
					$this->_view->generate(array('onePost' => $onePost, 'comments' => $comments));
					exit();			
				}
			}
		}
		else
		{
			SESSION::setFlash('L\'article demandé est inexistant!');
			header('Location: articles');
			exit();
		}
    }

    public function addComment()
    {    	
    	$post_id = htmlspecialchars($_GET['post_id']);

		if(isset($_SESSION['auth']))
		{
			if(!empty($_POST['com_content']) && isset($_POST['com_submit']))
			{
				$this->_comment = new Comments
					([
						'author' => $_SESSION['username'],
						'content' => $_POST['com_content'],
						'addDate' => new \DateTime()
					]);

				$this->_commentsManager->add($this->_comment);

				SESSION::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
				header('Refresh:0, url=article&post_id=' . $post_id);
				exit();
			}
			elseif(empty($_POST['com_content']) && isset($_POST['com_submit']))
			{
				SESSION::setFlash('Veuillez remplir le champs "commentaire" correctement.');
				header('Location: article&post_id=' . $post_id);
				exit();
			}
		}
	}	

	public function renderViewArticles()
	{		
		if(!headers_sent())
		{						
				$this->_view = new View('Articles');		
				$this->_view->generate(array('posts' => $GLOBALS['posts']));		
		}
	}

}

///////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////RESTE DES FONCTIONS A IMPLEMENTER :)))///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //   	public function resteAFaire()
	 //    {  		

		// 	//if user diconnected
		// 	if(!isset($_SESSION['auth']) && isset($_POST['com_submit']))
		// 	{
		// 		//Si les champs sont complétés
		// 		if(!empty($_POST['com_content']) && isset($_POST['com_submit']))
		// 		{
		// 			//Si l'user existe bien et qu'il n'est pas connecté
		// 			if($user != false)
		// 			{
		// 				//Initialisation des datas
		// 				$_SESSION['auth'] = 1;
		// 				$_SESSION['id'] = $user->id();
		// 				$_SESSION['lastname'] = $user->lastname();
		// 				$_SESSION['firstname'] = $user->firstname();
		// 				$_SESSION['email'] = $user->email();
		// 				$_SESSION['username'] = $user->username();
		// 				$_SESSION['password'] = $user->password();
		// 				$_SESSION['rank'] = $user->rank();

		// 				//Ajout du commentaire en BDD par hydratation
		// 				$this->_comment = new Comments
		// 				([
		// 					'author' => $user->username(),
		// 					'content' => $_POST['com_content'],
		// 					'addDate' => new \DateTime()
		// 				]);
						
		// 				$this->_commentsManager->add($this->_comment);

		// 				//Affichage du message cookie flash
		// 				SESSION::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
						
		// 				header('Refresh:0, url=article&post_id=' . $post_id);
		// 				exit();
		// 			}
		// 			else
		// 			{
		// 				SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
		// 				header('Refresh:0, url=article&post_id=' . $post_id);
		// 				exit();
		// 			}
		// 		}
		// 		else
		// 		{
		// 			SESSION::setFlash('Veuillez remplir tous les champs correctement !');
		// 			header('Refresh:0, url=article&post_id=' . $post_id);
		// 			exit();
		// 		}
		// 	}	

		// 	//affichage des bouttons en fonction du rank
		// 	if(isset($_SESSION['rank']))
		// 	{
		// 		if($_SESSION['rank'] == 2)
		// 		{
		// 			if(isset($_GET['comment_delete']))
		// 			{
		// 				$this->_comment = new Comments
		// 					([
		// 						'id' => (int)$_GET['comment_delete']
		// 					]);	
		// 				$this->_commentsManager->delete($this->_comment);
		// 				//Affichage du message cookie flash
		// 				SESSION::setFlash('Le commentaire a bien été supprimé.', 'success');

		// 				header('Refresh:0, url=article&post_id=' . $post_id);
		// 				exit();
		// 			}
		// 		}

		// 		if($_SESSION['rank'] > 0)
		// 		{
		// 			if(isset($_GET['comment_update']))
		// 			{
		// 				$commentUpdate = $this->_commentsManager->getUniqueComment($_GET['comment_update']);
		// 				if($commentUpdate && isset($_POST['com_update']))
		// 				{
		// 					$this->_comment = new Comments
		// 					([
		// 						'id' => (int)$_GET['comment_update']
		// 					]);	
		// 					$this->_commentsManager->update($this->_comment);

		// 					SESSION::setFlash('Le commentaire a bien été mis jour.', 'success');
		// 					header('Refresh:0, url=article&post_id=' . $post_id);
		// 					exit();
		// 				}
		// 				elseif(!$commentUpdate)
		// 				{
		// 					Session::setFlash('Le commentaire demandé est inexistant !');
	 //            			header('Location:articles');
	 //            			exit();
		// 				}
		// 			}
		// 		}
		// 	}
		// 	if(!headers_sent())
		// 	{
		// 		$this->_view = new View('Article');

		// 		if(isset($commentUpdate))
		// 		{
		// 			$this->_view->generate(array('post' => $post, 'comments' => $comments, 'user' => $user, 'comment' => $commentUpdate));				
		// 		}
		// 		else
		// 		{
		// 			$this->_view->generate(array('post' => $post, 'comments' => $comments, 'user' => $user));
		// 		}
		// 		exit();			
		// 	}
		// }	

