<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos.css">
    <title>Iniciar Sesión</title>
   
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form method="post" action="autenticacion.php" class="form mt-4">
            <div class="mb-3 row">
                <label for="ci" class="col-sm-4 col-form-label">CI:</label>
                <div class="col-sm-8">
                    <input type="text" name="ci" class="form-control" id="ci" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="contrasenia" class="col-sm-4 col-form-label">Contraseña:</label>
                <div class="col-sm-8">
                    <input type="password" name="contrasenia" class="form-control" id="contrasenia" required>
                </div>
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-success" value="Iniciar Sesión">
            </div>
        </form>
    </div>
</body>
</html>
