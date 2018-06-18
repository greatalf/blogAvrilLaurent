<?php
session_start();
require_once 'app/views/View.php';

class ControllerAdmin
{
	private $_view;

	public function __construct()
	{ 	
		if(isset($url) && count($url) > 1)
		{
			throw new \Exception('Page Introuvable');
		}
		else
		{
			$this->admin(); 
		}
	}

	public function admin()
	{
		if(isset($_SESSION['auth']))
		{
			$db = DBFactory::getConnexionPDO();
			require_once 'app/models/PostsManager.php';
			require_once 'app/models/UsersManager.php';

			$this->_usersManager = new UsersManager($db);
			$userInfos = $this->_usersManager->getUserInfos($_SESSION['id']);

			// if($_SESSION['rank'] == 2 || $_SESSION['rank'] <= 1)
			// {
				//UPDATE BOUTTON
			// 	$_SESSION['boutton_update'] = '<button type="submit" class="btn btn-info btn-sm">Modifier</button>';
			// }

			if($_SESSION['rank'] == 2)
			{
				$this->_postsManager = new PostsManager($db);
				$manager = $this->_postsManager->getList(0,25);
				$this->_view = new View('Admin');
				$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos));
			}
			else
			{
				$this->_postsManager = new PostsManager($db);
				$manager = $this->_postsManager->getMyList(0,10);

				$this->_view = new View('Admin');
				if($manager != NULL)
				{
					$this->_view->generate(array('manager' => $manager, 'userInfos' => $userInfos));	
				}
				else
				{
					$this->_view->generate(array('userInfos' => $userInfos));
				}
			}
		}
		else
		{
			setcookie("denied", $denied = 'Vous n\'êtes pas autorisé à accéder à cette page', time()+(3));
			header('Location:connexion');
		}
	}
}
