<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;

require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerAccueil
{
	private $_view;

	public function __construct()
	{
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
	}

	public function home()
	{		
		$this->_view = new View('Accueil');
		$this->_view->generate(NULL);
	}
}
