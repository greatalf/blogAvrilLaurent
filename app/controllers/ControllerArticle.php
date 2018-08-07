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

				$getCommentById = $this->_commentsManager->getCommentByUserId();
				if(isset($getCommentById))
				{
					$this->_view = new View('article');	
					$this->_view->generate(array('onePost' => $onePost, 'getCommentById' => $getCommentById, 'comments' => $comments));
					exit();
				}
				$this->_view = new View('article');	
					$this->_view->generate(array('onePost' => $onePost, 'comments' => $comments));
					exit();
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
		$post_id = isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : '';
        if(isset($_GET['commentUpdate']))
		{
			$onePost = $this->_postsManager->getUnique($post_id);

			if($onePost->id() == 0)
			{
				FLASH::setFlash('L\'article demandé est inexistant!');
				header('Location: articles');
				exit();
			}


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
		$this->renderViewOneArticleAndComments();	
		}	
    }

    public function commentDelete()
    {
    	if(isset($_GET['commentDelete']))
		{
			$post_id = isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : '';

			$this->_comment = new Comments
				([
					'id' => (int)$_GET['commentDelete']
				]);	
			$this->_commentsManager->delete($this->_comment);
			FLASH::setFlash('Le commentaire a bien été supprimé.', 'success');

			header('Refresh:0, url=article&post_id=' . $post_id);
			exit();
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

	public function renderViewOneArticleAndComments()
	{
		$post_id = isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : '';
		$onePost = $this->_postsManager->getUnique($post_id);
		$updateComment = $this->_commentsManager->getUniqueComment($_GET['commentUpdate']);
		if($updateComment == NULL || $updateComment->id() == NULL)
		{
			header('Location: article&post_id=' . $post_id);
			FLASH::setFlash('Le commentaire recherché est inexistant!');
			exit();
		}
		$comments = $this->_commentsManager->getListComments($_GET['post_id']);
		$getCommentById = $this->_commentsManager->getCommentByUserId();

		$this->_view = new View('article');
		$this->_view->generate(array('comments' => $comments, 'updateComment' => $updateComment, 'onePost' => $onePost, 'getCommentById' => $getCommentById));
		exit();
	}
}
