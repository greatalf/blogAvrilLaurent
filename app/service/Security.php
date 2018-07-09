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
}
