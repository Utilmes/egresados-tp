<?php
// Ocultar errores de PHP al usuario (Unidad 3 - Manejo de errores)
ini_set('display_errors', 0);
error_reporting(0);

$host     = 'localhost';
$usuario  = 'root';
$clave_bd = '';
$base     = 'egresados_db';

$conexion = mysqli_connect($host, $usuario, $clave_bd, $base);

if (!$conexion) {
    die("Error de conexión con la base de datos.");
}

mysqli_set_charset($conexion, 'utf8');
