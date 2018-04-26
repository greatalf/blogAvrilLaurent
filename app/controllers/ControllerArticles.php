<?php
require_once 'app/views/View.php';

class ControllerArticles
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
			$this->posts(); 
		}
	}

	public function posts()
	{
		$db = DBFactory::getConnexionPDO();
		require_once 'app/models/PostsManager.php';
		$this->_postsManager = new PostsManager($db);
		$posts = $this->_postsManager->getList(0,5);
		$this->_view = new View('Articles');
		$this->_view->generate(array('posts' => $posts));
	}
}
