<?php
use Laurent\App\Service\Flash;
$this->_t = 'Mot de passe oublié';

FLASH::flash();
FLASH::cookieFlash('deco', 'success');
FLASH::cookieFlash('BRUTEFORCE', 'danger');
?>
<form action="" method="post" >
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="connect_email" value="" class="form-control" id="email" placeholder="nom@exemple.com">
  </div>  
  <button type="submit" class="btn btn-primary" name="connect_submit">Envoyer</button>
</form>
