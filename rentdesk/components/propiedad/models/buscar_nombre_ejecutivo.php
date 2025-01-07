<?php

// **** bruno  ******/

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config     = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token = @$_GET['token'];

// Construir y ejecutar la consulta SQL
$query = "SELECT propie.id AS id_propiedad, eje.id AS id_ejecutivo, upper(eje.nombres || ' ' || eje.apellido_paterno || ' ' || eje.apellido_materno) AS ejecutivo FROM propiedades.propiedad propie INNER JOIN propiedades.cuenta_usuario eje ON propie.id_ejecutivo = eje.id WHERE propie.token = '$token'";


$data = array("consulta" => $query);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);


echo $resultado;