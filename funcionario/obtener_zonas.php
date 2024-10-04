<?php
include '../conexion.php';

if (isset($_GET['distrito_id'])) {
    $distritoId = intval($_GET['distrito_id']);
    $query = "SELECT cod_zona, nombre FROM zona WHERE cod_distrito = $distritoId";
    $resultado = $conexion->query($query);

    $zonas = [];
    while ($row = $resultado->fetch_assoc()) {
        $zonas[] = $row;
    }

    echo json_encode($zonas);
}
?>
