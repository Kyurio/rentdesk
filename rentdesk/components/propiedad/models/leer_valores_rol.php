<?php
session_start();
include("../../../configuration.php"); // Incluye la configuración
include("../../../includes/funciones.php"); // Incluye funciones adicionales
include("../../../includes/services_util.php"); // Incluye servicios útiles

class Propiedades
{
    protected $services;
    protected $url_services;

    public function __construct()
    {
        $config = new Config;
        $this->services = new ServicesRestful;
        $this->url_services = $config->url_services;
    }

    public function executeQuery($query)
    {
        $data = [
            "consulta" => $query,
            "cantRegistros" => 9999,
            "numPagina" => 1
        ];
        $result = $this->services->sendPostNoToken($this->url_services . '/util/paginacion', $data, []);
        return json_decode($result);
    }

    public function getValoresRoles($token)
    {
        try {
            $query = "SELECT  CASE 
               WHEN principal = TRUE THEN 'Sí'
               ELSE 'No'
           END AS principal, a.token AS token_rol , a.numero , b.token AS token_propiedad FROM propiedades.propiedad_roles a 
           INNER JOIN propiedades.propiedad b
           ON a.id_propiedad = b.id
           
            WHERE b.token='$token'
            order by principal desc";
            $results = $this->executeQuery($query);
            return $results;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}


// Instancia la clase y llama al método getValoresRoles
$propiedades = new Propiedades();
$token = $_GET['token'];
$resultado = $propiedades->getValoresRoles($token);
echo json_encode($resultado);