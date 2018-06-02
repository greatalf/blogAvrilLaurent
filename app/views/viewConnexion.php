<?php
$this->_t = 'Connexion';

SESSION::flash();
SESSION::cookieFlash('denied');
?>
<form action="connexion" method="post" >
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="connect_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="pass">Mot de Passe</label>
    <input type="password" name="connect_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <button type="submit" class="btn btn-primary">Se connecter</button>
</form>
