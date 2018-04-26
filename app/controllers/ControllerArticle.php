<?php
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerArticle
{
	private $_postsManager,
			$_view;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->post(); 
		}
	}

	public function post()
	{
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/PostsManager.php';
		require_once 'app/models/CommentsManager.php';
		$this->_postsManager = new PostsManager($db);
		$this->_commentsManager = new CommentsManager($db);

		if(isset($_GET['id']))
		{
			$post = $this->_postsManager->getUnique($_GET['id']);
			$comments = $this->_commentsManager->getListComments($_GET['id']);

			//Verify the comment's presence on post
			if(count($comments) == 0)
			{
				$_SESSION['comments_abs'] = 'Aucun commentaire n\'a été posté pour cet article!';
			}
			
			$this->_view = new View('Article');
			$this->_view->generate(array('post' => $post, 'comments' => $comments));
		}
		else
		{
			header('Location:articles');
		}

	}
}
