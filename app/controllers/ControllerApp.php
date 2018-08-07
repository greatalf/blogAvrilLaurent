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
				FLASH::setFlash('Veuillez remplir tous les champs correctement!');
				header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/accueil#contact');
				exit();	
			}
			$this->_mail->contactMail();
		}
	}

	public function confirm()
	{	
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

	public function forgetPass()
	{
		if(isset($_POST['connect_submit']))
		{
			if(empty($_POST['connect_email']))
			{			
				FLASH::setFlash('Veuillez remplir tous les champs correctement!');
				header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/passForgotten');
				exit();	
			}
			if(isset($_SESSION['auth']))
			{
				FLASH::setFlash('Vous êtes déjà connecté ' . $_SESSION['username'] . '...', 'success');
				header('Location: admin');
				exit();
			}
			$this->_security->noAccessBecauseBruteForce();
			sleep(1);
			$this->_security->securizationCsrf();

			$emailExist = $this->_usersManager->checkMailExistance();
			if($emailExist->id() != NULL)
			{
				$_SESSION['id'] = $emailExist->id();
				$_SESSION['username'] = $emailExist->username();
				setcookie('token', md5(time()*rand(1,1000)), time()+1800);

				$this->_mail->forgetPassMail();
			}
			else
			{
				FLASH::setFlash('L\'adresse email n\'existe pas.');
				$this->_view = new View('forgottenPass');
				$this->_view->generate(NULL);
				exit();
			}
		FLASH::setFlash('Un email de changement de mot de passe vous a été envoyé. Attention il est valide 30 minutes', 'success');
		$this->_view = new View('connexion');
		$this->_view->generate(NULL);
		exit();	
		}
	}
}
