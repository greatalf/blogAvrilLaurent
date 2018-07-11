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

					switch($url[0])
					{	
				        case 'articles':				        
				            $this->controllerArticle->getAllPosts();
				            break;				        
				        case 'article':
				        	$this->controllerArticle->getUniquePost();
				        	break;				        
				        case 'deconnexion':
				            $this->controllerUser->deconnexion();
				            break;				        
				        case 'connexion':
				            $this->controllerUser->connexion();
				            break;				        
				        case 'register':
				            $this->controllerUser->register();
				            break;				        
				        case 'accueil':
				            $this->controllerApp->home();
				            break;				        
				        case 'admin':
				            $this->controllerAdmin->admin();
				            break;				        
				    	case 'postdelete':		
							$this->controllerAdmin->postDelete();
							break;				    	
				    	case 'postupdate':		
							$this->controllerAdmin->postUpdate();
							break;				    	
				    	case 'commentupdate':		
							$this->controllerArticle->commentUpdate();
							break;				    	
				    	case 'commentdelete':		
							$this->controllerArticle->commentDelete();
							break;				    	
				    	case 'commentvalidate':		
							$this->controllerAdmin->commentValidate();
							break;				    	
				    	case 'commentnovalidate':		
							$this->controllerAdmin->commentNoValidate();
							break;				    	
				    	case 'userbanish':		
							$this->controllerAdmin->userBanish();
							break;				    	
				    	case 'upgradeuser':		
							$this->controllerAdmin->upgradeUser();
							break;	
						case 'contact':
							$this->controllerApp->contact();
							break;
						case 'confirm':
							$this->controllerApp->confirm();
							break;			    	
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
