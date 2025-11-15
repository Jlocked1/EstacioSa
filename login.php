<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$email = $_POST['email'];
$senha = $_POST['senha'];

if($email != NULL && $senha != NULL ) {
    echo 'Login com sucesso. Redirecionando...';
    sleep(1);
    header( 'Location: /dashboard.html' );
}