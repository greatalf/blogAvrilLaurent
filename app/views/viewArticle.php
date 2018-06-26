<?php
use Laurent\App\Session;

SESSION::flash();

$this->_t = 'Article';
foreach ($onePost as $post) : ?>
<div class="post_general">
	<h3 style="color: #FFFFFF;"><?= $post->title() ?></h3>
  <p><em><strong><?= $post->chapo() ?></strong></em></p>
	<br>
	<p><?= $post->content() ?></p>
	<p>Auteur : <u><?= $post->author() ?></u></p>
	<time id="update_com_form" class="date_writen_post"><?= 'Écrit le ' . $post->addDate() ?><?= $post->updateDate() != NULL ? ', modifié le ' . $post->updateDate() . '.' : ''?></time>
</div>
<?php endforeach; ?>
<br>
<br>
<br>

<h4>Commentaires :</h4>

<?php if(isset($_SESSION['auth'])) : ?>
<form action="" method="post" >
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" name="com_content" id="message" rows="4" placeholder="Votre commentaire..." <?= isset($_GET['post_update']) ? 'autofocus' : '' ?>><?= isset($_GET['comment_update']) ? str_replace('<br />', '', ($comment->content())) : ''?></textarea>
  </div>
  <button type="submit" name="<?= isset($_GET['comment_update']) ? 'com_update' : 'com_submit' ?>" class="btn btn-primary"><?= isset($_GET['comment_update']) ? 'Valider la modification' : 'Envoyer'?></button>
</form>

<?php else : ?>
<p>Vous devez être connecté pour laisser un commentaire : <a href="http://localhost/Blog_Avril_Laurent/connexion">=>Je me connecte<=</a></p>

<?php endif; ?>
<hr>

<?php 
echo (count($comments) == 0) ? 'Aucun commentaire n\'a encore été posté pour cet article!' : '';

//////////////////////////////////////////////////////////////////////////
//////////////////Mettre ça dans une classe //////////////////////////////
//////////////////////////////////////////////////////////////////////////
$GLOBALS['post_id'] = $post->id();

function boutton_del($refDel)
{
  if(isset($_SESSION['rank']) && ($_SESSION['rank'] == 2))
  {
    $boutton_delete = '<a href="article&post_id=' . $GLOBALS['post_id'] . '&comment_delete=' . $refDel . '"> | <button type="submit" class="btn btn-danger">Supprimer</button></a>';
  }
  else
  {
    $boutton_delete = '';
  }
  return $boutton_delete;
}

function boutton_update($refUpdate)
{
  if(isset($_SESSION['rank']) && ($_SESSION['rank'] == 2))
  {
    $boutton_update = '<a href="article&post_id=' . $GLOBALS['post_id'] . '&comment_update=' . $refUpdate . '#update_com_form"><button type="submit" class="btn btn-info">Modifier</button></a>';
  }
  else
  {
    $boutton_update = '';
  }
  return $boutton_update;
}
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////

foreach ($comments as $comment) :
?>
	<author class="author_com"><?= $comment->author() ?></author>
  <p class="content_com"><?= $comment->content() ?></p>
  <?= boutton_update($comment->id()) . boutton_del($comment->id()) ?>
  <p class="date_writen_com"><em><?= 
  $comment->updateDate() != NULL ? "Posté le " . $comment->addDate() . ", modifié le " . $comment->updateDate() : "Posté le " . $comment->addDate();
  ?></em></p>
<hr>
<?php
endforeach;
?>
