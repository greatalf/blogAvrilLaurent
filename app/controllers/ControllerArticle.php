<?php
require_once 'app/views/View.php';
require_once 'app/Session.php';

class ControllerArticle
{
	private $_commentsManager,
			$_usersManager,
			$_comment,
			$_post,
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
		$post_id = htmlspecialchars($_GET['post_id']);

		require_once 'app/models/PostsManager.php';
		require_once 'app/models/CommentsManager.php';
		require_once 'app/models/UsersManager.php';

		$this->_postsManager = new PostsManager($db);
		$this->_commentsManager = new CommentsManager($db);
		$this->_usersManager = new UsersManager($db);

		$post = $this->_postsManager->getUnique($post_id);

		if(isset($post_id) && $post)
		{
			$comments = $this->_commentsManager->getListComments($post_id);
			$user = $this->_usersManager->checkUser();
		}
		else
		{
			SESSION::setFlash('L\'article demandé est inexistant!');
			header('Location:articles');
			exit();
		}

		// Si utilisateur connecté
		if(isset($_SESSION['auth']))
		{
			if(!empty($_POST['com_content']) && isset($_POST['com_submit']))
			{
				$this->_comment = new Comments
					([
						'author' => $_SESSION['username'],
						'content' => $_POST['com_content'],
						'addDate' => new \DateTime()
					]);

				$this->_commentsManager->add($this->_comment);

				SESSION::setFlash('Votre commentaire a bien été envoyé.', 'success');
				header('Refresh:0, url=article&post_id=' . $post_id);
				exit();
			}
			elseif(empty($_POST['com_content']) && isset($_POST['com_submit']))
			{
				SESSION::setFlash('Veuillez remplir le champs "Message" correctement.');
				header('Location: article&post_id=' . $post_id);
				exit();
			}
		}
		//if user diconnected
		if(!isset($_SESSION['auth']) && isset($_POST['com_submit']))
		{
			//Si les champs sont complétés
			if(!empty($_POST['com_content']) && isset($_POST['com_submit']))
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

					//Ajout du commentaire en BDD par hydratation
					$this->_comment = new Comments
					([
						'author' => $user->username(),
						'content' => $_POST['com_content'],
						'addDate' => new \DateTime()
					]);
					
					$this->_commentsManager->add($this->_comment);

					//Affichage du message cookie flash
					SESSION::setFlash('Merci ' . $_SESSION['username'] . ', votre commentaire a bien été envoyé.', 'success');
					
					header('Refresh:0, url=article&post_id=' . $post_id);
					exit();
				}
				else
				{
					SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
					header('Refresh:0, url=article&post_id=' . $post_id);
					exit();
				}
			}
			else
			{
				SESSION::setFlash('Veuillez remplir tous les champs correctement !');
				header('Refresh:0, url=article&post_id=' . $post_id);
				exit();
			}
		}	

		//affichage des bouttons en fonction du rank
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] == 2)
			{
				if(isset($_GET['comment_delete']))
				{
					$this->_comment = new Comments
						([
							'id' => (int)$_GET['comment_delete']
						]);	
					$this->_commentsManager->delete($this->_comment);
					//Affichage du message cookie flash
					SESSION::setFlash('Le commentaire a bien été supprimé.', 'success');

					header('Refresh:0, url=article&post_id=' . $post_id);
					exit();
				}
			}

			if($_SESSION['rank'] > 0)
			{
				if(isset($_GET['comment_update']))
				{
					$commentUpdate = $this->_commentsManager->getUniqueComment($_GET['comment_update']);
					if($commentUpdate && isset($_POST['com_update']))
					{
						$this->_comment = new Comments
						([
							'id' => (int)$_GET['comment_update']
						]);	
						$this->_commentsManager->update($this->_comment);

						SESSION::setFlash('Le commentaire a bien été mis jour.', 'success');
						header('Refresh:0, url=article&post_id=' . $post_id);
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
