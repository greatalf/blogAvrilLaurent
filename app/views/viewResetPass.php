<?php
use Laurent\App\Service\Flash;
$this->_t = 'Nouveau mot de passe';

FLASH::flash();
FLASH::cookieFlash('deco', 'success');
FLASH::cookieFlash('BRUTEFORCE', 'danger');
?>
<form action="" method="post" >
  <div class="form-group">
    <label for="pass1">Nouveau mot de passe :</label>
    <input type="password" name="pass1" value="" class="form-control" id="pass1">
  </div> 
  <div class="form-group">
    <label for="pass2">Confirmation de mot de passe :</label>
    <input type="password" name="pass2" value="" class="form-control" id="pass2">
  </div>
  <button type="submit" class="btn btn-primary" name="forget_password">Enregistrer</button>
</form>