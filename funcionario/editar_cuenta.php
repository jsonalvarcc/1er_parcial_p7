<?php
session_start();
include '../conexion.php'; 

if (!isset($_GET['id'])) {
    header("Location: funcionario.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT * FROM Persona WHERE cod_persona = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$persona = $resultado->fetch_assoc();

if (!$persona) {
    header("Location: funcionario.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $ci = $_POST['ci'];
    $rol = $_POST['rol'];
    $cod_distrito = $_POST['distrito'];
    $cod_zona = $_POST['zona'];

    $stmt = $conexion->prepare("UPDATE Persona SET nombre = ?, apellidos = ?, ci = ?, rol = ? ,cod_distrito = ?, cod_zona = ? WHERE cod_persona = ?");
    $stmt->bind_param("ssssssi", $nombre, $apellidos, $ci, $rol, $cod_distrito, $cod_zona, $id);
        
    if ($stmt->execute()) {
        header("Location: funcionario.php?mensaje=Cuenta editada exitosamente");
    } else {
        header("Location: funcionario.php?mensaje=Error al editar cuenta");
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Editar Cuenta</title>
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .edit-container {
            max-width: 600px; 
            margin: 100px auto; 
            padding: 20px;
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2 class="text-center">Editar Cuenta</h2>
        <form method="post">
            <div class="mb-3">
                <label for="ci" class="form-label">Carnet de Identidad:</label>
                <input readonly type="text" class="form-control" id="ci" name="ci" value="<?php echo htmlspecialchars($persona['ci']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($persona['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($persona['apellidos']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <input type="text" class="form-control" id="rol" name="rol" value="<?php echo htmlspecialchars($persona['rol']); ?>" required>
            </div>
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
            <button type="submit" class="btn btn-primary">Actualizar Cuenta</button>
        </form>
        <br>
        <a href="funcionario.php" class="btn btn-secondary">Volver al listado</a>
    </div>
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
