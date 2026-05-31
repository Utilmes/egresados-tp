<?php
$host     = 'localhost';
$usuario  = 'root';
$password = '';
$base     = 'egresados_db';

$conexion = mysqli_connect($host, $usuario, $password, $base);

if (!$conexion) {
    die("Error de conexión con la base de datos.");
}

mysqli_set_charset($conexion, 'utf8');
