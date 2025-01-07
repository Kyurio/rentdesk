<?php

// 1) Mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// 2) Capturamos los tres parámetros
$codigoPropiedad = $_GET['codigo_propiedad'] ?? '';
$propietario = $_GET['propietario'] ?? '';
$arrendatario = $_GET['arrendatario'] ?? '';

// 3) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    // 4) Definir la consulta SIN condiciones
    $table = 'propiedades.propiedad p';
    $columns = "
        p.id AS propiedad_id,
        CONCAT(p.direccion, ' ', p.numero) AS direccion,
        ep.nombre AS estado_propiedad,
        CASE 
            WHEN p.habilitado = true THEN 'Vigente'
            ELSE 'Retirada'
        END AS estado,
        CONCAT(
          pnProp.nombres, ' ',
          pnProp.apellido_paterno, ' ',
          pnProp.apellido_materno
        ) AS propietario,
        CONCAT(
          pnArr.nombres, ' ',
          pnArr.apellido_paterno, ' ',
          pnArr.apellido_materno
        ) AS arrendatario,
        fa.precio AS precio
    ";

    $joins = [
        ['type' => '', 'table' => 'propiedades.tp_estado_propiedad ep', 'on' => 'p.id_estado_propiedad = ep.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.ficha_arriendo fa', 'on' => 'fa.id_propiedad = p.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.propiedad_copropietarios pcop', 'on' => 'pcop.id_propiedad = p.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_propietario pprop', 'on' => 'pprop.id_persona = pcop.id_propietario'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona persProp', 'on' => 'persProp.id = pprop.id_persona'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_natural pnProp', 'on' => 'pnProp.id_persona = persProp.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.ficha_arriendo_arrendadores faa', 'on' => 'faa.id_ficha_arriendo = fa.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_arrendatario parr', 'on' => 'parr.id_persona = faa.id_arrendatario'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona persArr', 'on' => 'persArr.id = parr.id_persona'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_natural pnArr', 'on' => 'pnArr.id_persona = persArr.id'],
    ];

    // Sin condiciones para que no genere placeholders con punto
    $conditions = [];

    // 5) Obtener todos los registros (sin WHERE)
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',
        '',
        null,
        false,
        false
    );

    // 6) Filtramos manualmente en PHP
    //    Convertimos a minúsculas los valores buscados
    $codigoPropLower = mb_strtolower($codigoPropiedad);
    $propietarioLower = mb_strtolower($propietario);
    $arrendatarioLower = mb_strtolower($arrendatario);

    $filteredResult = array_filter($result, function ($row) use ($codigoPropLower, $propietarioLower, $arrendatarioLower) {
        // Convierto cada columna a minúsculas
        $direccion = mb_strtolower($row['direccion'] ?? '');
        $propietarioName = mb_strtolower($row['propietario'] ?? '');
        $arrendatarioName = mb_strtolower($row['arrendatario'] ?? '');

        // Lógica de comparación:
        // 1) Si $codigoPropLower no está vacío, verificamos que aparezca en 'direccion'
        if (!empty($codigoPropLower) && strpos($direccion, $codigoPropLower) === false) {
            return false;
        }
        // 2) Si $propietarioLower no está vacío, verificamos que aparezca en 'propietario'
        if (!empty($propietarioLower) && strpos($propietarioName, $propietarioLower) === false) {
            return false;
        }
        // 3) Si $arrendatarioLower no está vacío, verificamos que aparezca en 'arrendatario'
        if (!empty($arrendatarioLower) && strpos($arrendatarioName, $arrendatarioLower) === false) {
            return false;
        }

        // Si pasó todas las pruebas, se incluye
        return true;
    });

    // Reindexar el array
    $filteredResult = array_values($filteredResult);

    // 7) Devolver en formato JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
