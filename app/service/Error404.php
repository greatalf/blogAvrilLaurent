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
			header('Refresh:4, url=accueil');

			$this->_view = new View('Error');
			$this->_view->generate(['errorMsg' => $errorMsg]);		
		}	
	}
}
