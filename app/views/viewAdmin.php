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
		   	<td> <?= $userInfos->rank() ?> </td>
	   	</tr>
	</table>

	<br>
	<br>

	<form action="" method="post" >
    <div class="form-group">
    <label for="email">Nom</label>
    <input type="text" name="profil_lastname" value="<?= $userInfos->lastname() ?>" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
    <div class="form-group">
    <label for="email">Prénom</label>
    <input type="text" name="profil_firstname" value="<?= $userInfos->firstname() ?>" class="form-control" id="email" placeholder="nom@exemple.com">
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
    <div class="form-group">
    <label for="email">Pseudo</label>
    <input type="text" name="profil_email" value="<?= $userInfos->username() ?>" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <button type="submit" name="profil_modify" class="btn btn-primary">Mettre à jour</button>

 <?php if($_SESSION['rank'] == 2) : ?>

<h3>Articles : </h3>
	<?php if(isset($postList)) : ?>		
<div>
	<table class="table">
	  <tr>
	    <th>Auteur</th>
	    <th>Titre</th>
	    <th>Date d'ajout</th>
	    <th>Dernière modification</th>
	    <th>Action</th>
	  </tr>
	<?php
		foreach ($postList as $posts):
		?>
			<tr>
			 <td> <?= $posts->author() ?> </td>
			 <td> <?= $posts->title() ?> </td>
			 <td> <?= $posts->addDate()/*->format('d/m/Y à H\hi')*/ ?> </td>
			 <td> <?= $posts->updateDate() != NULL ? (($posts->addDate() == $posts->updateDate() ? '-' : $posts->updateDate()/*->format('d/m/Y à H\hi')*/)) : '_'?> </td>

		     <td><a href="articles&post_update=<?= $posts->id() ?>#update_post_form"><button class="btn btn-info btn-sm">Modifier</button></a> | <a href="admin&post_delete=<?= $posts->id() ?> "><button class="btn btn-warning btn-sm">Supprimer</button></a></td>
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

	<h3>Les utilisateurs : </h3>
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
			  <td><a href="admin&user_delete=<?= $user->id() ?> "><button class="btn btn-warning btn-sm">Bannir</button></a></td>
			</tr>
		<?php endforeach; ?>	
		</table>
</div>
<?php endif; ?>
<?php endif; ?>
