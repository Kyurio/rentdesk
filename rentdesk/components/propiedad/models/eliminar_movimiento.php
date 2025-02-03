<?php
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Instancia de tu QueryBuilder
$QueryBuilder = new QueryBuilder();

// Recibir parámetro POST
$idcc = $_POST['idcc'] ?? null;

// Validación básica
if (empty($idcc)) {
    echo json_encode(['error' => 'idcc no proporcionado']);
    exit;
}

// Definir columnas a seleccionar
$columns = 'pc.id_propietario';

// Definir el JOIN
$joins = [
    [
        'type'  => 'JOIN',
        'table' => 'propiedades.ficha_arriendo fa',
        'on'    => 'pc.id_propiedad = fa.id_propiedad'
    ]
];

// Definir la condición WHERE
$conditions = [
    'fa.id' => $idcc
];

// Llamar a selectAdvanced
$result = $QueryBuilder->selectAdvanced(
    'propiedades.propiedad_copropietarios pc',  // FROM ...
    $columns,                                   // SELECT ...
    $joins,                                     // JOIN ...
    $conditions                                 // WHERE ...
    // demás parámetros por defecto
);

// Asignar el primer valor encontrado a la variable $id_propietario
if (!empty($result)) {
    // Tomamos la primera fila
    $id_propietario = $result[0]['id_propietario'];

    try {
        // Ejecutar la función en la BD
        $result = $QueryBuilder->executeFunction('propiedades.fn', [$idcc, $id_propietario]);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Movimiento eliminado correctamente',
            'result'  => $result // si quieres devolver algo adicional
        ]);
    } catch (Throwable $th) {
        echo json_encode([
            'status'  => 'error',
            'message' => $th->getMessage()
        ]);
    }
} else {
    // Si no hay resultados, devuelves vacío
    echo json_encode([]);
}
