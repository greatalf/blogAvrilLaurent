<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Users;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;
use Laurent\App\Service\Flash;
use Laurent\App\Service\mail;
use Laurent\App\Service\Security;

class ControllerApp extends ControllerMain
{
	public function home()
	{	
		$this->_view = new View('Accueil');
		$this->_view->generate(NULL);
		exit();
	}	

	public function contact()
	{
		if(isset($_POST['contactButton']))
		{
			if(empty($_POST['name']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['message']))
			{			
				FLASH::setFlash('Veuillez remplir tous les champs  correctement!');
				header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/accueil#contact');
				exit();	
			}
			$this->_mail->contactMail();
		}
	}

	public function confirm()
	{	
		//confirm&user_id=195&confirmation_token=42DSu2pNxnS2Q2BothTSQ7$V6XN0JwUNFKqWyu5JOmratDR46BY0WA4Uz!2o
		if(isset($_GET['user_id']) && isset($_GET['confirmation_token']))
		{
			$user = $this->_usersManager->confirmUser();
			if($user->id() != NULL)
			{
				$this->_usersManager->userIsConfirmed();
				$this->_usersManager->makeConnexionOfUser(); 
				FLASH::setFlash('Bienvenue, '.$_SESSION['username'].' !', 'success');
				header('Location: articles');
				exit();
			}
			FLASH::setFlash('Le lien utilisé est obsolète!');
			header('Location: connexion');
			exit();
		}
	}
}
