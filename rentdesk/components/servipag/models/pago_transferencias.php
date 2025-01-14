<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

try {
    // Obtener datos enviados desde la solicitud AJAX
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    foreach ($data as $item) {
        $result = $QueryBuilder->executeFunction('propiedades.fn_pago_transferencia', [
            $item['monto_pagado'],
            $item['id_propiedad'],
            $item['fecha_pago'],
            0
        ]);

    

        // actualiza el estado de procesado a true
        $data = ['procesado' => true];
        $conditions = ['id' => $item['id_servipag']];
        $result = $QueryBuilder->update('propiedades.servipag', $data, $conditions);
        var_dump($result);

    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Datos procesados exitosamente'
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status' => 'error',
        'message' => $th->getMessage()
    ]);
}
