<?php
namespace Laurent\App\Service;

use \Laurent\App\Views\View;
use Laurent\App\Service\Flash;
use Laurent\App\Service\Profile;

class Mail
{
	private $_view,
			$_profile;
	
	public function sendMailUserAdded()
	{		
		// if(session_status() == PHP_SESSION_NONE)
  //   	{
  //  			session_start();
		// }

		var_dump($_SESSION);

		$header="MIME-Version: 1.0\r\n";
		$header.="From:support@avril-laurent.fr"."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = $_POST['regist_email'];
		$subject = 'Confirmation de votre inscription';
		$message = 
			'<html>
				<header>
					<h1>Confirmation de votre inscription</h1>
						</header>
						<body>
							Bonjour ' . htmlspecialchars($_POST['regist_username']) . ', pour valider votre inscription au blog de Laurent AVRIL, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  htmlspecialchars($_SESSION['newUser']) . '&confirmation_token=' . htmlspecialchars($_SESSION['token']) . '">je confirme mon inscription</a>
				</body>
			</html>';

		$mail = mail($to, $subject, $message, $header);
		if(!$mail)
		{
			FLASH::setFlash('L\'inscription a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
			$this->_view = new View('Register');
			$this->_view->generate(NULL);
			exit();
		}

		FLASH::setFlash('Un mail de confirmation vient de vous être envoyé.', 'success');
		header('Location: connexion');
		exit();
	}	

	// public function sendMailUserAdded()
	// {
	// 	$header="MIME-Version: 1.0\r\n";
	// 	$header.="From:support@avril-laurent.fr"."\n";
	// 	$header.='Content-Type:text/html; charset="uft-8"'."\n";
	// 	$header.="Content-Transfer-Encoding: 8bit";

	// 	$to = $_POST['regist_email'];
	// 	$subject = 'Modification de votre profil';
	// 	$message = 
	// 		'<html>
	// 			<header>
	// 				<h1>Modification de profil</h1>
	// 					</header>
	// 					<body>
	// 						Bonjour ' . htmlspecialchars($_POST['regist_username']) . ', pour valider votre inscription au blog de Laurent AVRIL, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  htmlspecialchars($GLOBALS['newUser']) . '&confirmation_token=' . htmlspecialchars($_SESSION['token']) . '">je confirme mon inscription</a>
	// 			</body>
	// 		</html>';

	// 	$mail = mail($to, $subject, $message, $header);
	// 	if(!$mail)
	// 	{
	// 		FLASH::setFlash('La modification de profil a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
	// 		$this->_view = new View('Admin');
	// 		$this->_view->generate(NULL);
	// 		exit();
	// 	}

	// 	FLASH::setFlash('Un mail de modification de votre profil vient de vous être envoyé.', 'success');
	// 	$this->_view = new View('Admin');			
	// 	$this->_view->generate(NULL);
	// 	exit();
	// }

	public function sendMailUserUpdated()
	{
		$header="MIME-Version: 1.0\r\n";
		$header.="From:support@avril-laurent.fr"."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = $_POST['profil_email'];
		$subject = 'Modification de votre profil';
		$message = 
			'<html>
				<header>
					<h1>Modification de profil</h1>
						</header>
						<body>
							Bonjour ' . htmlspecialchars($_POST['profil_username']) . ', votre profil a bien été mis à jour.
				</body>
			</html>';

		$mail = mail($to, $subject, $message, $header);
		if(!$mail)
		{
			FLASH::setFlash('La modification de profil a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
			$this->_view = new View('Admin');
			$this->_view->generate(NULL);
			exit();
		}

		FLASH::setFlash('Un mail de modification de votre profil vient de vous être envoyé.', 'success');
		$this->_profile = new Profile;
		$this->_profile->renderViewAdmin();
		exit();
	}
}
