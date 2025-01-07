<?php

// ************** bruno ****************

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$id_propiedad = $_POST['idPropiedad'];
$id_ejecutivo = $_POST['idEjecutivo'];
$tipo_recordatorio = $_POST['tipoRecordatorio'];
$nombre_ejecutivo = $_POST['nombreEjecutivo'];
$fecha_notificacion = $_POST['fechaNotificacion'];
$repeticiones = $_POST['repeticionesRecordatorio'];
$descripcion = $_POST['descripcionRecordatorio'];
$frecuencia_recordatorio = $_POST['frecuenciaRecordatorio'];

$query = "INSERT INTO propiedades.propiedad_recordatorios(id_propiedad, fecha_notificacion, ejecutivo, repeticiones, descripcion, id_ejecutivo, frecuencia_recordatorio) VALUES ($id_propiedad, '$fecha_notificacion', '$nombre_ejecutivo', $repeticiones, '$descripcion', '$id_ejecutivo', '$frecuencia_recordatorio')";
$dataCab = array("consulta" => $query);
$resultadoCab = $services->sendPostNoToken($url_services . '/util/dml', $dataCab, []);



if ($resultadoCab) {
    echo true;
} else {
    echo false;
}
