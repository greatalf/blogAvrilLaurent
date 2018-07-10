<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Users;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;
use Laurent\App\Service\Flash;
use Laurent\App\Service\Security;

class ControllerApp extends ControllerMain
{
	public function home()
	{	
		$this->_view = new View('Accueil');
		$this->_view->generate(NULL);
		exit();
	}	
}
