<?php

namespace Laurent\App\Controllers;

session_start();
setcookie("deco", $deco = 'Vous avez bien été déconnecté.', time()+(2));
session_destroy();
header('Location:connexion');
exit();
