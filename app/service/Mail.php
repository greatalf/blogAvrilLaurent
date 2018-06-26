<?php
namespace Laurent\App\Service;

use \Laurent\App\Views\View;
use Laurent\App\Service\Flash;

class Mail
{
	private $_view;
	
	public function sendMail()
	{		
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
							Bonjour ' . $_POST['regist_username'] . ', pour valider votre inscription au blog de Laurent AVRIL, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  $GLOBALS['newUser'] . '&confirmation_token=' . $_SESSION['token'] . '">je confirme mon inscription</a>
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
		$this->_view = new View('Connexion');
		$this->_view->generate(NULL);
		exit();
		// $this->_view = new View('Connexion');
		// $this->_view->generate(NULL);
	}	
}
