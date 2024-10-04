<?php

$host = "localhost"; 
$usuario = "root"; 
$contraseña = "";
$base_datos = "bdjeyson"; 

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Conexion fallida: " . $conexion->connect_error);
}
?>
