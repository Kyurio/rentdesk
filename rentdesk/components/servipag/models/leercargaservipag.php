<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();



// Realizar el SELECT con la función selectAdvanced
$data = $QueryBuilder->selectAdvanced(
    'propiedades.servipag', // Tabla
    '*',                    // Columnas
    [],                     // Joins
    [],                     // Condiciones
    '',                     // Group By
    '',                     // Order By
    null,                   // Límite
    false,                  // isCount
    false                   // Debug
);

// Retornar los datos como JSON para ser consumidos por DataTables
header('Content-Type: application/json');
echo json_encode($data);


?>