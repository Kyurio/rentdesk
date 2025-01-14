<?php

session_start();

// Asegúrate de cargar tu QueryBuilder y la configuración necesaria
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

// Instanciamos la configuración
$config       = new Config;
$services     = new ServicesRestful;
$url_services = $config->url_services;

// 1) Recuperar los datos del formulario
$id_propiedad        = $_POST['idPropiedad'] ?? null;
$tipo_recordatorio   = $_POST['tipoRecordatorio'] ?? null;
$fecha_notificacion  = $_POST['fechaNotificacion'] ?? null;
$descripcion         = $_POST['descripcionRecordatorio'] ?? null;


// Recibir la lista de IDs de ejecutivos (vienen separados por coma)
$id_ejecutivos = isset($_POST['idEjecutivos']) ? explode(',', $_POST['idEjecutivos']) : [];

// Validar que al menos un ejecutivo ha sido seleccionado
if (empty($id_ejecutivos) || empty($id_propiedad)) {
    echo json_encode(['error' => true, 'message' => 'Debe completar todos los campos correctamente.']);
    exit;
}

// Validar formato de ID
$id_ejecutivos = array_filter($id_ejecutivos, function ($value) {
    return ctype_digit($value) && $value !== '';
});

// 2) Instanciar el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    $inserciones_exitosas = 0;

    // 3) Insertar un registro por cada ejecutivo
    foreach ($id_ejecutivos as $id_ejecutivo) {
        $id_ejecutivo = (int) $id_ejecutivo;
        $id_propiedad = (int) $id_propiedad;

        // Crear array para la inserción
        $dataToInsert = [
            'id_propiedad'       => $id_propiedad,
            'fecha_notificacion' => $fecha_notificacion,
            'id_ejecutivo'       => $id_ejecutivo,
            'descripcion'        => $descripcion,
            'tipo_recordatorio'  => $tipo_recordatorio
        ];

        // Ejecutar la inserción
        $insertResult = $QueryBuilder->insert('propiedades.propiedad_recordatorios', $dataToInsert);

        if ($insertResult) {
            $inserciones_exitosas++;
        }
    }

    // 4) Confirmar resultado
    if ($inserciones_exitosas > 0) {
        echo json_encode(['success' => true, 'message' => "$inserciones_exitosas recordatorio(s) insertado(s)."]);
    } else {
        echo json_encode(['error' => true, 'message' => 'No se pudo insertar ningún recordatorio.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
