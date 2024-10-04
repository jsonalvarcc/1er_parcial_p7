<?php
session_start();
include '../conexion.php'; 


if (!isset($_SESSION['cod_persona'])) {
    header("Location: ../index.php"); 
    exit();
}


$id_persona = $_SESSION['cod_persona'];


$stmt = $conexion->prepare("SELECT * FROM Persona WHERE cod_persona = ?");
$stmt->bind_param("i", $id_persona);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();


if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}


$stmt_catastro = $conexion->prepare("SELECT c.*, d.nombre AS nombre_distrito FROM Catastro c INNER JOIN Distrito d ON c.cod_distrito = d.cod_distrito WHERE c.cod_persona = ?");
$stmt_catastro->bind_param("i", $id_persona);
$stmt_catastro->execute();
$resultado_catastro = $stmt_catastro->get_result();
$catastros = $resultado_catastro->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Datos del Usuario</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Datos del Usuario</h2>

        <div class="mb-4">
            <h4>Información Personal</h4>
            <p><strong>Carnet de Identidad:</strong> <?php echo htmlspecialchars($usuario['ci']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($usuario['apellidos']); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rol']); ?></p>
        </div>

        <div class="mb-4">
            <h4>Catastros Asociados</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código Catastral</th>
                        <th>Xini</th>
                        <th>Yini</th>
                        <th>Xfin</th>
                        <th>Yfin</th>
                        <th>
                            Superficie
                        </th>

                        <th>Nombre del Distrito</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($catastros) > 0): ?>
                        <?php foreach ($catastros as $catastro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($catastro['id']); ?></td>
                                <td><?php echo htmlspecialchars($catastro['codigo_catastral']); ?></td>

                                <td><?php echo htmlspecialchars($catastro['xini']); ?></td>
                                <td> <?php echo htmlspecialchars($catastro['yini']); ?></td>
                                <td><?php echo htmlspecialchars($catastro['xfin']); ?></td>
                                <td> <?php echo htmlspecialchars($catastro['yfin']); ?></td>
                       
                                <td> <?php echo htmlspecialchars($catastro['superficie']); ?></td>

                                <td><?php echo htmlspecialchars($catastro['nombre_distrito']); ?></td>
                                <td>    <a class="btn btn-info" href="http://localhost:8080/preg4_java/impuesto?codigo_catastral=<?php echo $catastro['codigo_catastral']; ?>" target="_blank">Ver Impuesto</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay catastros asociados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="../funcionario/funcionario.php" class="btn btn-secondary">Volver</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qG9+0A2H8OBf1LZfHZT08/RUas0BO9kGoZP4J0P0g0Y1OrO7vDp3Vt2w9iR5/7o1" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conexion->close();
?>
