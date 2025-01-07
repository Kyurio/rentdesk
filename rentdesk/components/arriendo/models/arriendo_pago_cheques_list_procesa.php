<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio        = $_POST["start"];
@$num_reg        = $_POST["length"];
@$num_reg_principal        = $_POST["length"];

$draw            = @$_POST["draw"];
$inicio            = @$_POST["start"];
@$fin            = @$_POST["length"];
$busqueda         = @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;


$orden         = "";
if (!empty($_POST["order"][0]["column"]))
  $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
  $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$coma = 0;
$signo_coma = "";
$datos        = "";

if ($inicio == "") {
  $inicio = 0;
}
if ($num_reg == "") {
  $num_reg = 99999;
}

$cant_rows = $num_reg;



// if (isset($_GET["idFicha"])) {
//     $idFicha = $_GET["idFicha"];
$queryCheques = "SELECT fa.token as token_arriendo, ficha.fecha_cobro as fecha_cobro, ficha.razon as razon, ficha.monto,
     ficha.monto as monto, banco.nombre as nombre, ficha.girador as girador, fa.id as id, 
     ficha.numero_documento as numero_documento, ficha.cantidad as cantidad, ficha.banco as banco
     FROM propiedades.ficha_arriendo_cheques as ficha
     INNER JOIN propiedades.tp_banco as banco
     ON ficha.banco = banco.id 
     inner join propiedades.ficha_arriendo as fa 
     on fa.id = ficha.id_ficha_arriendo 
     where ficha.habilitado=true
     order by fa.id";
// }

// var_dump("QUERY HISTORIAL: ", $queryCheques);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);


// echo json_encode($objCheques);


$dataCount = array("consulta" => $queryCheques);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objCheques as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;


    $token_arriendo = $result->token_arriendo;
    $ficha_tecnica = $result->id;
    $fecha_cobro = $result->fecha_cobro;
    $razon = $result->razon;
    $monto = $result->monto;
    $nombre = $result->nombre;
    $girador = $result->girador;
    $numero_documento = $result->numero_documento;
    $cantidad = $result->cantidad;
    $banco = $result->banco;


    $ficha_tecnica = "<a href='index.php?component=arriendo&view=arriendo_ficha_tecnica&token=$token_arriendo' class='link-info' > #$ficha_tecnica</a>";

    $datos = $datos . "
     $signo_coma
     [
      \"$ficha_tecnica\",
      \"$fecha_cobro\",
      \"$razon\",
      \"$monto\",
      \"$nombre\",
      \"$girador\",
      \"$numero_documento\",
	  \"$cantidad\",
	  \"$banco\"
    ]";
  }

  echo "
{
  \"draw\": 1,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}";
} else {
  echo "
{
  \"draw\": 0,
  \"recordsTotal\": 0,
  \"recordsFiltered\": 0,
  \"data\": [
    $datos
  ]
}";
}
