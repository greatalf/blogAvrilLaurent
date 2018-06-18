<?php
$this->_t = 'Article';
?>
<div class="post_general">
	<h3 style="color: #FFFFFF;"><?= $post->title() ?></h3>
  <p><em><strong><?= $post->chapo() ?></strong></em></p>
	<br>
	<p><?= $post->content() ?></p>
	<p>Auteur : <u><?= $post->author() ?></u></p>
	<time id="update_com_form" class="date_writen_post"><?= 'Écrit le ' . $post->addDate()->format('d/m/Y à H:i') ?><?= $post->updateDate()->format('d/m/Y à H:i') != NULL ? ', modifié le ' . $post->updateDate()->format('d/m/Y à H:i') . '.' : ''?></time>
</div>
<br>
<br>
<br>
<?php
  SESSION::flash();
?>
<h4>Commentaires :</h4>

<form action="" method="post" >
<?php if(!isset($_SESSION['auth'])) : ?>
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="connect_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="pass">Mot de Passe</label>
    <input type="password" name="connect_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <?php endif; ?>
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" name="com_content" id="message" rows="4" placeholder="Votre commentaire..." <?= isset($_GET['post_update']) ? 'autofocus' : '' ?>><?= isset($_GET['comment_update']) ? str_replace('<br />', '', ($comment->content())) : ''?></textarea>
  </div>
  <button type="submit" name="<?= isset($_GET['comment_update']) ? 'com_update' : 'com_submit'?>" class="btn btn-primary"><?= isset($_GET['comment_update']) ? 'Valider la modification' : 'Envoyer'?></button>
</form>

<hr>

<?php 
echo (count($comments) == 0) ? 'Aucun commentaire n\'a été posté pour cet article!' : '';

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
  $comment->updateDate() != NULL ? "Posté le " . $comment->addDate()->format('d/m/Y à H:i') . ", modifié le " . $comment->updateDate()->format('d/m/Y à H:i') : "Posté le " . $comment->addDate()->format('d/m/Y à H:i');
  ?></em></p>
<hr>
<?php
endforeach;
?>
