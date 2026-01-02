<?php

$hostname = "localhost";
$bancodedados = "db";
$usuario="root";
$senha="root";

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

if($mysqli->connect_errno)
{
    echo "Falha ao conectar: (". $mysqli->connect_errno.")\n".$mysqli->connect_error."\n";
}

// charset UTF-8
$mysqli->set_charset("utf8mb4");