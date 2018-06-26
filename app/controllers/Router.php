<?php

use Laurent\App\Controllers\ControllerApp;
use Laurent\App\Controllers\ControllerArticle;
use Laurent\App\Controllers\ControllerAdmin;
use Laurent\App\Controllers\ControllerUser;
use Laurent\App\Service\Error404;

class Router 
{
	private $controllerApp;
	private $controllerAdmin;
	private $controllerArticle;
	private $controllerUser;
	private $error;

	public function __construct()
	{
		$this->controllerApp = new ControllerApp();
		$this->controllerArticle = new ControllerArticle();
		$this->controllerUser = new controllerUser();
		$this->controllerAdmin = new controllerAdmin();
		$this->error = new Error404();
	}

	public function routeReq()
	{
		 try{
				$url = '';
				if(isset($_GET['url']))
				{	
					$url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

					if(session_status() == PHP_SESSION_NONE)
			    	{
			   			session_start();
					}

					if(isset($url[1]))
					{
						$this->error->errorPage();
					}				

			        if($url[0] === 'articles')
			        {
			            $this->controllerArticle->getAllPosts();
			        }
			        elseif($url[0] === 'article')
			        {
			        	$this->controllerArticle->getUniquePost();
			        }
			        elseif($url[0] === 'deconnexion')
			        {
			            $this->controllerUser->deconnexion();
			        }
			        elseif($url[0] === 'connexion')
			        {
			            $this->controllerUser->connexion();
			        }
			        elseif($url[0] === 'register')
			        {
			            $this->controllerUser->register();
			        }
			        elseif($url[0] === 'accueil')
			        {
			            $this->controllerApp->home();
			        }
			        elseif($url[0] === 'admin')
			        {
			            $this->controllerAdmin->admin();
			        }
		  		}
		  	$this->error->errorPage();	
		  	}	
			catch(Exception $e)
			{
	  		 	echo 'Erreur : ' . $e->getMessage();
			}
	}
}
