<?php

session_start();

include("../../../app/model/QuerysBuilder.php");
use app\database\QueryBuilder;

// Instanciar QueryBuilder
$QueryBuilder = new QueryBuilder();

// Obtener parámetro de ficha técnica de forma segura
$id_ficha = filter_input(INPUT_GET, 'ficha_tecnica', FILTER_SANITIZE_NUMBER_INT);

$table = 'propiedades.propiedad_recordatorios';
$conditions = [
    'id_propiedad' => $id_ficha
];

// Llamar a la función
$resultado = $QueryBuilder->select($table, $conditions);

// Preparar respuesta
if ($resultado) {
    echo json_encode([
        'success' => true,
        'data' => $resultado // DataTables espera esta clave para la lista de datos
    ]);
} else {
    echo json_encode([
        'success' => false,
        'data' => [], // Vacío para evitar errores
        'message' => 'No hay datos disponibles.'
    ]);
}
