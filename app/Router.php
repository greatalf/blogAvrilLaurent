<?php

use Laurent\App\Controllers\ControllerAccueil;
use Laurent\App\Controllers\ControllerArticle;
use Laurent\App\Controllers\ControllerAdmin;
use Laurent\App\Controllers\ControllerCommentaire;
use Laurent\App\Controllers\controllerRegister;
use Laurent\App\Controllers\controllerContact;
use Laurent\App\Controllers\controllerUser;

class Router 
{
	private $controllerAccueil;
	private $controllerAdmin;
	private $controllerArticle;
	private $controllerCommentaire;
	private $controllerRegister;
	private $controllerContact;
	private $controllerUser;

	public function __construct()
	{
		$this->controllerAccueil = new ControllerAccueil();
		$this->controllerArticle = new ControllerArticle();
		$this->controllerCommentaire = new controllerCommentaire();
		$this->controllerRegister = new controllerRegister();
		$this->controllerContact = new controllerContact();
		$this->controllerUser = new controllerUser();
		$this->controllerAdmin = new controllerAdmin();
	}

	public function routeReq()
	{
		try {
			$url = '';
			if(isset($_GET['url']))
			{	
				$url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

		        if ($url[0] == 'articles')
		        {
		            $this->controllerArticle->getAllPosts();
		        }
		        elseif ($url[0] == 'article')
		        {
		        	$this->controllerArticle->getUniquePost();
		        }
		        elseif ($url[0] == 'deconnexion')
		        {
		            $this->controllerUser->deco();
		        }
		        elseif ($url[0] == 'connexion')
		        {
		            $this->controllerUser->connexion();
		        }
		    }
	    	else
	    	{ 
	        	$this->controllerAccueil->home();
	    	}
		}
		catch(Exception $e)
		{
	    	echo 'Erreur : ' . $e->getMessage();
		}
	}
}
