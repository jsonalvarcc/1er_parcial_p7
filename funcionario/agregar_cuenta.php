<?php
session_start();
include '../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $ci = $_POST['ci'];
    $rol = $_POST['rol'];
    $rol = $_POST['cod_distrito'];
    $rol = $_POST['cod_zona'];

    

    $stmt = $conexion->prepare("INSERT INTO Persona (nombre, apellidos, ci, rol, cod_distrito , cod_zona) VALUES (?, ?, ?, ?, ? , ?)");
    $stmt->bind_param("ssss", $nombre, $apellidos, $ci, $rol);
    
    if ($stmt->execute()) {
        header("Location: funcionario.php?mensaje=Cuenta agregada exitosamente");
    } else {
        header("Location: funcionario.php?mensaje=Error al agregar cuenta");
    }
    
    $stmt->close();
}


$conexion->close();
?>
