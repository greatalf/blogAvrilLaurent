<?php
require 'lib/autoload.php';

$db = DBFactory::getConnexionPDO();
$manager = new PostsManager_PDO($db);

if (isset($_GET['modifier']))
{
  $posts = $manager->getUnique((int) $_GET['modifier']);
  $message = 'Vous pouvez modifier l\'article...';
}

if (isset($_GET['supprimer']))
{
  $manager->delete((int) $_GET['supprimer']);
  $message = 'L\'article a bien été supprimé !';
}

if (isset($_POST['title']))
{
    $posts = new Posts(
      [
        'author' => $_POST['author'],
        'title' => $_POST['title'],
        'content' => $_POST['content']
      ]
    );
    
    if (isset($_POST['id']))
    {
      $posts->setId($_POST['id']);
    }
    
    if ($posts->isValable())
    {
      $manager->save($posts);
      
      $message = ($posts->isNew()) ? 'L\'article a bien été ajouté !' : 'L\'article a bien été modifié !';
    }
    else
    {
      $errors = $posts->errors();
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Administration</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="assets/style.css">
  </head>
  
  <body>
    <p><a href=".">Accéder à l'accueil du site</a></p>
    
    <form action="admin.php" method="post">
      <p style="text-align: center">
<?php
if (isset($message))
{
  echo $message, '<br />';
}
?>

<!-- ____________________author_____________________________________ -->

        <?php if (isset($errors) && in_array(Posts::INVAILABLE_AUTHOR, $errors)) {echo 'L\'auteur est invalide.<br />'; }?>

        Auteur : <input type="text" name="author" value="<?php if (isset($posts)) echo $posts->author(); ?>" /><br />

<!-- ______________________title_____________________________________ -->
        
        <?php if (isset($errors) && in_array(Posts::INVAILABLE_TITLE, $errors)) {echo 'Le titre est invalide.<br />'; }?>

        Titre : <input type="text" name="title" value="<?php if (isset($posts)) echo $posts->title(); ?>" /><br />

<!-- ______________________content_________________________________ -->
        
        <?php if (isset($errors) && in_array(Posts::INVAILABLE_CONTENT, $errors)) {echo 'Le contenu est invalide.<br />'; }?>

        Contenu :<br /><textarea rows="8" cols="60" name="content"><?php if (isset($posts)) echo $posts->content(); ?></textarea><br />



<?php
if(isset($posts) && !$posts->isNew())
{
?>
        <input type="hidden" name="id" value="<?= $posts->id() ?>" />
        <input type="submit" value="Modifier" name="modifier" />
<?php
}
else
{
?>
        <input type="submit" value="Ajouter" />
<?php
}
?>
      </p>
    </form>
    
    <p style="text-align: center">Il y a actuellement <?= $manager->count() ?> articles. En voici la liste :</p>
    
    <table>
      <tr>
        <th>Auteur</th>
        <th>Titre</th>
        <th>Date d'ajout</th>
        <th>Dernière modification</th>
        <th>Action</th>
      </tr>
<?php
foreach ($manager->getList() as $posts)
{
  echo '<tr>
            <td>', $posts->author(), '</td>
            <td>', $posts->title(), '</td>
            <td>', $posts->addDate()->format('d/m/Y à H\hi'), '</td>
            <td>', ($posts->addDate() == $posts->updateDate() ? '-' : $posts->updateDate()->format('d/m/Y à H\hi')), '</td>
            <td><a href="?modifier=', $posts->id(), '">Modifier</a> | <a href="?supprimer=', $posts->id(), '">Supprimer</a></td>
        </tr>', "\n";
}
?>
    </table>
    
<!--   <br>
  <br>
  <form action="index.php" method="post">
    <select name="postsNumber" id="postsNumber">
      <?php for ($i=1; $i < 25; $i++) {?> 
      <option value=<?= $i ?>><?= $i ?></option>
      <?php } ?>
    </select>
    <input type="submit" value="Enregistrer">
  </form>
  <br>
  <br> -->

  </body>
</html>
