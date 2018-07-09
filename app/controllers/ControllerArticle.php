<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\Posts;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\Comments;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use \Laurent\App\Views\View;
use \Laurent\App\Service\Flash;
use \Laurent\App\Service\Security;

class ControllerArticle extends ControllerMain
{

/////////////////////////////////////////////////////////////
/////////////////LISTE DES ARTCILES//////////////////////////
/////////////////////////////////////////////////////////////
    public function getAllPosts()
    {    	
    	$GLOBALS['posts'] = $this->_postsManager->getList(0, 25);
    	$this->renderViewArticles();

    	if(isset($_SESSION['auth']) && $_SESSION['rank'] == 2)
    	{
    		$this->addPost();
    	}
    // exit();
    }

    public function addPost()
    {    	
    	if(isset($_POST['post_submit']))
		{				
			$author = isset($_POST['post_author']) ? htmlspecialchars($_POST['post_author']) : false;
			$title = isset($_POST['post_title']) ? htmlspecialchars($_POST['post_title']) : false;
			$chapo = isset($_POST['post_chapo']) ? htmlspecialchars($_POST['post_chapo']) : false;
			$content = isset($_POST['post_content']) ? htmlspecialchars($_POST['post_content']) : false;
		    	    		
			if(!empty($author) && !empty($title) && !empty($chapo) && !empty($content))
			{
				$this->_security->securizationCsrf();

				$this->_post = new Posts
				([
					'author' =>$author,
					'title' =>$title,
					'chapo' =>$chapo,
					'content' => $content,
					'addDate' => new \DateTime()
				]);

				$this->_postsManager->add($this->_post);
	
				FLASH::setFlash('Merci ' . $_SESSION['username'] . ', votre nouvel article a bien été envoyé.', 'success');
				header('Refresh:0');
				exit();
			}	
			FLASH::setFlash('Veuillez remplir tous les champs  correctement!');
			header('Refresh:2, url=articles');
			exit();

	    }   
	}    	

/////////////////////////////////////////////////////////////
///////////////////////////ARTICLE UNIQUE////////////////////
/////////////////////////////////////////////////////////////

	public function getUniquePost()
    {
	    $post_id = isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : '';
	    $onePost = $this->_postsManager->getUnique($post_id);


		if($onePost->id() != 0)
		{
			if(isset($post_id) && $onePost)
			{
				$comments = $this->_commentsManager->getListComments($post_id);

				$this->addComment();

				// if(!headers_sent())
				// {
	    // var_dump($onePost); die();
					$this->_view = new View('article');	
					$this->_view->generate(array('onePost' => $onePost, 'comments' => $comments));
					exit();			
				// }
			}
		}
		else
		{
			FLASH::setFlash('L\'article demandé est inexistant!');
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
				$this->_security->securizationCsrf();

				$this->_comment = new Comments
					([
						'author' => $_SESSION['username'],
						'content' => $_POST['com_content'],
						'addDate' => new \DateTime()
					]);

				$this->_commentsManager->add($this->_comment);

				FLASH::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé à l\'administrateur pour validation.', 'success');
				header('Refresh:0, url=article&post_id=' . $post_id);
				exit();
			}
			elseif(empty($_POST['com_content']) && isset($_POST['com_submit']))
			{
				FLASH::setFlash('Veuillez remplir le champs "commentaire" correctement.');
				header('Location: article&post_id=' . $post_id);
				exit();
			}
		}
	}

    public function commentUpdate()
    {
        if(isset($_GET['commentUpdate']))
		{
			$post_id = isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : '';

			$this->_security->securizationCsrf();

			if(isset($_POST['com_update']))
			{	
				if(empty($_POST['com_content']))
				{
					FLASH::setFlash('Remplissez correctement le champs "commentaire" pour le modifier!');
					header('Location: article&post_id=' . $post_id);
					exit();
				}				
				$this->_comment = new Comments
				([
					'id' => (int) $_GET['commentUpdate'],
					'content' => $_POST['com_content']
				]);

				$this->_commentsManager->update($this->_comment);

				FLASH::setFlash('Le commentaire a bien été modifié.', 'success');
				header('Refresh:0, url=article&post_id=' . $post_id);
				exit();		
			}
			$onePost = $this->_postsManager->getUnique($post_id);
			$updateComment = $this->_commentsManager->getUniqueComment($_GET['commentUpdate']);
			$comments = $this->_commentsManager->getListComments($_GET['post_id']);

			if($updateComment->id() === NULL)
			{
				header('Location: article&post_id=' . $post_id);
				FLASH::setFlash('Le commentaire recherché est inexistant!');
				exit();
			}

			$this->_view = new View('article');
			$this->_view->generate(array('comments' => $comments, 'updateComment' => $updateComment, 'onePost' => $onePost));
			exit();
		}
    }

    public function commentDelete()
    {

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
		// 				FLASH::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
						
		// 				header('Refresh:0, url=article&post_id=' . $post_id);
		// 				exit();
		// 			}
		// 			else
		// 			{
		// 				FLASH::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
		// 				header('Refresh:0, url=article&post_id=' . $post_id);
		// 				exit();
		// 			}
		// 		}
		// 		else
		// 		{
		// 			FLASH::setFlash('Veuillez remplir tous les champs correctement !');
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
		// 				FLASH::setFlash('Le commentaire a bien été supprimé.', 'success');

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

		// 					FLASH::setFlash('Le commentaire a bien été mis jour.', 'success');
		// 					header('Refresh:0, url=article&post_id=' . $post_id);
		// 					exit();
		// 				}
		// 				elseif(!$commentUpdate)
		// 				{
		// 					FLASH::setFlash('Le commentaire demandé est inexistant !');
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

