<?php
@include("../../includes/sql_inyection.php");

echo "";
//************************************************************************************************************
//proceso para las navegaciones

$nav	= @$_GET["nav"];
$token	= @$_GET["token"];
$pag_origen = codifica_navegacion("component=propietario&view=propietario_list_procesa_prop_pago&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=propietario&view=propietario_list";
}	


//************************************************************************************************************

?>