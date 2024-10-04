<?php
session_start();
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci = $_POST['ci'];
    $contrasenia = $_POST['contrasenia'];

    $consulta = "SELECT cod_persona, rol FROM Persona WHERE ci = '$ci' ";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0 && $ci == $contrasenia) {

        $fila = $resultado->fetch_assoc();
        $_SESSION['cod_persona'] = $fila['cod_persona'];
        $_SESSION['rol'] = $fila['rol'];

        if ($fila['rol'] === 'funcionario') {
            header("Location: ./funcionario/funcionario.php"); 
            exit();
        } else {
            header("Location: ./usuario/usuario.php"); 
            exit();
        }
    } else {
        echo "Credenciales incorrectas.";
    }
}

$conexion->close();
?>
