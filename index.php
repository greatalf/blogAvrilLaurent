<?php 
require 'lib/autoload.php';

$db = DBFactory::getConnexionPDO();
$manager = new PostsManager_PDO($db);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Accueil du site</title>
    <meta charset="utf-8" />
  </head>
  
  <body>
    <p><a href="admin.php">Accéder à l'espace d'administration</a></p>
		<?php
		if (isset($_GET['id']))
		{
		  $posts = $manager->getUnique($_GET['id']);
		  echo '<p>Par <em>', $posts->author(), '</em>, 
		  le ', $posts->addDate()->format('d/m/Y à H\hi'), '</p>', "\n",
		       '<h2>', $posts->title(), '</h2>', "\n",
		       '<p>', nl2br($posts->content()), '</p>', "\n";
		  
			if ($posts->addDate() != $posts->updateDate())
			{
			    echo '<p style="text-align: right;"><small><em>Modifiée le ', 
			    $posts->updateDate()->format('d/m/Y à H\hi'), '</em></small></p>';
			}
		}

		else
		{
		  echo '<h2 style="text-align:center">Liste des 5 derniers articles</h2>';
		  
		  foreach ($manager->getList(0, 5) as $posts)
		  {
		    if (strlen($posts->content()) <= 200)
		    {
		      $content = $posts->content();
		    }
		    
		    else
		    {
		      $debut = substr($posts->content(), 0, 200);
		      $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
		      
		      $content = $debut;
		    }
		    
		    echo '<h4><a href="?id=', $posts->id(), '">', $posts->title(), '</a></h4>', "\n",
		         '<p>', nl2br($content), '</p>';
		  }
		}
		?>
  </body>
</html>
