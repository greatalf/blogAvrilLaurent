<?php
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerRegister
{
	private $_view,
			$_usersManager;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->register(); 
		}
	}

	public function register()
	{	
		if(!empty($_POST['regist_lastname']) &&
			!empty($_POST['regist_firstname']) && 
			!empty($_POST['regist_email']) && 
			!empty($_POST['regist_username']) && 
			!empty($_POST['regist_password']) && 
			!empty($_POST['regist_rank']))
		{	
			$db = DBFactory::getConnexionPDO();
			require_once 'app/models/UsersManager.php';
			$this->_usersManager = new UsersManager($db);

			$newUser = $this->_usersManager->add();

			if($newUser != false && !isset($_SESSION['auth']))
			{
				//envoyer mail de confirmation au nouveau membre
			/////////////////////////////////
			////////ENVOIE DU MAIL///////////
			/////////////////////////////////
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
						Bonjour ' . $_POST['regist_username'] . ', pour valider votre inscription, merci de cliquer sur le lien suivant http://localhost/Blog_Avril_Laurent/confirm&id=' .  $newUser . '&confirmation_token=' . $_SESSION['token'] . '
					</body>
				</html>';

			$mail = mail($to, $subject, $message, $header);

			SESSION::setFlash('Un mail de confirmation vient de vous être envoyé.', 'success');
			header('Location:connexion');
			}
			elseif(isset($_SESSION['auth']))
			{
				header('Location:articles');
			}
			else
			{
				SESSION::setFlash('Les champs sont mal remplis!');
				$this->_view = new View('Register');
				$this->_view->generate(NULL);
			}
		}
		else
		{
			// SESSION::setFlash('Remplir tous les champs', 'warning');
			$this->_view = new View('Register');
			$this->_view->generate(NULL);
		}


	}	
}
