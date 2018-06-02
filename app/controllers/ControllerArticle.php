<?php
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerArticle
{
	private $_postsManager,
			$_commentsManager,
			$_usersManager,
			$_view;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->post(); 
		}
	}

	public function post()
	{
		$db = DBFactory::getConnexionPDO();
		$id = htmlspecialchars($_GET['id']);

		require_once 'app/models/PostsManager.php';
		require_once 'app/models/CommentsManager.php';
		require_once 'app/models/UsersManager.php';

		$this->_postsManager = new PostsManager($db);
		$this->_commentsManager = new CommentsManager($db);
		$this->_usersManager = new UsersManager($db);

		if(isset($id))
		{
			$post = $this->_postsManager->getUnique($id);
			$comments = $this->_commentsManager->getListComments($id);
			$user = $this->_usersManager->checkUser();
		}
		else
		{
			header('Location:articles');
			exit();
		}

		// Si utilisateur connecté
		if(isset($_SESSION['auth']))
		{
			if(!empty($_POST['com_message']) && isset($_POST['com_submit']))
			{
				$this->_commentsManager->add($id);
				SESSION::setFlash('Votre commentaire a bien été envoyé.', 'success');
				header('Refresh:0, url=article&id=' . $id);
				exit();
			}
			elseif(empty($_POST['com_message']) && isset($_POST['com_submit']))
			{
				SESSION::setFlash('Veuillez remplir le champs "Message" correctement.');
				header('Location: article&id=' . $id);
				exit();
			}
		}
		//if user connected
		if(!isset($_SESSION['auth']) && isset($_POST['com_submit']))
		{
			//Si les champs sont complétés
			if(!empty($_POST['connect_email']) && !empty($_POST['connect_pass']) && !empty($_POST['com_message']))
			{
				//Si l'user existe bien et qu'il n'est pas connecté
				if($user != false)
				{
					//Initialisation des datas
					$_SESSION['auth'] = 1;
					$_SESSION['id'] = $user->id();
					$_SESSION['lastname'] = $user->lastname();
					$_SESSION['firstname'] = $user->firstname();
					$_SESSION['email'] = $user->email();
					$_SESSION['username'] = $user->username();
					$_SESSION['password'] = $user->password();
					$_SESSION['rank'] = $user->rank();

					//Ajout du commentaire en BDD
					$this->_commentsManager->add($id);

					//Affichage du message cookie flash
					SESSION::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
					
					header('Refresh:0, url=article&id=' . $id);
					exit();
				}
				else
				{
					SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
					header('Refresh:0, url=article&id=' . $id);
					exit();
				}
			}
			else
			{
				SESSION::setFlash('Veuillez remplir tous les champs correctement !');
				header('Refresh:0, url=article&id=' . $id);
				exit();
			}
		}	

		//affichage des bouttons en fonction du rank
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] == 2)
			{
				if(isset($_GET['delete']))
				{
					$this->_commentsManager->delete((int) $_GET['delete']);
					//Affichage du message cookie flash
					SESSION::setFlash('Le commentaire a bien été supprimé.', 'success');

					header('Refresh:0, url=article&id=' . $id);
					exit();
				}
			}

			if($_SESSION['rank'] > 0)
			{
				if(isset($_GET['update']))
				{
					$commentUpdate = $this->_commentsManager->getUniqueComment($_GET['update']);
					// var_dump($commentUpdate);
					// die();

					if($commentUpdate && isset($_POST['com_update']))
					{
						$this->_commentsManager->update($_GET['update']);

						SESSION::setFlash('Le commentaire a bien été mis jour.', 'success');
						header('Refresh:0, url=article&id=' . $id);
						exit();
					}
					elseif(!$commentUpdate)
					{
						Session::setFlash('Le commentaire demandé est inexistant !');
            			header('Location:articles');
            			exit();
					}
				}
			}
		}
		if(!headers_sent())
		{
			$this->_view = new View('Article');

			if(isset($commentUpdate))
			{
				$this->_view->generate(array('post' => $post, 'comments' => $comments, 'user' => $user, 'comment' => $commentUpdate));				
			}
			else
			{
				$this->_view->generate(array('post' => $post, 'comments' => $comments, 'user' => $user));
			}
			exit();			
		}
	}
}
