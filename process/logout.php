<?php
require_once '../functions/auth.php';
require_once '../functions/helpers.php';

fazer_logout();
definir_sucesso("Logout realizado com sucesso!");
redirecionar('../teladelogin.php');