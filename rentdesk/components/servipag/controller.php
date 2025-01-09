<?php
$view = @$_GET["view"];

echo "<script>alert('llego')</script>";

if ($view == "servipag") {
    include("views/servipag.php");
}


