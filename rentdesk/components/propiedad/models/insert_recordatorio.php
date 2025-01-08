<?php

session_start();

// Asegúrate de cargar tu QueryBuilder y la configuración necesaria
require "../../../app/model/QuerysBuilder.php"; // <-- Ajusta la ruta
use app\database\QueryBuilder;

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

// Instanciamos la configuración si la necesitas
$config       = new Config;
$services     = new ServicesRestful;
$url_services = $config->url_services;

// 1) Recuperamos los datos del formulario (POST)
$id_propiedad        = $_POST['idPropiedad'];
$id_ejecutivo        = $_POST['idEjecutivo'];
$tipo_recordatorio   = $_POST['tipoRecordatorio'];
$nombre_ejecutivo    = $_POST['nombreEjecutivo'];
$fecha_notificacion  = $_POST['fechaNotificacion'];
$descripcion         = $_POST['descripcionRecordatorio'];

// 2) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

// 3) Creamos un array con la información a insertar
$dataToInsert = [
    'id_propiedad'       => $id_propiedad,
    'fecha_notificacion' => $fecha_notificacion,
    'ejecutivo'          => $nombre_ejecutivo,
    'descripcion'        => $descripcion,
    'id_ejecutivo'       => $id_ejecutivo
];

// 4) Llamamos a insert en nuestro QueryBuilder
try {
    $insertResult = $QueryBuilder->insert('propiedades.propiedad_recordatorios', $dataToInsert);

    // 5) Verificamos el resultado 
    if ($insertResult) {
        echo true;
    } else {
        echo false;
    }
} catch (Exception $e) {
    // Manejo de errores en caso de excepciones
    echo false;
}
