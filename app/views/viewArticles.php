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
<hr>
<?php endforeach; ?>
<br>
<br>
<br>

<?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 2) : ?>
<hr>
<h4 id="update_post_form"><?= isset($_GET['postUpdate']) ? 'Modifiez un article : ' : 'Écrivez un article : ' ?></h4>
<br>

<form action="" method="post" >
  
  <div class="form-group">
    <label for="post_author">Auteur</label>
    <input type="text" name="post_author" value="<?= $_SESSION['username'] ?>" class="form-control" id="post_author" placeholder="L'auteur">
  </div>
  <div class="form-group">
    <label for="post_title">Titre</label>
    <input type="text" name="post_title" value="<?= isset($_GET['postUpdate']) ? str_replace('<br />', '', ($updatePost->title())) : ''?>" class="form-control" id="post_title" placeholder="Le titre" <?= isset($_GET['postUpdate']) ? 'autofocus' : '' ?>>
  </div>
    <div class="form-group">
    <label for="post_chapo">Chapô</label>
    <input type="text" name="post_chapo" value="<?= isset($_GET['postUpdate']) ? str_replace('<br />', '', ($updatePost->chapo())) : ''?>" class="form-control" id="post_chapo" placeholder="Le chapô">
  </div>
  <div class="form-group">
    <label for="post_content">Article</label>
    <textarea class="form-control" name="post_content" id="post" rows="4" id="post_content" placeholder="Votre article..."><?= isset($_GET['postUpdate']) ? str_replace('<br />', '', ($updatePost->content())) : ''?></textarea>
  </div>
  <button type="submit" name="<?= isset($_GET['postUpdate']) ? 'post_update' : 'post_submit'?>" class="btn btn-primary"><?= isset($_GET['postUpdate']) ? 'Valider la modification' : 'Envoyer'?></button>
</form>
<hr>
<?php endif; ?>
