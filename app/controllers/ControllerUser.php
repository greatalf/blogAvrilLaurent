<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Users;
use \Laurent\App\Models\Model;
use \Laurent\App\Views\View;
use Laurent\App\Service\Mail;
use Laurent\App\Service\Flash;
use Laurent\App\Service\Security;
use Laurent\App\Service\Profile;
use Laurent\App\Controllers\ControllerAdmin;

class ControllerUser extends ControllerMain
{
	public function __construct()
	{ 	
		parent::__construct();
		$this->_mail = new Mail();
	}

	public function deconnexion()
	{	
		if(!isset($_SESSION['auth']))
		{
			setcookie("deco", $deco = 'Vous êtes déjà déconnecté......', time()+(2));
			header('Location:connexion');
			exit();
		}
		setcookie("deco", $deco = 'Vous êtes déconnecté.', time()+(2));
		session_destroy();
		header('Location:connexion');
		exit();
	}

	public function connexion()
	{
		if(isset($_SESSION['auth']))
		{
			FLASH::setFlash('Vous êtes déjà connecté ' . $_SESSION['username'] . '...', 'success');
			header('Location: admin');
			exit();
		}
		if(isset($_POST['connect_email']) && isset($_POST['connect_pass']))
		{
			if(isset($_POST['connect_submit']))
			{
				$this->_security->noAccessBecauseBruteForce();
				sleep(1);

				$this->_usersManager->makeConnexionOfUser();

				if(isset($_SESSION['auth']))
				{
					FLASH::setFlash('Bienvenue, ' . $_SESSION['username'] . ' !', 'success');
					header('Location: admin');
					exit();
				}
				$this->isThereBruteForce(5/*nbAttempt*/, 45/*timeFreeze*/);
				
				FLASH::setFlash('L\'adresse email et le mot de passe ne correspondent pas!');
				header('Location: connexion');
				exit();
			}
		}		
	$this->_view = new View('connexion');
	$this->_view->generate(NULL);
	exit();
	}	

	public function isThereBruteForce($nbAttempt, $timeFreeze)
	{
		$this->_usersManager->addIp($this->_security->getIp());

		$bruteForceTest = $this->_usersManager->bruteForceTest($this->_security->getIp());

		if($bruteForceTest >= $nbAttempt)
		{
			FLASH::setFlash('Vous avez fait <strong>' . $nbAttempt . ' tentatives</strong> de connexion échouées!');

			setcookie("BRUTEFORCE", $bruteForce = 'Réessayez ultérieurement.', time()+($timeFreeze));

			$this->_security->noAccessBecauseBruteForce();

			$this->_usersManager->deleteIp($this->_security->getIp());
			header('Location: connexion');
			exit();
		}
	}

	public function checkUniqueEmailAndPseudo()
	{
		$this->_controllerAdmin = new ControllerAdmin;
		unset($countEmail);
		$countEmail = $this->_usersManager->checkEmail();
		var_dump($countEmail); die();
		if($countEmail > 0)
		{
			FLASH::setFlash('Cette adresse Email est déjà utilisée.');
			if(isset($_POST['profil_email']))
			{	
				$this->_controllerAdmin->renderViewAdmin();		
			}
			$this->_view = new View('register');
			$this->_view->generate(NULL);
			exit();
		}

		unset($countPseudo);
		$countPseudo = $this->_usersManager->checkPseudo();
		if($countPseudo > 0)
		{
			FLASH::setFlash('Ce pseudo est déjà utilisé.');
			if(isset($_POST['profil_username']))
			{
				$this->_controllerAdmin->renderViewAdmin();				
			}
				$this->_view = new View('register');
				$this->_view->generate(NULL);
				exit();
		}
	}

	public function checkUniqueEmail()
	{
		$this->_controllerAdmin = new ControllerAdmin;
		unset($countEmail);
		$countEmail = $this->_usersManager->checkEmail();
		if($countEmail > 0)
		{
			FLASH::setFlash('Cette adresse Email est déjà utilisée.');
			if(isset($_POST['profil_email']))
			{	
				$this->_controllerAdmin->renderViewAdmin();		
			}
			$this->_view = new View('register');
			$this->_view->generate(NULL);
			exit();
		}
	}

	public function checkUniquePseudo()
	{
		$this->_controllerAdmin = new ControllerAdmin;
		unset($countPseudo);
		$countPseudo = $this->_usersManager->checkPseudo();
		if($countPseudo > 0)
		{
			FLASH::setFlash('Ce pseudo est déjà utilisé.');
			if(isset($_POST['profil_username']))
			{
				$this->_controllerAdmin->renderViewAdmin();				
			}
				$this->_view = new View('register');
				$this->_view->generate(NULL);
				exit();
		}
	}

	public function addUser()
	{
		if(session_status() == PHP_SESSION_NONE)
    	{
   			session_start();
		}
		$_SESSION['newUser'] = $this->_usersManager->add($this->_user);

		var_dump($_SESSION['newUser']); die();

		if($_SESSION['newUser'] != false && !isset($_SESSION['auth']))
		{
			$this->_mail->sendMail();
		}
	}

	public function register()
	{	
		$this->_profile = new Profile();
		$this->_profile->registerProfile();
	}
}
