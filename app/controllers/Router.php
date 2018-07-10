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
		$this->controllerUser = new ControllerUser();
		$this->controllerAdmin = new ControllerAdmin();
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
						var_dump($url[1]);
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
			    	elseif($url[0] === 'postdelete')
			    	{		
						$this->controllerAdmin->postDelete();
			    	}
			    	elseif($url[0] === 'postupdate')
			    	{		
						$this->controllerAdmin->postUpdate();
			    	}
			    	elseif($url[0] === 'commentupdate')
			    	{		
						$this->controllerArticle->commentUpdate();
			    	}
			    	elseif($url[0] === 'commentdelete')
			    	{		
						$this->controllerArticle->commentDelete();
			    	}
			    	elseif($url[0] === 'commentvalidate')
			    	{		
						$this->controllerAdmin->commentValidate();
			    	}
			    	elseif($url[0] === 'commentnovalidate')
			    	{		
						$this->controllerAdmin->commentNoValidate();
			    	}
			    	elseif($url[0] === 'userbanish')
			    	{		
						$this->controllerAdmin->userBanish();
			    	}
			    	elseif($url[0] === 'upgradeuser')
			    	{		
						$this->controllerAdmin->upgradeUser();
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
