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

var_dump("idRegistro: ", $idRegistro);
var_dump("idPropiedad: ", $idPropiedad);
var_dump("idPropietario: ", $idPropietario);
var_dump("tokenBeneficiario: ", $tokenBeneficiario);

if ($tokenBeneficiario) {
    $queryUpdateInfoCoPropietario = "UPDATE propiedades.propiedad_copropietarios
        SET habilitado = false 
        where token ='$tokenBeneficiario'";
} else {
    $queryUpdateInfoCoPropietario = "SELECT propiedades.update_habilitado_propietario_beneficiario($idRegistro, $idPropiedad, $idPropietario)";
}

// var_dump($queryUpdateInfoCoPropietario);
// return;
// $queryUpdateInfoCoPropietario = "DELETE FROM propiedades.propiedad_copropietarios
// WHERE token ='$tokenInfoCoPropietario'";
// var_dump("QUERY UPDATE: ",$queryUpdateInfoCoPropietario );
$dataCab = array("consulta" => $queryUpdateInfoCoPropietario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab) {
    echo "true";
} else {
    echo "false";
}
