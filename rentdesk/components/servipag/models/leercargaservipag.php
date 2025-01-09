<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();

// Definir columnas
$columns = "

    fa.id_propiedad,
    fa.id AS ficha_arriendo,
    fa.id_propiedad,
    fa.id as ficha_arriendo,
    fa.token as token,
    fa.token as rut,

    UPPER(
        CONCAT(
            COALESCE(CONCAT(pro.direccion, ' ', pro.numero), ''), 
            CASE 
                WHEN pro.numero_depto IS NOT NULL AND pro.numero_depto <> '' THEN CONCAT(' Dpto ', pro.numero_depto) 
                ELSE '' 
            END, 
            CASE 
                WHEN pro.piso IS NOT NULL AND pro.piso <> 0 THEN CONCAT(' Piso ', pro.piso) 
                ELSE '' 
            END
        )
    ) AS direccion,
    fa.precio,
    (
        SELECT ROUND(valor, 0) 
        FROM propiedades.indicadores 
        WHERE fecha = se.fecha_pago
    ) AS valor_indicador,
    CASE fa.id_moneda_precio
        WHEN 3 THEN ROUND(fa.precio * (
            SELECT ROUND(valor, 0) 
            FROM propiedades.indicadores 
            WHERE fecha = se.fecha_pago
        ), 0)
        ELSE fa.precio
    END AS Valor_arriendo,
    se.monto AS Monto_Pagado,
    CASE fa.id_moneda_precio
        WHEN 3 THEN (
            se.monto - ROUND(fa.precio * (
                SELECT ROUND(valor, 0) 
                FROM propiedades.indicadores 
                WHERE fecha = se.fecha_pago
            ), 0)
        )
        ELSE (se.monto - fa.precio)
    END AS Diferencia,
    fa.id_moneda_precio AS Tipo_moneda,
    se.fecha_pago
";

// Definir joins
$joins = [
    [
        'type' => 'INNER',
        'table' => 'propiedades.servipag se',
        'on' => '(se.id_documento = fa.id OR se.id_documento = fa.contrato_ctrlp OR se.id_documento = fa.contrato_adm)'
    ],
    [
        'type' => 'INNER',
        'table' => 'propiedades.propiedad pro',
        'on' => 'pro.id = fa.id_propiedad'
    ]
];

// Llamar a la función selectAdvanced
$data = $QueryBuilder->selectAdvanced(
    'propiedades.ficha_arriendo fa', // Tabla principal con alias
    $columns,                       // Columnas
    $joins,                         // Joins
    [],                             // Condiciones
    '',                             // Group By
    'se.rut_cliente',               // Order By
    null,                           // Límite
    false,                          // isCount
    false                           // Debug
);

// Retornar los datos como JSON
header('Content-Type: application/json');
echo json_encode($data);

?>
