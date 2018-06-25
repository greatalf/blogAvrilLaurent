<?php
use Laurent\App\Session;
$this->_t = 'Inscription';
SESSION::flash();
Session::cookieFlash('denied');
?>
<form action="" method="post" >
	<div class="form-group">
   		<label for="lastname">Nom</label>
   		<input type="text" name="regist_lastname" value="<?= isset($_POST['regist_lastname']) ? $_POST['regist_lastname'] : '' ?>" class="form-control" id="lastname" placeholder="Votre nom" required="required">
  	</div>
  	<div class="form-group">
	    <label for="firstname">Prénom</label>
	    <input type="text" name="regist_firstname" value="<?= isset($_POST['regist_firstname']) ? $_POST['regist_firstname'] : '' ?>" class="form-control" id="firstname" placeholder="Votre prénom" required="required">
  	</div>	
 	<div class="form-group">
	    <label for="email">Email</label>
	    <input type="email" name="regist_email" value="<?= isset($_POST['regist_email']) ? $_POST['regist_email'] : '' ?>" class="form-control" id="email" placeholder="mail@exemple.com" required="required">
  	</div>
	<div class="form-group">
		<label for="username">Pseudo</label>
		<input type="text" name="regist_username" value="<?= isset($_POST['regist_username']) ? $_POST['regist_username'] : '' ?>" class="form-control" id="username" placeholder="Votre pseudo" required="required">
	</div>
  	<div class="form-group">
   		<label for="password">Mot de passe</label>
   		<input type="password" name="regist_password" value="<?= isset($_POST['regist_password']) ? $_POST['regist_password'] : '' ?>" class="form-control" id="password" placeholder="Votre mot de passe" required="required">
 	</div>
 	<div class="form-group">
	    <label for="regist_password_confirm">Confirmez le mot de passe</label>
	    <input type="password" name="regist_password_confirm" value="<?= isset($_POST['regist_password_confirm']) ? $_POST['regist_password_confirm'] : '' ?>" class="form-control" id="regist_password_confirm" placeholder="Retapez le même mot de passe" required="required">
  	</div>

  	<button type="submit" class="btn btn-primary" name="regist_submit">Envoyer</button>
</form>
