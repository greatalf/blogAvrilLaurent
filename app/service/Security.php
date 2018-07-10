<?php
namespace Laurent\App\Service;

use Laurent\App\Service\Flash;

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
}
