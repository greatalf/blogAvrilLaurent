<?php
session_start();
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerContact
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
			$this->contact(); 
		}
	}

	public function contact()
	{	
		if((isset($_POST['name']) && (isset($_POST['lastname']) && (isset($_POST['email']) && (isset($_POST['message']))))))
		{

			$name = (htmlspecialchars($_POST['name'], ENT_QUOTES));
			$lastname = (htmlspecialchars($_POST['lastname'], ENT_QUOTES)); 
			$email = (htmlspecialchars($_POST['email'], ENT_QUOTES)); 
			$message = nl2br(htmlspecialchars($_POST['message'], ENT_QUOTES));

			if(!empty($name) &&
				!empty($lastname) && 
				!empty($email) && 
				!empty($message))
			{				
				if(strlen($name) <= 50 && 
					strlen($lastname) <= 50)
				{
					/////////////////////////////////
					////////ENVOIE DU MAIL///////////
					/////////////////////////////////
					$header="MIME-Version: 1.0\r\n";
					$header.="From:" . $_POST['email'] . ""."\n";
					$header.='Content-Type:text/html; charset="uft-8"'."\n";
					$header.="Content-Transfer-Encoding: 8bit";

					$to = 'contact@avril-laurent.fr';
					$subject = 'Confirmation de votre inscription';
					$message = 
						'<html>
							<header>
								<h1>Message de ' . strtoupper($name) . ' ' . ucfirst(strtolower($lastname)) . '</h1>
							</header>
							<body>
								Bonjour,<br>
								' . strtoupper($name) . ' ' . ucfirst(strtolower($lastname)) . ' vous a envoyé un mail depuis son adresse : ' . $email . '<br>
								Voici son message : ' . $message . '.
							</body>
						</html>';

					if($mail = mail($to, $subject, $message, $header) == true)
					{
						SESSION::setFlash('Votre message a bien été posté.<br>', 'success');
						header('Location:http://localhost/Blog_Avril_Laurent/#contact');
					}
					else
					{
						SESSION::setFlash('Votre message n\'a pas pu être posté.<br>Réessayez ultérieurement :/');
						header('Location:http://localhost/Blog_Avril_Laurent/#contact');	
					}

				}
			}
		}	
		else
		{
			SESSION::setFlash('Remplissez tous les champs correctement !');				
				header('Location:http://localhost/Blog_Avril_Laurent/#contact');
		}
	}	
}	
