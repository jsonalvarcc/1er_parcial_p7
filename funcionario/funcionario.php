<?php session_start();
include '../conexion.php';

if ($_SESSION['rol'] !== 'funcionario') {
    header("Location: ./../index.php");
    exit();
}

// Obtener todas las personas
$resultado = $conexion->query("SELECT * FROM Persona");

// Mensaje si existe
if (isset($_GET['mensaje'])) {
    echo "<p>" . htmlspecialchars($_GET['mensaje']) . "</p>";
}

// Consultar distritos y zonas para poder mostrarlos más adelante
$distritos = $conexion->query("SELECT cod_distrito, nombre FROM Distrito");
$zonas = $conexion->query("SELECT cod_zona, nombre FROM Zona");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>ABC Cuentas</title>
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
                        <a class="nav-link" href="agregar_persona.php">Agregar Persona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>ABC de Cuentas de Personas</h2>

        <h3 class="mt-4">Listado de Cuentas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>C.I.</th>
                    <th>Rol</th>
                    <th>Distrito</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>    
                        <td><?php echo $fila['cod_persona']; ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($fila['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($fila['ci']); ?></td>
                        <td><?php echo htmlspecialchars($fila['rol']); ?></td>

                        <?php
                        // Obtener el nombre del distrito
                        $distritoNombre = '';
                        if ($fila['cod_distrito']) {
                            $distritoConsulta = $conexion->query("SELECT nombre FROM Distrito WHERE cod_distrito = " . $fila['cod_distrito']);
                            if ($distritoConsulta->num_rows > 0) {
                                $distrito = $distritoConsulta->fetch_assoc();
                                $distritoNombre = htmlspecialchars($distrito['nombre']);
                            }
                        }

                        // Obtener el nombre de la zona
                        $zonaNombre = '';
                        if ($fila['cod_zona']) {
                            $zonaConsulta = $conexion->query("SELECT nombre FROM Zona WHERE cod_zona = " . $fila['cod_zona']);
                            if ($zonaConsulta->num_rows > 0) {
                                $zona = $zonaConsulta->fetch_assoc();
                                $zonaNombre = htmlspecialchars($zona['nombre']);
                            }
                        }
                        ?>

                        <td><?php echo $distritoNombre; ?></td>
                        <td><?php echo $zonaNombre; ?></td>
                        <td>
                            <a class="btn btn-success"
                                href="catastro.php?id=<?php echo $fila['cod_persona']; ?>">Catastro</a>
                            <a class="btn btn-info"
                                href="editar_cuenta.php?id=<?php echo $fila['cod_persona']; ?>">Editar</a>
                            <a class="btn btn-warning"
                                href="eliminar_cuenta.php?id=<?php echo $fila['cod_persona']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qG9+0A2H8OBf1LZfHZT08/RUas0BO9kGoZP4J0P0g0Y1OrO7vDp3Vt2w9iR5/7o1"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
$conexion->close();
?>
