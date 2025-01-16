<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();

// Ejemplo de llamada a selectAdvanced
$resultado = $QueryBuilder->selectAdvanced(
    'propiedades.servipag', // Tabla
    'max(fecha_contab) AS fecha_contab',         // Columnas
    [],                     // JOINs
    [],                     // Condiciones
    '',                     // GROUP BY
    '',                     // ORDER BY
    null,                   // LIMIT
    false,                  // isCount
    false                    // Debug
);


// Retornar los datos como JSON
header('Content-Type: application/json');
echo json_encode($resultado);
