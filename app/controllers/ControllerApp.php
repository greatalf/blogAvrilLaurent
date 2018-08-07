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
		if(isset($_SESSION['auth']))
		{
			FLASH::setFlash('Vous êtes connecté ' . $_SESSION['username'] . '...', 'success');
			header('Location: admin');
			exit();
		}
		
		if(isset($_POST['connect_submit']))
		{
			if(empty($_POST['connect_email']))
			{			
				FLASH::setFlash('Veuillez remplir tous les champs correctement!');
				header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/passForgotten');
				exit();	
			}
			
			$this->_security->noAccessBecauseBruteForce();
			sleep(1);

			$emailExist = $this->_usersManager->checkMailExistance();
			if($emailExist->id() != NULL)
			{
				$_SESSION['id'] = $emailExist->id();
				$_SESSION['username'] = $emailExist->username();
				$_SESSION['tokenCsrf'] = md5(time()*rand(1,1000));

				$this->_mail->forgetPassMail();

			}
			else
			{
				FLASH::setFlash('L\'adresse email n\'existe pas.');
				$this->_view = new View('forgottenPass');
				$this->_view->generate(NULL);
				exit();
			}
		FLASH::setFlash('Un email de reset de votre mot de passe vous a été envoyé.', 'success');			
		}
	$this->resetPass();

	$this->_view = new View('forgottenPass');		
	$this->_view->generate(NULL);
	exit();	
	}

	public function resetPass()
	{
		if(isset($_GET['user_id']) && isset($_GET['tokenCsrf']))
		{
			if($_GET['tokenCsrf'] != $_SESSION['tokenCsrf'])
			{
				FLASH::setFlash('Le lien est obsolète!');
				$this->_view = new View('forgottenPass');		
				$this->_view->generate(NULL);
				exit();
			}
			if(isset($_POST['forget_password']))
			{
				if(empty($_POST['pass1']) || empty($_POST['pass2']))
				{
					FLASH::setFlash('Remplissez tous les champs correctement!');
					$this->_view = new View('resetPass');	
					$this->_view->generate(NULL);
					exit();
				}
				if(htmlspecialchars($_POST['pass1']) != htmlspecialchars($_POST['pass2']))
				{
					FLASH::setFlash('Les mots de passe doivent être identiques!');
					header('Refresh:0');
					exit();
				}	
				else
				{
					$userRes = $this->_usersManager->getUserInfos($_GET['user_id']);

					$this->_user = new Users
					([
						'id' => $userRes->id(),
						'lastname' => $userRes->lastname(),
						'firstname' => $userRes->firstname(),
						'email' => $userRes->email(),
						'username' => $userRes->username(),
						'password' => htmlspecialchars($_POST['pass1']),
						'rank' => $userRes->rank(),
						'confirmedAt' => $userRes->confirmedAt()
					]);
					$this->_usersManager->update($this->_user);
				}				
				if($_GET['user_id'] != $_SESSION['id'])
				{
					FLASH::setFlash('Utilisateur erroné!');
					header('Location: connexion');
					exit();							
				}				

				FLASH::setFlash('Votre mot de passe a été mis à jour.', 'success');
				header('Location: connexion');
				exit();
			}	
		$this->_view = new View('resetPass');	
		$this->_view->generate(NULL);
		exit();		
		}
	}
}
