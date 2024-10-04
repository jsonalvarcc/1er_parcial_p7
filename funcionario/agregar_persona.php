<?php
session_start();
include '../conexion.php';

if ($_SESSION['rol'] !== 'funcionario') {
    header("Location: ./../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $ci = $_POST['ci'];
    $rol = $_POST['rol'];
    $cod_distrito = $_POST['distrito'];
    $cod_zona = $_POST['zona'];

    $consulta = "INSERT INTO Persona (nombre, apellidos, ci, rol, cod_distrito, cod_zona) VALUES ('$nombre', '$apellidos', '$ci', '$rol', '$cod_distrito', '$cod_zona')";
    if ($conexion->query($consulta) === TRUE) {
        header("Location: funcionario.php?mensaje=Persona agregada con éxito.");
        exit();
    } else {
        echo "Error: " . $consulta . "<br>" . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Agregar Persona</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ABC Cuentas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="funcionario.php">Ver Cuentas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Agregar Nueva Persona</h2>
        <form method="post" action="agregar_persona.php">

            <div class="mb-3 row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="apellidos" class="col-sm-2 col-form-label">Apellidos:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="ci" class="col-sm-2 col-form-label">Carnet de Identidad:</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="ci" name="ci" required>
                </div>
            </div>

            <label for="rol" class="form-label">Rol:</label>
            <select id="rol" name="rol" required class="form-control">
                <option value="funcionario">Funcionario</option>
                <option value="usuario">Usuario</option>
            </select>
            <br>
             <!-- distrito selecionar -->
            <div class="mb-3 row">
                <label for="distrito" class="col-sm-2 col-form-label">Distrito:</label>
                <div class="col-sm-10">
                    <select id="distrito" name="distrito" class="form-control" required>
                        <option value="">Seleccione un distrito</option>
                        <?php
                        $distritos = $conexion->query("SELECT * FROM distrito");
                        while ($row = $distritos->fetch_assoc()) {
                            echo "<option value='{$row['cod_distrito']}'>{$row['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
<!-- selecionar zona -->
            <div class="mb-3 row">
                <label for="zona" class="col-sm-2 col-form-label">Zona:</label>
                <div class="col-sm-10">
                    <select id="zona" name="zona" class="form-control" required>
                        <option value="">Seleccione una zona</option>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-success" value="Agregar Persona">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qG9+0A2H8OBf1LZfHZT08/RUas0BO9kGoZP4J0P0g0Y1OrO7vDp3Vt2w9iR5/7o1"
        crossorigin="anonymous"></script>
    <script>
        document.getElementById('distrito').addEventListener('change', function () {
            var distritoId = this.value;

            if (distritoId) {
                var obj_ajx = new XMLHttpRequest();
                obj_ajx.open('GET', 'obtener_zonas.php?distrito_id=' + distritoId, true);
                obj_ajx.onload = function () {
                    if (this.status === 200) {
                        var zonas = JSON.parse(this.responseText);
                        var zonaSelect = document.getElementById('zona');
                        zonaSelect.innerHTML = '<option value="">Seleccione una zona</option>'; 
                        zonas.forEach(function (zona) {
                            zonaSelect.innerHTML += '<option value="' + zona.cod_zona + '">' + zona.nombre + '</option>';
                        });
                    }
                };
                obj_ajx.send();
            } else {
                document.getElementById('zona').innerHTML = '<option value="">Seleccione una zona</option>';
            }
        });
    </script>

</body>

</html>

<?php
$conexion->close();
?>