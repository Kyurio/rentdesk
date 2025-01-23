<?php
session_start();

// Ajusta la ruta de tu QueryBuilder según tu estructura de carpetas.
require_once "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Verifica que la sesión contenga el id del usuario.
if (!isset($_SESSION["rd_usuario_id"])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No existe el ID de usuario en la sesión'
    ]);
    exit;
}

$id_usuario = $_SESSION["rd_usuario_id"];
$password   = $_POST["password"];

$password = md5($password);


// Instanciar la clase QueryBuilder.
$QueryBuilder = new QueryBuilder();

try {
    // Armamos el array con la data que queremos actualizar.
    $data = [
        'password' => $password
    ];

    // Y definimos las condiciones del WHERE.
    $conditions = [
        'token' => $id_usuario
    ];

    // Ejecutar la actualización.
    $updateResult = $QueryBuilder->update(
        'propiedades.cuenta_usuario',
        $data,
        $conditions
    );

    // Manejo de la respuesta según lo que devuelva $updateResult.
    // Esto dependerá de tu método execute (ejemplo: si retorna true/false o número de filas).
    if ($updateResult) {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Contraseña actualizada correctamente',
            'result'  => $updateResult
        ]);
    } else {
        echo json_encode([
            'status'  => 'warning',
            'message' => 'No se pudo actualizar la contraseña, o no hubo cambios.',
            'result'  => $updateResult
        ]);
    }
} catch (Throwable $th) {
    // Capturamos cualquier excepción.
    echo json_encode([
        'status'  => 'error',
        'message' => $th->getMessage()
    ]);
}
