<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();


$data = [];

function formatearRut($rut)
{
    $rut = preg_replace('/[^0-9kK]/', '', $rut); // Eliminar cualquier carácter que no sea número o 'K'
    $cuerpo = ltrim(substr($rut, 0, -1), '0');  // Parte numérica sin ceros a la izquierda
    $dv = substr($rut, -1);                     // Dígito verificador

    // Agregar el guion y retornar el RUT formateado
    return $cuerpo . '-' . strtoupper($dv);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== false) {
        while (($line = fgets($handle)) !== false) {
            // Extraer campos basados en posiciones
            $data = [
                'canal_de_pago' => (int)trim(substr($line, 0, 3)),          // Canal de Pago (3)
                'oficina' => (int)trim(substr($line, 3, 3)),               // Oficina (3)
                'txcliente' => trim(substr($line, 6, 30)),                 // TxCliente (30)
                'id_documento' => trim(substr($line, 36, 20)),             // Id_Documento (20)
                'boleta' => trim(substr($line, 56, 40)),                   // Boleta (40)
                'rut_cliente' => formatearRut(trim(substr($line, 96, 11))),     // Rut Cliente (11)
                'id_pago' => (int)trim(substr($line, 107, 12)),            // Id Pago (12)
                'monto' => (float)trim(substr($line, 119, 8)),             // Monto (8) - Debería ser un float, no int
                'medio_pago' => trim(substr($line, 127, 2)),               // Medio Pago (2)
                'nro_serie_doc' => trim(substr($line, 129, 12)),           // Nro_serie_doc (12)
                'banco' => trim(substr($line, 141, 3)),                    // Banco (3)
                'plaza_banco' => trim(substr($line, 144, 4)),              // Plaza_banco (4)
                'ctacte' => trim(substr($line, 148, 12)),                  // Ctacte (12)
                'fecha_pago' => trim(substr($line, 160, 8)),               // Fecha Pago (8)
                'hora' => trim(substr($line, 168, 6)),                     // Hora (6)
                'fecha_contab' => trim(substr($line, 174, 8)),             // Fecha Contab (8)
                'tipo_trx' => trim(substr($line, 182, 1)),                 // Tipo Trx (1)
                'procesado' => 0
            ];


            // Insertar el registro en la tabla
            try {
                $result = $QueryBuilder->insert('propiedades.servipag', $data);
                if ($result) {
                    echo true;
                }  // Para ver si el resultado es exitoso
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

        }
        fclose($handle);
    }
}

?>