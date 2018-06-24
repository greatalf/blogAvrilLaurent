<?php
namespace Laurent\App\Views;

class View
{
	private $_file,
			$_t;

	public function __construct($action)
	{
		$this->_file = 'app/views/view' . ucfirst($action) . '.php';
	}

	/**
     *Permet de générer et d'afficher la vue $view
     * @param $data donnée qu'on passe en param pour les récupérer dans la vue
     *
     * @throws Exception
     */
	public function generate($data)
	{
		$content = $this->generateFile($this->_file, $data);
		$view = $this->generateFile('app/views/template.php', array(
			't' 		=> $this->_t,
			'content' 	=> $content));

 		echo $view;
	}

	/**
	*
	*@param $file adresse du fichier appelée dans le constructeur, liée à $action.
	*@param $data 
	*/
	private function generateFile($file, $data)
	{
		if(file_exists($file) && $data != NULL)
		{
			extract($data);

			//Je veux envoyer un paramètre avec le require $file
			ob_start();
			require_once $file;
			return ob_get_clean();
		}
		elseif(file_exists($file))
		{
			ob_start();
			require_once $file;
			return ob_get_clean();
		}
		else
		{
			throw new Exception ('Fichier ' . $file . ' introuvable');
		}
	}
}
