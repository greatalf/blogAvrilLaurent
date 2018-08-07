<?php
use Laurent\App\Service\Flash;
$this->_t = 'Article';
FLASH::flash();
?>

<div class="post_general">
	<h3 style="color: #000000;"><?= $onePost->title() ?></h3>
  <p><em><strong><?= $onePost->chapo() ?></strong></em></p>
	<br>
	<p><?= $onePost->content() ?></p>
	<p>Auteur : <u><?= $onePost->author() ?></u></p>
	<time id="update_com_form" class="date_writen_onePost"><?= 'Écrit le ' . $onePost->addDate() ?><?= $onePost->updateDate() != NULL ? ', modifié le ' . $onePost->updateDate() . '.' : ''?></time>
</div>
<br>
<br>
<br>

<h4>Commentaires :</h4>

<?php if(isset($_SESSION['auth'])) : ?>
<form action="" method="post" >
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" name="com_content" id="message" rows="4" placeholder="Votre commentaire..." <?= isset($_GET['commentUpdate']) ? 'autofocus' : '' ?>><?= isset($_GET['commentUpdate']) ? str_replace('<br />', '', ($updateComment->content())) : ''?></textarea>



  </div>
  <button type="submit" name="<?= isset($_GET['commentUpdate']) ? 'com_update' : 'com_submit' ?>" class="btn btn-primary"><?= isset($_GET['commentUpdate']) ? 'Valider la modification' : 'Envoyer'?></button>
</form>

<?php else : ?>
<p>Vous devez être connecté pour laisser un commentaire : <a href="http://localhost/Blog_Avril_Laurent/connexion">=>Je me connecte<=</a></p>

<?php endif; ?>
<hr>

<?php 
echo (count($comments) == 0) ? 'Aucun commentaire n\'a encore été posté pour cet article!' : '';
foreach ($comments as $comment) :
?>
  <author class="author_com"><?= $comment->author() ?></author>
  <p class="content_com"><?= $comment->content() ?></p>
  <p class="date_writen_com"><em><?= 
  $comment->updateDate() != NULL ? "Posté le " . $comment->addDate() . ", modifié le " . $comment->updateDate() : "Posté le " . $comment->addDate();
  ?></em></p>

  <?= ((isset($_SESSION['rank']) && $_SESSION['rank'] == 2))
    ? '<a style="color:white;" href="commentupdate&commentUpdate=' . $comment->id() . '&post_id=' . $onePost->id() . '&tokenCsrf='. $_SESSION['tokenCsrf'] .'"><boutton class="btn btn-warning btn-sm">Modifier</boutton></a> | <a style="color:white;" href="commentdelete&commentDelete=' . $comment->id() . '&post_id=' . $onePost->id() . '&tokenCsrf='. $_SESSION['tokenCsrf'] .'"><boutton class="btn btn-info btn-sm">Supprimer</boutton></a>' : '' ?>

    <?php /*var_dump($getCommentById);*/ ?>


<?php foreach($getCommentById as $getComment) : ?>
<?php if((isset($getCommentById) && $comment->author() == $getComment->author() && isset($_SESSION['tokenCsrf']))) : ?>
    <?php echo '<a style="color:white;" href="commentupdate&commentUpdate=' . htmlspecialchars($comment->id()) . '&post_id=' . $onePost->id() . '&tokenCsrf='. $_SESSION['tokenCsrf'] .'"><boutton class="btn btn-warning btn-sm">Modifier</boutton></a> | <a style="color:white;" href="commentdelete&commentDelete=' . htmlspecialchars($comment->id()) . '&post_id=' . $onePost->id() . '&tokenCsrf='. $_SESSION['tokenCsrf'] .'"><boutton class="btn btn-info btn-sm">Supprimer</boutton></a>'; break;?>
<?php endif; ?>
<?php endforeach; ?>    
<hr>
<?php
endforeach;
?>
