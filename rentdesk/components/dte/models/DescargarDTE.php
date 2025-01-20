<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de parámetros del servicio
$rutEmpresa = "77367969K";
$tipoDocto = $_POST["tipo_documento"];
$folioDocto = $_POST["folio"];
$usuario = "RENTA";
$password = "RENTA";

// URL del servicio SOAP
$wsdl = "https://dteqa.arpis.cl/wsconsulta/avanzado.asmx?WSDL";

try {
    // Inicializar el cliente SOAP
    $client = new SoapClient($wsdl, [
        'trace' => true,
        'exceptions' => true,
    ]);

    // Crear los parámetros para la solicitud
    $params = [
        "RutEmpresa" => $rutEmpresa,
        "TipoDocto" => $tipoDocto,
        "FolioDocto" => $folioDocto,
        "Usuario" => $usuario,
        "PWD" => $password,
    ];

    // Llamar al método getLinkPDF
    $response = $client->__soapCall("getLinkPDF", [$params]);

    // Verificar la respuesta
    if (isset($response->getLinkPDFResult) && is_object($response->getLinkPDFResult)) {
        $pdfResult = $response->getLinkPDFResult;

        // Extraer el enlace del campo MsgEstatus
        if (isset($pdfResult->MsgEstatus) && filter_var($pdfResult->MsgEstatus, FILTER_VALIDATE_URL)) {
            $pdfLink = $pdfResult->MsgEstatus;

            // Devolver la URL en JSON
            echo json_encode(["pdfLink" => $pdfLink]);
        } else {
            echo json_encode(["error" => "El enlace al PDF no es válido o no se encuentra disponible."]);
        }
    } else {
        echo json_encode(["error" => "No se encontró un resultado válido en la respuesta del servicio."]);
    }
} catch (SoapFault $e) {
    echo json_encode(["error" => "Error SOAP: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
