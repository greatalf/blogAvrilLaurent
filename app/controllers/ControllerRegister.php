<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Model;
use Laurent\App\Views\View;


require_once 'app/views/View.php';
require_once 'app/Session.php';
require_once 'app/models/Model.php';

class ControllerRegister
{
	private $_view,
			$_user,
			$_usersManager;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
	}

	public function register()
	{	
		$lastname = isset($_POST['regist_lastname']) ? htmlspecialchars($_POST['regist_lastname']) : '';
		$firstname = isset($_POST['regist_firstname']) ? htmlspecialchars($_POST['regist_firstname']) : '';
		$email = isset($_POST['regist_email']) ? htmlspecialchars($_POST['regist_email']) : '';
		$username = isset($_POST['regist_username']) ? htmlspecialchars($_POST['regist_username']) : '';
		$password = isset($_POST['regist_password']) ? htmlspecialchars($_POST['regist_password']) : '';
		$confirmPassword = isset($_POST['regist_password_confirm']) ? htmlspecialchars($_POST['regist_password_confirm']) : '';

		if(!empty($lastname) &&
			!empty($firstname) && 
			!empty($email) && 
			!empty($username) && 
			!empty($password) && 
			!empty($confirmPassword))
		{	
			if($password != $confirmPassword)
			{
				SESSION::setFlash('Le mot de passe et la confirmation de mot de passe ne correspondent pas!');

				$this->_view = new View('Register');
				$this->_view->generate(NULL);

				exit();				
			}

			$db = DBFactory::getConnexionPDO();
			require_once 'app/models/UsersManager.php';
			$this->_usersManager = new UsersManager($db);

			//hydratation de l'user nouvellement inscrit
			$this->_user = new Users
			([
				'lastname' => $_POST['regist_lastname'],
				'firstname' => $_POST['regist_firstname'], 
				'email' => $_POST['regist_email'], 
				'username' => $_POST['regist_username'], 
				'password' => $_POST['regist_password'], 
				'timeToDelete' => time()
			]);

			$newUser = $this->_usersManager->add($this->_user);

			if($newUser != false && !isset($_SESSION['auth']))
			{
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
							Bonjour ' . $_POST['regist_username'] . ', pour valider votre inscription, merci de cliquer sur le lien suivant <a href="http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  $newUser . '&confirmation_token=' . $_SESSION['token'] . '">http://localhost/Blog_Avril_Laurent/confirm&user_id=' .  $newUser . '&confirmation_token=' . $_SESSION['token'] . '</a>
						</body>
					</html>';

				$mail = mail($to, $subject, $message, $header);
				if(!$mail)
				{
					SESSION::setFlash('L\'inscription a échoué. Vérifiez votre connexion et réessayer ultérieurement.');
					header('Location:register');
					exit();
				}

				SESSION::setFlash('Un mail de confirmation vient de vous être envoyé.', 'success');
				header('Location:connexion');
				exit();
			}
			
			if(isset($_SESSION['auth']))
			{
				header('Location:articles');
				exit();
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
