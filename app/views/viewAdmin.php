<?php
use Laurent\App\Service\Flash;
$this->_t = 'Espace perso';
FLASH::flash();
?>

<div>
	<table class="table">
		<tr>
		    <th>Nom</th>
		    <th>Prénom</th>
		    <th>Email</th>
		    <th>Pseudo</th>
		    <th>Niveau</th>
	  	</tr>
		<tr>
			<td> <?= $userInfos->lastname() ?> </td>
		 	<td> <?= $userInfos->firstname() ?> </td>
			<td> <?= $userInfos->email() ?> </td>
			<td> <?= $userInfos->username() ?> </td>
		   	<td> <?= $userInfos->rank() == '2' ? 'Administrateur' : 'Contributeur' ?> </td>
	   	</tr>
	</table>

	<br>
	<br>

	<form action="" method="post" >
    <div class="form-group">
    <label for="lastname">Nom</label>
    <input type="text" name="profil_lastname" value="<?= $userInfos->lastname() ?>" class="form-control" id="lastname" placeholder="nom@exemple.com">
  </div>
    <div class="form-group">
    <label for="firstname">Prénom</label>
    <input type="text" name="profil_firstname" value="<?= $userInfos->firstname() ?>" class="form-control" id="firstname" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
  <label for="username">Pseudo</label>
  <input type="text" name="profil_username" value="<?= $userInfos->username() ?>" class="form-control" id="username" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="profil_email" value="<?= $userInfos->email() ?>" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>  
  <div class="form-group">
    <label for="pass">Nouveau mot de Passe</label>
    <input type="password" name="profil_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
    <div class="form-group">
    <label for="pass">Confirmation du mot de Passe</label>
    <input type="password" name="profil_confirm_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <button type="submit" name="profil_modify" class="btn btn-primary">Mettre à jour</button>

<?php if($_SESSION['rank'] == 2) : ?>

<h3>Commentaires en attente de validation : <?= $commentValidateCount ?></h3>
<br>
<div>
	<table class="table">
	  <tr>
	    <th>Auteur</th>
	    <th>Commentaire</th>
	    <th>Date d'ajout</th>
	    <th>Action</th>
	  </tr>	
<?php /*var_dump($validateComment);die();*/ foreach($validateComment as $comment) : ?>
	  <tr>
	    <th><?= $comment->author() ?></th>
	    <th><?= $comment->content() ?></th>
	    <th><?= $comment->addDate() ?></th>
	    <th><button class="btn btn-info btn-sm"><a style="color:white;" href="commentvalidate&commentValidate=<?= $comment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Valider</a></button> | <button class="btn btn-warning btn-sm"><a style="color:white;" href="commentnovalidate&commentNoValidate=<?= $comment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Supprimer</a></button></th>
	  </tr>	
<?php  endforeach; ?>
	</table>
</div>
<br>
<br>
<h3>Les Articles : <?= $postCount ?></h3>
	<?php if(isset($postList)) : ?>		
<div>
	<table class="table">
	  <tr>
	    <th>Auteur</th>
	    <th>Titre</th>
	    <th>Date d'ajout</th>
	    <th>Action</th>
	  </tr>
	<?php
		foreach ($postList as $posts):
		?>
			<tr>
			 <td> <?= $posts->author() ?> </td>
			 <td> <?= $posts->title() ?> </td>
			 <td> <?= $posts->addDate()/*->format('d/m/Y à H\hi')*/ ?> </td>

		     <td><button class="btn btn-info btn-sm"><a style="color:white;" href="postupdate&postUpdate=<?= $posts->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Modifier</a></button> | <button class="btn btn-warning btn-sm"><a style="color:white;" href="postdelete&postDelete=<?= $posts->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Supprimer</a></button></td>
		    </tr>
		    <br>
			<br>

		<?php endforeach; ?>
	</table>
</div>

<?php endif; ?>

<?php if(isset($allUsers)) : ?>
<div>

<br>
<br>

	<h3>Les utilisateurs : <?= $usersCount ?></h3>
		<table class="table">
			<tr>
				<th>Utilisateur</th>
				<th>Date inscription</th>
				<th>Action</th>
			</tr>
		<?php foreach ($allUsers as $user): ?>
			<tr>
			  <td> <?= $user->username() ?> </td>
			  <td> <?= ($user->confirmedAt() != NULL ? $user->confirmedAt() : '_') ?> </td>
			  <td><button class="btn btn-info btn-sm"><a style="color:white;" href="upgradeuser&upgradeUser=<?= $user->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Upgrade</a></button> | <button class="btn btn-warning btn-sm"><a style="color:white;" href="userbanish&userBanish=<?= $user->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Bannir</a></button></td>
			</tr>
		<?php endforeach; ?>	
		</table>
</div>
<?php endif; ?>
<?php endif; ?>
