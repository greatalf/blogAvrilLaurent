<?php
namespace Laurent\App\Service;

use Laurent\App\Service\Flash;
use Laurent\App\Models\UsersManager;
use Laurent\App\Models\Model;

class Security
{
	public function securizationCsrf()
	{		
		if(isset($_SESSION['tokenCsrf']) && isset($_GET['tokenCsrf']))
		{
			if($_GET['tokenCsrf'] != $_SESSION['tokenCsrf'])
			{
				FLASH::setFlash('Une tentative de suppression à votre insue à été déjouée avec succès.', 'success');
				header('Location: admin');
				exit();
			}
		}
	}	

	public function noAccessBecauseBruteForce()
	{
		while(isset($_COOKIE['BRUTEFORCE']))
		{
			header('Refresh:3, url=http://localhost/Blog_Avril_Laurent/connexion');
			die('connexion bloquée, réessayez ultérieurement...');
		}
	}

	public function getIp()
	{
		// IP si internet partagé
		if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		// IP derrière un proxy
		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		// Sinon : IP normale
		else
		{
			return(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		}	
	}

	public function decoSessionAuto($minutes)
	{
		if(isset($_SESSION['lastTime']))
		{			   				
			if((time() - $_SESSION['lastTime']) > $minutes*60)
			{
				header('Refresh:0, url=deconnexion');
			}
			$_SESSION['lastTime'] = time();
		}
	}
}
