<?php
class Router
{
	private $_controller,
			$_view;

    /**
     *
     */
    public function routeReq()
	{
		try
		{
			$url = '';
			if(isset($_GET['url']))
			{	
				$url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

				$controller = ucfirst(strtolower($url[0]));
				$controllerClass = 'Controller' . $controller;
				$controllerFile = 'app/controllers/' . $controllerClass . '.php';

				if (file_exists($controllerFile))
				{
                    require_once($controllerFile);
					$this->_controller = new $controllerClass;
				}
				else
				{
					echo 'Le fichier ' . $controllerFile . ' n\'existe pas !!';
					throw new \Exception('Page Introuvable : Erreur 404');	
				}
			}
			else
			{
				require_once('ControllerAccueil.php');
				$this->_controller = new ControllerAccueil($url);
			}

		}
		catch(\Exception $e)
		{
			$errorMsg = $e->getMessage();
			header('HTTP/1.0 404 Not Found');
			require_once('app/views/viewError.php');
		}
	}
}
