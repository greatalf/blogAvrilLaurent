<?php
$this->_t = 'Admin';
?>
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
     <td><a href="?modifier='<?= $posts->id() ?> '">Modifier</a> | <a href="?supprimer='<?= $posts->id() ?> '">Supprimer</a></td>
    </tr>
    <br>

<?php endforeach; ?>
</table>


<style>
table, td, th
{
	color: white;
	border: 1px solid black;
}

a
{
	color: white;
}

table, th
{
	margin:auto;
	text-align: center;
	border-collapse: collapse;
}

table, th
{
	border: 3px solid black;
}

td
{
  padding: 3px;
}
</style>