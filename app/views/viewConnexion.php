<?php
use Laurent\App\Service\Flash;
$this->_t = 'Connexion';

FLASH::flash();
FLASH::cookieFlash('deco', 'success');
FLASH::cookieFlash('BRUTEFORCE', 'danger');
if(isset($_COOKIE['BRUTEFORCE'])) :
?>
<form action="connexion" method="post" >
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" readonly="readonly" disabled="disabled" name="connect_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="pass">Mot de Passe</label>
    <input type="password" readonly="readonly" disabled="disabled" name="connect_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <button type="submit" readonly="readonly" disabled="disabled" class="btn btn-primary" name="connect_submit">Se connecter</button>
</form>
<?php else : ?>
<form action="connexion" method="post" >
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="connect_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>
  <div class="form-group">
    <label for="pass">Mot de Passe</label>
    <input type="password" name="connect_pass" value="" class="form-control" id="pass" placeholder="Mot de Passe">
  </div>
  <p><a href="http://localhost/Blog_Avril_Laurent/passForgotten">Mot de passe oublié</a></p>
  <button type="submit" class="btn btn-primary" name="connect_submit">Se connecter</button>
</form>
<?php endif ?>
