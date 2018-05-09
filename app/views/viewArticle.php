<?php
$this->_t = 'Article';
?>
<div class="post_general">
	<h3><?= $post->title() ?></h3>
	<br>
	<p><?= $post->content() ?></p>
	<p>Auteur : <u><?= $post->author() ?></u></p>
	<time class="date_writen_post"><?= 'Écrit le ' . $post->addDate()->format('d/m/Y à H:i') . ', modifié le ' . $post->updateDate()->format('d/m/Y à H:i') . '.' ?></time>
</div>
<br>
<br>
<br>
<h4>Commentaires :</h4>

<form action="#" method="post" >
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="com_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="pass">Mot de Passe</label>
    <input type="password" name="com_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" name="com_message" id="message" rows="4" placeholder="Votre commentaire..."></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<hr>

<?php 
if(isset($_SESSION['comments_abs']))
{
	echo $_SESSION['comments_abs'];
	unset($_SESSION['comments_abs']);
}
foreach ($comments as $comment) :
?>
	<author class="author_com"><?= $comment->author() ?></author>
	<p class="content_com"><?= $comment->content() ?></p>
	<p class="date_writen_com"><em><?= $comment->addDate()->format('d/m/Y à H:i') ?></em></p>
	<hr>
<?php
endforeach;
?>
