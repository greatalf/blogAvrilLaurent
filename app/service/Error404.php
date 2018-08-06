<?php
namespace Laurent\App\Service;

use \Laurent\App\Views\View;

class Error404
{
	private $_view;
	
	public function errorPage()
	{
		if(!headers_sent())
		{
			$errorMsg = 'ERREUR 404 <br> PAGE INTROUVABLE!';
			header('Refresh:2, url=http://localhost/Blog_Avril_Laurent/accueil');

			$this->_view = new View('Error');
			$this->_view->generate(['errorMsg' => $errorMsg]);		
		}	
	}

	public function errorUrl()
	{
		if(!headers_sent())
		{			
			header('Refresh:0, url=http://localhost/Blog_Avril_Laurent/accueil');
			exit();	
		}
	}
}
