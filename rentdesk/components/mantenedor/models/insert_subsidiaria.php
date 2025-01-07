<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
// $current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);


$component = @$_POST["component"];
$view = @$_POST["view"];
$token = @$_POST["token"];
$item = @$_POST["item"];
$id_recurso = @$_POST["id_recurso"];
$id_item = @$_POST["id_item"];
// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico

$num_reg = 10;
$inicio = 0;





$sbsNombre=$_POST["sbsNombre"];
$sbsRut = @$_POST["sbsRut"];
$sbsActivo = @$_POST["sbsActivo"];
$sbsEmpresa = @$_POST["sbsEmpresa"];
// $tokenRegistro = $_POST["CtaContableToken"];


if($sbsActivo != "on"){
	$sbsActivo = false;
	
}else{
	$sbsActivo = true;
}

$querySucursal = "INSERT INTO propiedades.cuenta_subsidiaria 
( id_empresa ,rut, nombre, activo, habilitada)
VALUES( $id_company, '$sbsRut', '$sbsNombre' ,". ($sbsActivo ? 'true' : 'false') ." , true )  ";

var_dump($$querySucursal);
$dataCab = array("consulta" => $querySucursal);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar Usuario,xxx,-,xxx,";
    return;
}



echo ",xxx,OK,xxx,Usuario Ingresado Correctamente,xxx,-,xxx,";
