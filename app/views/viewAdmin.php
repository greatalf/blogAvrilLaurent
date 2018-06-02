<?php
$this->_t = 'Espace perso';
?>

<div>
	<table>
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

<br><br><br>
<h3>Articles : </h3>
	<a href="#"><button class="btn btn-primary">Ajouter</button></a>
	<?php if(isset($manager)) : ?>		
<div>
	<table>
	  <tr>
	    <th>Auteur</th>
	    <th>Titre</th>
	    <th>Date d'ajout</th>
	    <th>Dernière modification</th>
	    <th>Action</th>
	  </tr>
	<?php
		foreach ($manager as $posts):
		?>
			<tr>
			 <td> <?= $posts->author() ?> </td>
			 <td> <?= $posts->title() ?> </td>
			 <td> <?= $posts->addDate()->format('d/m/Y à H\hi') ?> </td>
			 <td> <?= ($posts->addDate() == $posts->updateDate() ? '-' : $posts->updateDate()->format('d/m/Y à H\hi')) ?> </td>
		     <td><a href="?update=<?= $posts->id() ?> "><button class="btn btn-info btn-sm">Modifier</button></a> | <a href="?delete=<?= $posts->id() ?> "><button class="btn btn-warning btn-sm">Supprimer</button></a></td>
		    </tr>
		    <br>

		<?php endforeach; ?>
	</table>
</div>
	<?php endif; ?>
