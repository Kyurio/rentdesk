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


$idRegistro = @$_POST['idRegistro'];
$idPropiedad = @$_POST['idPropiedad'];
$idPropietario = @$_POST['idPropietario'];
$tokenBeneficiario = @$_POST['tokenBeneficiario'];


if ($tokenBeneficiario) {

    echo "entro al if";

    $queryUpdateInfoCoPropietario = "UPDATE propiedades.propiedad_copropietarios
        SET habilitado = false 
        where token ='$tokenBeneficiario'";
} else {


    echo "entro al else";

    $queryUpdateInfoCoPropietario = "SELECT propiedades.update_habilitado_propietario_beneficiario($idRegistro, $idPropiedad, $idPropietario)";
}


$dataCab = array("consulta" => $queryUpdateInfoCoPropietario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab) {
    echo "true";
} else {
    echo "false";
}
