<?php
require_once 'app/views/View.php';
session_start();

class ControllerArticles
{
	private $_postsManager,
			$_usersManager,
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
			$this->posts(); 
		}
	}

	public function posts()
	{
		$db = DBFactory::getConnexionPDO();

		require_once 'app/models/Posts.php';
		require_once 'app/models/PostsManager.php';
		require_once 'app/models/UsersManager.php';

		$this->_postsManager = new PostsManager($db);
		$this->_usersManager = new UsersManager($db);

		$posts = $this->_postsManager->getList(0, 5);
		$user = $this->_usersManager->checkUser();
		$this->_usersManager->deleteUsersNoConfirmed(60);
		
		$post = isset($_GET['post_update']) ? $this->_postsManager->getUnique($_GET['post_update']) : '';


		//Si user is not connected
		if(!isset($_SESSION['auth']) && isset($_POST['post_submit']))
		{
			//Si les champs sont complétés
			if(!empty($_POST['post_content'] && !empty($_POST['post_title'] && !empty($_POST['post_chapo']))))
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

					//HYDRATATION POUR ADD POST => USER NON CONNECTÉ:))
					$this->_post = new Posts
					([
						'author' => $_SESSION['username'],
						'title' => $_POST['post_title'],
						'chapo' => $_POST['post_chapo'],
						'content' => $_POST['post_content'],
						'addDate' => new \DateTime()
					]);

					//Ajout du commentaire en BDD
					$this->_postsManager->add($this->_post);

					//Affichage du message flash
					SESSION::setFlash('Merci ' . $user->username() . ', votre article a bien été envoyé.', 'success');
					
					header('Refresh:0, url=articles');
					exit();
				}
				else
				{
					SESSION::setFlash('Le mot de passe et l\'adresse email ne correspondent pas!');
					header('Refresh:0, url=articles');
					exit();
				}
			}
			else
			{
				SESSION::setFlash('Veuillez remplir tous les champs correctement !');
				header('Refresh:0, url=articles');
				exit();
			}
		}

		//Si user est déjà connecté
		if(isset($_SESSION['auth']) && isset($_POST['post_submit']))
		{
			//Si les champs sont complétés
			if(!empty($_POST['post_content'] && !empty($_POST['post_title'] && !empty($_POST['post_chapo']))))
			{
				//HYDRATATION :))
				$this->_post = new Posts
				([
					'author' => $_SESSION['username'],
					'title' => $_POST['post_title'],
					'chapo' => $_POST['post_chapo'],
					'content' => $_POST['post_content'],
					'addDate' => new \DateTime()
				]);

				$this->_postsManager->add($this->_post);
				SESSION::setFlash('Votre article a bien été posté.', 'success');

				header('Refresh:0, url=articles');
				exit();
			}			
			else
			{
				SESSION::setFlash('Veuillez remplir les champs correctement.');

				header('Refresh:0, url=articles');
				exit();
			}
		}		

		// Si utilisateur connecté pour update
		if(isset($_SESSION['auth']) && isset($_GET['post_update']))
		{
			if((int) $_GET['post_update'] == 0)
			{
				SESSION::setFlash('L\'article à modifier n\'est pas accessible');

				header('Refresh:0, url=articles');
				exit();
			}
			else
			{	
				if($post)
				{
					//hydratation pour inscrire dans les champs quand update
					$updatePost = new Posts
					([
						'id' => $_GET['post_update'],
						'author' => $post->author(),
						'title' => $post->title(),
						'chapo' => $post->chapo(),
						'content' =>$post->content()
					]);
					if(isset($_POST['post_update']))
					{
						if(!empty($_POST['post_author'] && !empty($_POST['post_content'] && !empty($_POST['post_title'] && !empty($_POST['post_chapo'])))))
						{

							//HYDRATATION POUR UPDATE :))
							$this->_post = new Posts
							([
								'author' => $_POST['post_author'],
								'title' => $_POST['post_title'],
								'chapo' => $_POST['post_chapo'],
								'content' =>$_POST['post_content'],
								'updateDate' => new \DateTime()
							]);
							
							$this->_postsManager->update($this->_post);
							SESSION::setFlash('L\'article a bien été mis à jour.', 'success');

							header('Refresh:0, url=articles');
							exit();
						}
						else
						{
							SESSION::setFlash('Veuillez remplir les champs correctement.');

							$this->_view = new View('Articles');			
							$this->_view->generate(array('post' => $post, 'posts' => $posts, 'user' => $user, 'updatePost' => $updatePost));
						}
					}
				}
				else
				{
					SESSION::setFlash('L\'article à modifier n\'existe pas!');

					header('Refresh:0, url=articles');
					exit();
				}	
			}
		}

		//DELETE EN TANT QUE SUPER ADMIN
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] == 2)
			{
				if(isset($_GET['post_delete']))
				{
					$post = $this->_postsManager->getUnique($_GET['post_delete']);

					//HYDRATATION POUR DELETE
					$this->_post = new Posts
					([
						'id' => $_GET['post_delete']
					]);

					$this->_postsManager->delete($this->_post);
					//Affichage du message msg flash
					SESSION::setFlash('L\'article a bien été supprimé.', 'success');

					header('Refresh:0, url=articles');
					exit();
				}
			}
		}	

		//affichage des bouttons en fonction du rank
		// if(isset($_SESSION['rank']))
		// {
		// 	if($_SESSION['rank'] == 2)
		// 	{
		// 		if(isset($_GET['post_delete']))
		// 		{
		// 			$post = $this->_postsManager->getUnique($_GET['post_delete']);

		// 			//HYDRATATION POUR DELETE
		// 			$this->_post = new Posts
		// 			([
		// 				'id' => $_GET['post_delete']
		// 			]);

		// 			$this->_postsManager->delete($this->_post);
		// 			//Affichage du message msg flash
		// 			SESSION::setFlash('L\'article a bien été supprimé.', 'success');

		// 			header('Refresh:0, url=articles');
		// 			exit();
		// 		}

		// 		if(isset($_POST['post_update_button']))
		// 		{
		// 				var_dump($this->_post);
		// 				die();
						
		// 			if(isset($_GET['post_update']))
		// 			{
		// 				$this->_post = new Posts
		// 				([
		// 					'id' => $_GET['post_update']
		// 				]);

		// 				$this->_postsManager->update($this->_post);
		// 				SESSION::setFlash('L\'article a bien été mis à jour.', 'success');
		// 				header('Refresh:0, url=articles');
		// 				exit();
		// 			}
		// 		}

			// }
			// if($_SESSION['rank'] == 1)
			// {				

			// 	$this->_post = new Posts;
			// 	$this->_post->setAuthor($_SESSION['username']);
			// 	// $this->_post->setId($post->id());
				
			// 	var_dump($this->_post);
			// 	foreach ($posts as $post)
			// 	{
			// 		$this->_controller = new Controller;
			// 		$boutton_update = $this->_controller->boutton_update($post->id());
			// 		var_dump($boutton_update);
			// 	}
			// }
		// }
		if(!headers_sent())
		{
			$this->_view = new View('Articles');

			if(isset($updatePost))
			{	
				$this->_view->generate(array('post' => $post, 'posts' => $posts, 'user' => $user, 'updatePost' => $updatePost));
			}
			else
			{
				$this->_view->generate(array('post' => $post, 'posts' => $posts, 'user' => $user));
			}			
		}
	}
}
