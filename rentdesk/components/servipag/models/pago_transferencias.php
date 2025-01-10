<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

if (!isset($_POST['monto']) || !isset($_POST['id_propiedad']) || !isset($_POST['fecha_pago'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron los datos necesarios']);
    exit;
}

$monto = $_POST['monto'];
$id_propiedad = $_POST['id_propiedad'];
$fecha_pago = $_POST['fecha_pago'];

var_dump($monto, $id_propiedad, $fecha_pago);
die();

try {
    $result = $QueryBuilder->executeFunction('propiedades.fn_pago_servipag', [$monto, $id_propiedad, $fecha_pago]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Transferencia procesada correctamente',
        'result' => $result
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status' => 'error',
        'message' => $th->getMessage()
    ]);
}
