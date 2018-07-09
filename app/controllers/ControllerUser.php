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
		setcookie("deco", $deco = 'Vous avez bien été déconnecté.', time()+(2));
		session_destroy();
		header('Location:connexion');
		exit();
	}

	public function connexion()
	{
		if(isset($_POST['connect_email']) && isset($_POST['connect_pass']))
		{
			if(isset($_POST['connect_submit']))
			{				
				$user = $this->_usersManager->checkUser();
				if($user->id() != NULL && !isset($_SESSION['auth']))
				{
					// Initialisation des datas
					$_SESSION['auth'] = 1;
					$_SESSION['id'] = $user->id();
					$_SESSION['lastname'] = $user->lastname();
					$_SESSION['firstname'] = $user->firstname();
					$_SESSION['email'] = $user->email();
					$_SESSION['username'] = $user->username();
					$_SESSION['password'] = $user->password();
					$_SESSION['rank'] = $user->rank();

					$_SESSION['tokenCsrf'] = md5(time()*rand(1,1000));

					FLASH::setFlash('Bienvenue, ' . $_SESSION['username'] . ' !', 'success');
					header('Location: articles');
					exit();
				}
				else
				{
					FLASH::setFlash('L\'adresse email et le mot de passe ne correspondent pas!');
					header('Location: connexion');
					exit();
				}
			}
		}
	$this->_view = new View('connexion');
	$this->_view->generate(NULL);
	exit();
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
			die('pseudo compté');
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
		// var_dump($countEmail); die();
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

	// public function sendMail()
	// {		
	// 	$header="MIME-Version: 1.0\r\n";
	// 	$header.="From:support@avril-laurent.fr"."\n";
	// 	$header.='Content-Type:text/html; charset="uft-8"'."\n";
	// 	$header.="Content-Transfer-Encoding: 8bit";

	// 	$to = $_POST['regist_email'];
	// 	$subject = 'Confirmation de votre inscription';
	// 	$message = 
	// 		'<html>
	// 			<header>
	// 				<h1>Confirmation de votre inscription</h1>
	// 					</header>
	// 					<body>
	// 						Bonjour ' . $_POST['regist_username'] . ', pour valider votre inscription au blog de Laurent AVRIL, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  $GLOBALS['newUser'] . '&confirmation_token=' . $_SESSION['token'] . '">je confirme mon inscription</a>
	// 			</body>
	// 		</html>';

	// 	$mail = mail($to, $subject, $message, $header);
	// 	if(!$mail)
	// 	{
	// 		FLASH::setFlash('L\'inscription a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
	// 		$this->_view = new View('Register');
	// 		$this->_view->generate(NULL);
	// 		exit();
	// 	}

	// 	FLASH::setFlash('Un mail de confirmation vient de vous être envoyé.', 'success');
	// 	$this->_view = new View('Connexion');
	// 	$this->_view->generate(NULL);
	// 	exit();
		// $this->_view = new View('Connexion');
		// $this->_view->generate(NULL);
	// }



			
		// 	if(isset($_SESSION['auth']))
		// 	{
		// 		header('Location:articles');
		// 		exit();
		// 	}
		// 	else
		// 	{
		// 		FLASH::setFlash('Les champs sont mal remplis!');
		// 		$this->_view = new View('Register');
		// 		$this->_view->generate(NULL);
		// 	}
		// }
		// else
		// {
		// 	// FLASH::setFlash('Remplir tous les champs', 'warning');
		// 	$this->_view = new View('Register');
		// 	$this->_view->generate(NULL);
		// }
}
