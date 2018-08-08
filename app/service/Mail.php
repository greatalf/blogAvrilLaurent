<?php
namespace Laurent\App\Service;

use \Laurent\App\Views\View;
use Laurent\App\Service\Flash;
use Laurent\App\Service\Profile;
use Laurent\App\Controllers\ControllerAdmin;

class Mail
{
	private $_view,
			$_profile;
	
	public function sendMailUserAdded()
	{		
		$header="MIME-Version: 1.0\r\n";
		$header.="From:support@avril-laurent.fr"."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = htmlspecialchars($_POST['regist_email']);
		$subject = 'Confirmation de votre inscription';
		$message = 
			'<html>
				<header>
					<h1>Confirmation de votre inscription</h1>
				</header>
				<body>
					Bonjour ' . ucfirst(htmlspecialchars($_POST['regist_username'])) . ', pour valider votre inscription au blog de Laurent AVRIL, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  htmlspecialchars($_SESSION['newUser']) . '&confirmation_token=' . htmlspecialchars($_SESSION['token']) . '">je confirme mon inscription</a>
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

	public function sendMailUserUpdated()
	{
		$header="MIME-Version: 1.0\r\n";
		$header.="From:support@avril-laurent.fr"."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = htmlspecialchars($_POST['profil_email']);
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
		$this->_controllerAdmin = new ControllerAdmin;
		$this->_controllerAdmin->renderViewAdmin();
		exit();
	}

	public function contactMail()
	{
		$header="MIME-Version: 1.0\r\n";
		$header.="From:" . htmlspecialchars($_POST['email']) . ""."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = 'avril.laurent974@yahoo.fr';
		$subject = 'Message de ' . htmlspecialchars($_POST['email']);
		$message = 
			'<html>
				<header>
					<h1>Bonjour Laurent, vous avez reçu un message!
					</h1>
					<p>Il est de la part de ' . strtoupper(htmlspecialchars($_POST['name'])) . ' ' . ucfirst(htmlspecialchars($_POST['lastname'])) . '.</p>
				</header>
				<body>
					Cette personne est joignable à l\'adresse email suivante : ' . htmlspecialchars($_POST['email']) . '. <br>
					Voici son message : ' . htmlspecialchars($_POST['message']) . '
				</body>
			</html>';

		$mail = mail($to, $subject, $message, $header);
		if(!$mail)
		{
			FLASH::setFlash('L\'envoi du message a échoué. Vérifiez votre connexion et réessayez ultérieurement.');
			header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/accueil#contact');
			exit();
		}

		FLASH::setFlash('Votre message a bien été envoyé à l\'administrateur.', 'success');
		header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/accueil#contact');
		exit();
	}

	public function forgetPassMail()
	{
		$header="MIME-Version: 1.0\r\n";
		$header.="From:support@avril-laurent.fr"."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.="Content-Transfer-Encoding: 8bit";

		$to = htmlspecialchars($_POST['connect_email']);
		$subject = 'Reset de votre mot de passe';
		$message = 
			'<html>
				<header>
					<h1>Confirmation de votre inscription</h1>
				</header>
				<body>
					Bonjour ' . htmlspecialchars($_SESSION['username']) . ', pour réinitialiser votre mot de passe, accédez au lien suivant : <a href="http://localhost/Blog_Avril_Laurent/resetPass&user_id=' .  htmlspecialchars($_SESSION['id']) . '&tokenCsrf=' . $_SESSION['tokenCsrf'] . '">Changer de mot de passe</a>
				</body>
			</html>';

		$mail = mail($to, $subject, $message, $header);
		if(!$mail)
		{
			FLASH::setFlash('L\'envoi a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
			$this->_view = new View('passForgotten');
			$this->_view->generate(NULL);
			exit();
		}
	}
}
