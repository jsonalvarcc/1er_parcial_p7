<?php
include '../conexion.php';

if (!isset($_GET['cod_persona'])) {
    echo "ID de persona no especificado.";
    exit();
}

$cod_persona = $_GET['cod_persona'];


$query_distritos = "SELECT cod_distrito, nombre FROM Distrito";
$result_distritos = $conexion->query($query_distritos);
$distritos = $result_distritos->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipo_codigo = $_POST['tipo_codigo']; 
    $xini = $_POST['xini'];
    $yini = $_POST['yini'];
    $xfin = $_POST['xfin'];
    $yfin = $_POST['yfin'];
    $superficie = $_POST['superficie'];
    $cod_distrito = $_POST['cod_distrito'];


    $rango_inicial = ($tipo_codigo == '10000') ? 10000 : (($tipo_codigo == '20000') ? 20000 : 30000);
    $rango_final = $rango_inicial + 9999;

    $query_codigo = "SELECT MAX(codigo_catastral) as max_codigo FROM Catastro WHERE codigo_catastral BETWEEN $rango_inicial AND $rango_final";
    $result_codigo = $conexion->query($query_codigo);
    $max_codigo = $result_codigo->fetch_assoc()['max_codigo'];
    $nuevo_codigo = $max_codigo ? $max_codigo + 1 : $rango_inicial;

  
    $stmt = $conexion->prepare("INSERT INTO Catastro (codigo_catastral, xini, yini, xfin, yfin, cod_distrito, cod_persona, superficie) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiiii", $nuevo_codigo, $xini, $yini, $xfin, $yfin, $cod_distrito, $cod_persona, $superficie);
    
    if ($stmt->execute()) {
       
        header("Location: catastro.php?id=$cod_persona"); 
        exit();
    } else {
        echo "Error al agregar catastro.";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Catastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Catastro</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="tipo_codigo" class="form-label">Tipo de Código Catastral</label>
                <select name="tipo_codigo" id="tipo_codigo" class="form-select" required>
                    <option value="10000">Alto</option>
                    <option value="20000">Medio</option>
                    <option value="30000">Bajo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="xini" class="form-label">Xini</label>
                <input type="number" name="xini" id="xini" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="yini" class="form-label">Yini</label>
                <input type="number" name="yini" id="yini" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="xfin" class="form-label">Xfin</label>
                <input type="number" name="xfin" id="xfin" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="yfin" class="form-label">Yfin</label>
                <input type="number" name="yfin" id="yfin" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="superficie" class="form-label">Superficie</label>
                <input type="number" name="superficie" id="superficie" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cod_distrito" class="form-label">Distrito</label>
                <select name="cod_distrito" id="cod_distrito" class="form-select" required>
                    <?php foreach ($distritos as $distrito): ?>
                        <option value="<?php echo $distrito['cod_distrito']; ?>"><?php echo $distrito['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Catastro</button>
        </form>
    </div>
</body>
</html>
