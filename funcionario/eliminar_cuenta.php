<?php
session_start();
include '../conexion.php'; 

if (!isset($_GET['id'])) {
    header("Location: funcionario.php");
    exit();
}

$id = $_GET['id'];


$stmt = $conexion->prepare("DELETE FROM Persona WHERE cod_persona = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: funcionario.php?mensaje=Cuenta eliminada exitosamente");
} else {
    header("Location: funcionario.php?mensaje=Error al eliminar cuenta");
}

$stmt->close();

$conexion->close();
?>
