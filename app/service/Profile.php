<?php
namespace Laurent\App\Service;

use Laurent\App\Views\View;
use Laurent\App\Models\Users;
use Laurent\App\Models\UsersManager;
use Laurent\App\Models\PostsManager;
use Laurent\App\Models\CommentsManager;
use Laurent\App\Models\Model;
use Laurent\App\Controllers\ControllerUser;
use Laurent\App\Controllers\ControllerAdmin;
use Laurent\App\Service\Flash;
use Laurent\App\Service\Security;

class Profile
{
	public function __construct()
	{
		$_db = (new Model())->dbConnect();
		$this->_db = $_db;
		// $this->_posts = new PostsManager($_db);
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
		$this->_security = new Security();
		$this->_mail = new Mail();
	}

	public function initVariables()
	{			
		$GLOBALS['lastname'] = isset($_POST['regist_lastname']) ? htmlspecialchars($_POST['regist_lastname']) : '';
		$GLOBALS['firstname'] = isset($_POST['regist_firstname']) ? htmlspecialchars($_POST['regist_firstname']) : '';
		$GLOBALS['email'] = isset($_POST['regist_email']) ? htmlspecialchars($_POST['regist_email']) : '';
		$GLOBALS['username'] = isset($_POST['regist_username']) ? htmlspecialchars($_POST['regist_username']) : '';
		$GLOBALS['password'] = isset($_POST['regist_password']) ? htmlspecialchars($_POST['regist_password']) : '';
		$GLOBALS['confirmPassword'] = isset($_POST['regist_password_confirm']) ? htmlspecialchars($_POST['regist_password_confirm']) : '';
	}

	public function checkUniqueEmailAndPseudo()
	{
		unset($countEmail);
		$countEmail = $this->_usersManager->checkEmail();
		if($countEmail > 0)
		{
			FLASH::setFlash('Cette adresse Email est déjà utilisée.');
			$this->renderViewRegister();
		}

		unset($countPseudo);
		$countPseudo = $this->_usersManager->checkPseudo();
		if($countPseudo > 0)
		{
			FLASH::setFlash('Ce pseudo est déjà utilisé.');
			$this->renderViewRegister();
		}
	}

	public function registerProfile()
	{	
		$this->_security = new Security;
		$this->initVariables();
		// die('ok');

		if(isset($_POST['regist_submit']))
		{
			if(empty($GLOBALS['lastname']) &&
				empty($GLOBALS['firstname']) && 
				empty($GLOBALS['email']) && 
				empty($GLOBALS['username']) && 
				empty($GLOBALS['password']) && 
				empty($GLOBALS['confirmPassword']))
			{	
				FLASH::setFlash('Remplissez tous les champs correctement !');
				$this->renderViewRegister();
			}
			if($GLOBALS['password'] != $GLOBALS['confirmPassword'])
			{
				FLASH::setFlash('Le mot de passe et la confirmation de mot de passe ne correspondent pas!');
				$this->renderViewRegister();
			}
			$this->_security->securizationCsrf();

			$this->_user = new Users
			([
				'lastname' => $GLOBALS['lastname'],
				'firstname' => $GLOBALS['firstname'],
				'email' => $GLOBALS['email'],
				'username' => $GLOBALS['username'],
				'password' => $GLOBALS['password'],
				'timeToDelete' => time()
			]);
			$this->checkUniqueEmailAndPseudo();

			$_SESSION['newUser'] = $this->_usersManager->add($this->_user);

			if($_SESSION['newUser'] != false && !($_SESSION['auth']))
			{
				$this->_mail->sendMailUserAdded();
			}
		}

		if(isset($_POST['profil_modify']))
		{

			if(empty($_POST['profil_lastname']) ||
				empty($_POST['profil_firstname']) || 
				empty($_POST['profil_email']) || 
				empty($_POST['profil_username']) || 
				empty($_POST['profil_pass']) || 
				empty($_POST['profil_confirm_pass']))
			{	
				FLASH::setFlash('Remplissez tous les champs correctement !');
				$this->_controllerAdmin = new ControllerAdmin;
				$this->_controllerAdmin->renderViewAdmin();
			}
			if($_POST['profil_pass'] != $_POST['profil_confirm_pass'])
			{
				FLASH::setFlash('Le mot de passe et la confirmation de mot de passe ne correspondent pas!');
				$this->_controllerAdmin = new ControllerAdmin;
				$this->_controllerAdmin->renderViewAdmin();
			}
			$this->_security->securizationCsrf();

			$this->_user = new Users
			([
				'id' => $_SESSION['id'],
				'lastname' => htmlspecialchars($_POST['profil_lastname']),
				'firstname' => htmlspecialchars($_POST['profil_firstname']), 
				'username' => htmlspecialchars($_POST['profil_username']), 
				'email' => htmlspecialchars($_POST['profil_email']), 
				'password' => htmlspecialchars($_POST['profil_pass']) 
			]);
			
			$this->_controllerUser = new ControllerUser;
			if($this->_user->username() != $_SESSION['username'])
			{
				// die('pseudo changé');
				$this->_controllerUser->checkUniquePseudo();
			}
			if($this->_user->email() != $_SESSION['email'])
			{
				// die('email changé');
				$this->_controllerUser->checkUniqueEmail();
			}
			$user = $this->_usersManager->update($this->_user);

			$this->_mail->sendMailUserUpdated();
			FLASH::setFlash('Votre profil a bien été mis à jour, un mail de confirmation vous a été envoyé.', 'success');
		}
		$this->renderViewRegister();
	}

	// public function addUser()
	// {
	// 	$newUser = $this->_usersManager->add($this->_user);

	// 	if($newUser != false && !isset($_SESSION['auth']))
	// 	{
	// 		$this->_mail->sendMailUserAdded();
	// 	}
	// }

	// public function modifyUser()
	// {

	// 	$user = $this->_usersManager->update($this->_user);

	// 	if($user != false && !isset($_SESSION['auth']))
	// 	{
	// 		$this->_mail->sendMailUserUpdated();
	// 	}
	// }

	public function renderViewRegister()
	{
		$this->_view = new View('register');
		$this->_view->generate(NULL);
		exit();
	}

	// public function renderViewAdmin()
	// {
	// 	$userInfos = isset($_SESSION['id']) ? $this->_usersManager->getUserInfos($_SESSION['id']) : '';
 //    	$allUsers = $this->_usersManager->getUsersList();
 //    	$postList = $this->_postsManager->getList(0,25);

 //    	$validateComment = $this->_commentsManager->validateCommentList();
	// 	$usersCount = $this->_usersManager->count();
	// 	$postCount = $this->_postsManager->count();
	// 	$commentValidateCount = $this->_commentsManager->count();
 //    	// var_dump($validateComment); die();

	// 	if(!headers_sent())
	// 	{						
	// 			$this->_view = new View('admin');		
	// 			$this->_view->generate(array('postList' => $postList, 'userInfos' => $userInfos, 'allUsers' => $allUsers));		
	// 	}
	// }
}
