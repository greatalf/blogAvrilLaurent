<?php
use Laurent\App\Service\Flash;
$this->_t = 'Espace perso';
FLASH::flash();
?>

<div>
	<table class="table">
		<tr>
		    <th class="smallScreen">Nom</th>
		    <th class="smallScreen">Prénom</th>
		    <th>Email</th>
		    <th>Pseudo</th>
		    <th>Niveau</th>
	  	</tr>
		<tr>
			<td class="smallScreen"> <?= strlen($userInfos->lastname()) > 20 ? substr($userInfos->lastname(), 0, 20) . '...' : $userInfos->lastname() ?>				
			</td>
		 	<td class="smallScreen"> <?= strlen($userInfos->firstname()) > 20 ? substr($userInfos->firstname(), 0, 20) . '...' : $userInfos->firstname() ?>
		 	</td>
			<td> <?= strlen($userInfos->email()) > 15 ? substr($userInfos->email(), 0, 15) . '...' : $userInfos->email() ?>				
			</td>
			<td> <?= strlen($userInfos->username()) > 10 ? substr($userInfos->username(), 0, 10) . '...' : $userInfos->username() ?>			
			</td>
		   	<td> <?= $userInfos->rank() == '2' ? 'Admin..' : 'Contrib..' ?> </td>
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
    <input type="email" name="profil_email" value="<?= $userInfos->email() ?>" class="form-control" id="email" placeholder="nom@exemple.com">
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

<?php if(isset($getCommentById)) : ?>
<h3>Les commentaires persos : </h3>
<div>
	<table class="table">
	  <tr>
	    <th>Titre article</th>
	    <th class="smallScreen">Date d'ajout</th>
	    <th>Contenu</th>
	    <th>Action</th>
	  </tr>
<?php foreach($getCommentById as $getComment) : ?>
<?php foreach($allComments as $comment) : ?> 
<?php if((isset($getCommentById) && $comment->author() == $getComment->author())) : ?>
<?php endif; ?>
<?php endforeach; ?>

		<tr>
			<td><?= $getComment->title() ?></td>
			<td class="smallScreen"><?= $getComment->addDate() ?></td>
			<td><?= $getComment->content() ?>
		    <td>
		    	<button class="btn btn-info btn-sm">
		    		<a style="color:white;" href="commentupdate&commentUpdate=<?= $getComment->id() ?>&post_id=<?=  /* le num de post_id est pas bon.*/ $getComment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">
		    		Modifier
		    		</a>
		    	</button> | 
		    	<button class="btn btn-warning btn-sm">
		    		<a style="color:white;" href="commentdelete&commentDelete=<?= $getComment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">
		    		Supprimer
		    		</a>
		    	</button>
		    </td>
		</tr>
<?php endforeach; ?>
	</table>
</div>
<?php endif; ?>


<?php if($_SESSION['rank'] == 2) : ?>

<hr class="hr_separator">
<h3>Commentaires en attente de validation : <?= $commentValidateCount ?></h3>
<br>
<div>
	<table class="table">
	  <tr>
	    <th>Auteur</th>
	    <th>Commentaire</th>
	    <th class="smallScreen">Date d'ajout</th>
	    <th>Action</th>
	  </tr>	
<?php foreach($validateComment as $comment) : ?>
	  <tr>
	    <th><?= $comment->author() ?></th>
	    <th><?= $comment->content() ?></th>
	    <th class="smallScreen"><?= $comment->addDate() ?></th>
	    <th><button class="btn btn-info btn-sm"><a style="color:white;" href="commentvalidate&commentValidate=<?= $comment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Valider</a></button> | <button class="btn btn-warning btn-sm"><a style="color:white;" href="commentnovalidate&commentNoValidate=<?= $comment->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Supprimer</a></button></th>
	  </tr>	
<?php  endforeach; ?>
	</table>
</div>
<br>
<br>
<hr class="hr_separator">
<h3>Les Articles : <?= $postCount ?></h3>
<br>
	<?php if(isset($postList)) : ?>		
<div>
	<table class="table">
	  <tr>
	    <th>Auteur</th>
	    <th>Titre</th>
	    <th class="smallScreen">Date d'ajout</th>
	    <th>Action</th>
	  </tr>
	<?php
		foreach ($postList as $posts):
		?>
			<tr>
			 <td> <?= $posts->author() ?> </td>
			 <td> <?= $posts->title() ?> </td>
			 <td class="smallScreen"> <?= $posts->addDate() ?> </td>

		     <td><button class="btn btn-info btn-sm"><a style="color:white;" href="postupdate&postUpdate=<?= $posts->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Modifier</a></button> | <button class="btn btn-warning btn-sm"><a style="color:white;" href="postdelete&postDelete=<?= $posts->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Supprimer</a></button></td>
		    </tr>
		<?php endforeach; ?>
	</table>
</div>
<?php endif; ?>

<?php if(isset($allUsers)) : ?>
<div>

<br>
<br>
<hr class="hr_separator">
	<h3>Les utilisateurs : <?= $usersCount ?></h3>
		<table class="table">
			<tr>
				<th>Utilisateur</th>
				<th>Date inscription</th>
				<th>Rang | Action</th>
			</tr>
		<?php foreach ($allUsers as $user): ?>
			<tr>
			  <td> <?= $user->username() ?> </td>
			  <td> <?= ($user->confirmedAt() != NULL ? $user->confirmedAt() : '_') ?> </td>
			  <td>
			  	<?php if($user->rank() == 1) : ?>
			  	<button class="btn btn-info btn-sm">
			  		<a style="color:white;" href="upgradeuser&upgradeUser=<?= $user->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Améliorer
			  		</a>
			  	</button>
			  	<?php elseif($user->rank() == 2) : ?>  
			  	<button class="btn btn-info btn-sm">
			  		<a style="color:white;" href="downgradeuser&downgradeUser=<?= $user->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Rétrograder
			  		</a>
			  	</button>
			  	<?php endif; ?>	
			  	| <button class="btn btn-warning btn-sm">
			  		<a style="color:white;" href="userbanish&userBanish=<?= $user->id() ?>&tokenCsrf=<?= $_SESSION['tokenCsrf'] ?>">Bannir
			  		</a>
			  	</button>
			  </td>
			</tr>
		<?php endforeach; ?>	
		</table>
</div>
<?php endif; ?>
<?php endif; ?>
