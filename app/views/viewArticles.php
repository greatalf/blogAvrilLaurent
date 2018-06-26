<?php
use Laurent\App\Service\Flash;
$this->_t = 'Articles';
FLASH::flash();

foreach($posts as $post) :
?>
<h3 id="post_h1_title"><?= $post->title() ?></h3>
<p><em><strong><?= $post->chapo() ?></strong></em></p>
<p><?=  strlen($post->content()) <= 150 ? $post->content() : substr($post->content(), 0, 150) . '...' ?></p>
<p><?= $post->updateDate() != NULL ? 'Écrit le ' . $post->addDate() . ' par <em> ' . $post->author() . '</em>, modifié le ' . $post->updateDate() : 'Écrit le ' . $post->addDate()?></p>
<a href="article&post_id=<?= $post->id() ?>"><p id="read_all">Lire la suite...</p></a>
<?php //boutton_update($post->id()) . boutton_del($post->id()) ?>
<hr>
<?php endforeach; ?>
<br>
<br>
<br>
<?php

//////////////////////////////////////////////////////////////////////////
//////////////////Mettre ça dans une classe //////////////////////////////
//////////////////////////////////////////////////////////////////////////
function boutton_del($refDel)
{
  if(isset($_SESSION['rank']) && ($_SESSION['rank'] == 2))
  {
    $boutton_delete = '<a href="articles&post_delete=' . $refDel . '"> | <button type="submit" class="btn btn-danger">Supprimer</button></a>';
  }else
  {
    $boutton_delete = '';
  }
  return $boutton_delete;
}

function boutton_update($refUpdate)
{
  if(isset($_SESSION['rank']) && ($_SESSION['rank'] == 2))
  {
    $boutton_update = '<a href="articles&post_update=' . $refUpdate . '#update_post_form"><button type="submit" class="btn btn-info">Modifier</button></a>';
  }else
  {
    $boutton_update = '';
  }
  return $boutton_update;
}
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
?>



<?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 2) : ?>
<hr>
<h4 id="update_post_form">Écrivez un article : </h4>
<br>

<form action="" method="post" >
  
  <div class="form-group">
    <label for="post_author">Auteur</label>
    <input type="text" name="post_author" value="<?= $_SESSION['username'] ?>" class="form-control" id="post_author" placeholder="L'auteur">
  </div>
  <div class="form-group">
    <label for="post_title">Titre</label>
    <input type="text" name="post_title" value="<?= isset($_GET['post_update']) ? str_replace('<br />', '', ($updatePost->title())) : ''?>" class="form-control" id="post_title" placeholder="Le titre" <?= isset($_GET['post_update']) ? 'autofocus' : '' ?>>
  </div>
    <div class="form-group">
    <label for="post_chapo">Chapô</label>
    <input type="text" name="post_chapo" value="<?= isset($_GET['post_update']) ? str_replace('<br />', '', ($updatePost->chapo())) : ''?>" class="form-control" id="post_chapo" placeholder="Le chapô">
  </div>
  <div class="form-group">
    <label for="post">Article</label>
    <textarea class="form-control" name="post_content" id="post" rows="4" placeholder="Votre article..."><?= isset($_GET['post_update']) ? str_replace('<br />', '', ($updatePost->content())) : ''?></textarea>
  </div>
  <button type="submit" name="<?= isset($_GET['post_update']) ? 'post_update' : 'post_submit'?>" class="btn btn-primary"><?= isset($_GET['post_update']) ? 'Valider la modification' : 'Envoyer'?></button>
</form>
<hr>
<?php endif; ?>
