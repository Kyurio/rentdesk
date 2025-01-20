
<?php


include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;
use LDAP\Result;

$QueryBuilder = new QueryBuilder();
$config = new Config();

$idLiquidacion = $_POST['id_liquidacion'];

try {

    // Definir la tabla y las condiciones
    // Tabla base y columnas especificadas
    $table = 'propiedades.propiedad_liquidaciones pl';
    $columns = "
        pl.id AS liquidacion,
        pl.id_ficha_propiedad AS ficha_propiedad,
        pl.id_propietario,
        per.dni AS dni,
        tre.nombre AS region,
        tco.nombre AS comuna,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.nombres) ELSE NULL END AS nombre,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.apellido_paterno) ELSE NULL END AS apellido_paterno,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.apellido_materno) ELSE NULL END AS apellido_materno,
        CASE WHEN per.id_tipo_persona <> 1 THEN TRIM(pj.razon_social) ELSE NULL END AS razon_social,
        UPPER(
            COALESCE(p.direccion, '') || ' ' || 
            COALESCE(p.numero, '') || 
            CASE 
                WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN ' Dpto ' || p.numero_depto 
                ELSE '' 
            END || 
            CASE 
                WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN ' Piso ' || p.piso 
                ELSE '' 
            END
        ) AS direccion,
        p.numero AS numero,
        CASE WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN 'Dpto ' || p.numero_depto ELSE NULL END AS depto,
        CASE WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN 'Piso ' || p.piso ELSE NULL END AS piso,
        TO_CHAR(pl.fecha_liquidacion::DATE, 'DD/MM/YYYY') AS fecha_liquidacion,
        pl.id_ficha_arriendo AS ficha_arriendo,
        pl.cierre AS cierre,
        pcl.iva AS iva,
        pcl.tipo_comision AS razon,
        pcl.monto AS monto,
        pcl.id_liquidacion,
        pl.porcentaje_participacion,
	    pcl.id AS id_liquidacion_comision,
        pcl.id

    ";

    // Definir los JOINs
    $joins = [
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad_comision_liquidacion pcl',
            'on' => 'pcl.id_liquidacion = pl.id'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad p',
            'on' => 'p.id = pl.id_ficha_propiedad'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.persona per',
            'on' => 'per.id = pl.id_propietario'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_natural pn',
            'on' => 'pn.id_persona = pl.id_propietario'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_juridica pj',
            'on' => 'pj.id_persona = pl.id_propietario'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.tp_comuna tco',
            'on' => 'tco.id = p.id_comuna'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.tp_region tre',
            'on' => 'tre.id = tco.id_region'
        ]
    ];

    // Condiciones WHERE
    $conditions = [
        'id_liquidacion' => $idLiquidacion,
        'tipo_comision' => ['IN', ['COMISIÓN ARRIENDO', 'COMISIÓN ADMINISTRACIÓN']]
    ];


    // Llamada a `selectAdvanced`
    $resultadoQuery = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',           // groupBy
        '',           // orderBy
        null,         // limit
        false,        // isCount
        false          // debug para ver el SQL generado
    );



    // url del servicio
    $fecha =  date('Y-m-d') . 'T' . date(format: 'H:i:s');


    // Crear carpeta para guardar PDFs si no existe
    if (!file_exists('boletas')) {
        mkdir('boletas', 0777, true);
    }

    // genera el xml de la boleta
    function generarXMLBoleta($data)
    {
        // Crear un nuevo objeto DOMDocument
        $dom = new DOMDocument('1.0', 'ISO-8859-1');
        $dom->formatOutput = true;

        // Crear el elemento raíz <EnvioBOLETA>
        $envioBoleta = $dom->createElement('EnvioBOLETA');
        $envioBoleta->setAttribute('version', '1.0');
        $envioBoleta->setAttribute('xmlns', 'http://www.sii.cl/SiiDte');
        $envioBoleta->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $envioBoleta->setAttribute('xsi:schemaLocation', 'http://www.sii.cl/SiiDte EnvioBOLETA_v11.xsd');
        $dom->appendChild($envioBoleta);

        // Crear el SetDTE
        $setDTE = $dom->createElement('SetDTE');
        $setDTE->setAttribute('ID', 'ENVBOL-' . date('YmdHis'));
        $envioBoleta->appendChild($setDTE);

        // Crear la Caratula
        $caratula = $dom->createElement('Caratula');
        $caratula->setAttribute('version', '1.0');
        $setDTE->appendChild($caratula);

        // Agregar elementos a Caratula
        $caratula->appendChild($dom->createElement('RutEmisor', $data['rut_emisor']));
        $caratula->appendChild($dom->createElement('RutEnvia', $data['rut_envia']));
        $caratula->appendChild($dom->createElement('RutReceptor', $data['rut_receptor']));
        $caratula->appendChild($dom->createElement('FchResol', $data['fch_resol']));
        $caratula->appendChild($dom->createElement('NroResol', $data['nro_resol']));
        $caratula->appendChild($dom->createElement('TmstFirmaEnv', $data['fechahora']));
        // SubTotDTE
        $subTotDTE = $dom->createElement('SubTotDTE');
        $subTotDTE->appendChild($dom->createElement('TpoDTE', '39'));
        $subTotDTE->appendChild($dom->createElement('NroDTE', '1'));
        $caratula->appendChild($subTotDTE);

        // Crear el DTE
        $dte = $dom->createElement('DTE');
        $dte->setAttribute('version', '1.0');
        $setDTE->appendChild($dte);

        // Crear el Documento
        $documento = $dom->createElement('Documento');
        $documento->setAttribute('ID', 'R' . $data['rut_emisor'] . 'T39F' . $data['folio']);
        $dte->appendChild($documento);

        // Encabezado
        $encabezado = $dom->createElement('Encabezado');
        $documento->appendChild($encabezado);

        // IdDoc
        $idDoc = $dom->createElement('IdDoc');
        $idDoc->appendChild($dom->createElement('TipoDTE', '39'));
        $idDoc->appendChild($dom->createElement('Folio', $data['folio']));
        $idDoc->appendChild($dom->createElement('FchEmis', $data['fch_emis']));
        $idDoc->appendChild($dom->createElement('IndServicio', '3'));
        $idDoc->appendChild($dom->createElement('FchVenc', $data['fecha_vencmimeinto']));

        // Agregar FchVenc si está disponible
        if (!empty($data['fch_venc'])) {
            $idDoc->appendChild($dom->createElement('FchVenc', $data['fch_venc']));
        }

        $encabezado->appendChild($idDoc);

        // Emisor
        $emisor = $dom->createElement('Emisor');
        $emisor->appendChild($dom->createElement('RUTEmisor', $data['rut_emisor']));
        $emisor->appendChild($dom->createElement('RznSocEmisor', $data['razon_social_emisor']));
        $emisor->appendChild($dom->createElement('GiroEmisor', $data['giro_emisor']));
        $emisor->appendChild($dom->createElement('CdgSIISucur', '999999999'));
        $emisor->appendChild($dom->createElement('DirOrigen', $data['dir_origen']));
        $emisor->appendChild($dom->createElement('CmnaOrigen', $data['comuna_origen']));
        $emisor->appendChild($dom->createElement('CiudadOrigen', $data['ciudad_origen']));
        $encabezado->appendChild($emisor);

        // Receptor
        $receptor = $dom->createElement('Receptor');
        $receptor->appendChild($dom->createElement('RUTRecep', $data['rutrecep']));
        $receptor->appendChild($dom->createElement('RznSocRecep', $data['razon_social_receptor'] ?? ''));
        $receptor->appendChild($dom->createElement('DirRecep', $data['DirRecep']));
        $receptor->appendChild($dom->createElement('CmnaRecep', $data['CmnaRecep']));
        $receptor->appendChild($dom->createElement('CiudadRecep', $data['CiudadRecep']));
        $receptor->appendChild($dom->createElement('Contacto', $data['Contacto']));
        $receptor->appendChild($dom->createElement('DirPostal', $data['DirPostal']));
        $receptor->appendChild($dom->createElement('CmnaPostal', $data['CmnaPostal']));

        // Agregar DirRecep, CmnaRecep y CiudadRecep si están disponibles
        if (!empty($data['dir_recep'])) {
            $receptor->appendChild($dom->createElement('DirRecep', $data['dir_recep']));
        }
        if (!empty($data['cmna_recep'])) {
            $receptor->appendChild($dom->createElement('CmnaRecep', $data['cmna_recep']));
        }
        if (!empty($data['ciudad_recep'])) {
            $receptor->appendChild($dom->createElement('CiudadRecep', $data['ciudad_recep']));
        }

        $encabezado->appendChild($receptor);

        // Totales
        $totales = $dom->createElement('Totales');
        $totales->appendChild($dom->createElement('MntNeto', $data['mnt_bruto']));
        $totales->appendChild($dom->createElement('IVA', $data['iva']));
        $totales->appendChild($dom->createElement('MntTotal', $data['mnt_total']));
        $encabezado->appendChild($totales);

        // Detalle
        $detalle = $dom->createElement('Detalle');
        $documento->appendChild($detalle);

        // Detalle de línea
        $detalle->appendChild($dom->createElement('NroLinDet', '1'));

        // CdgItem
        if (!empty($data['cdg_item_tipo']) && !empty($data['cdg_item_valor'])) {
            $cdgItem = $dom->createElement('CdgItem');
            $cdgItem->appendChild($dom->createElement('TpoCodigo', $data['cdg_item_tipo']));
            $cdgItem->appendChild($dom->createElement('VlrCodigo', $data['cdg_item_valor']));
            $detalle->appendChild($cdgItem);
        }

        $detalle->appendChild($dom->createElement('NmbItem', $data['nombre_item']));

        // DscItem
        if (!empty($data['descripcion_item'])) {
            $detalle->appendChild($dom->createElement('DscItem', $data['descripcion_item']));
        }

        $detalle->appendChild($dom->createElement('QtyItem', $data['cantidad_item']));

        // UnmdItem
        if (!empty($data['unidad_medida'])) {
            $detalle->appendChild($dom->createElement('UnmdItem', $data['unidad_medida']));
        }

        $detalle->appendChild($dom->createElement('PrcItem', $data['precio_item']));
        $detalle->appendChild($dom->createElement('MontoItem', $data['mnt_total']));

        // DD (Datos del Documento)
        $dd = $dom->createElement('DD');
        $dd->appendChild($dom->createElement('RE', $data['rut_emisor']));
        $dd->appendChild($dom->createElement('TD', '39'));
        $dd->appendChild($dom->createElement('F', $data['folio']));
        $dd->appendChild($dom->createElement('FE', $data['fch_emis']));
        $dd->appendChild($dom->createElement('RR', $data['rut_receptor']));
        $dd->appendChild($dom->createElement('RSR', $data['razon_social_receptor'] ?? ''));
        $dd->appendChild($dom->createElement('MNT', $data['mnt_total']));
        $dd->appendChild($dom->createElement('IT1', $data['nombre_item']));

        // CAF (Código de Autorización de Folios) - Placeholder
        $caf = $dom->createElement('CAF');
        $caf->setAttribute('version', '1.0');

        $da = $dom->createElement('DA');
        $da->appendChild($dom->createElement('RE', $data['rut_emisor']));
        $da->appendChild($dom->createElement('RS', $data['razon_social_emisor']));
        $da->appendChild($dom->createElement('TD', '39'));

        // RNG (Rango de Folios) - Placeholder
        $rng = $dom->createElement('RNG');
        $rng->appendChild($dom->createElement('D', $data['folio_desde'] ?? '1'));
        $rng->appendChild($dom->createElement('H', $data['folio_hasta'] ?? '99999999'));
        $da->appendChild($rng);

        $da->appendChild($dom->createElement('FA', $data['fecha_autorizacion'] ?? date('Y-m-d')));

        // RSAPK (Llave Pública) - Placeholder
        $rsapk = $dom->createElement('RSAPK');
        $rsapk->appendChild($dom->createElement('M', $data['modulo'] ?? ''));
        $rsapk->appendChild($dom->createElement('E', $data['exponente'] ?? ''));
        $da->appendChild($rsapk);

        $da->appendChild($dom->createElement('IDK', $data['idk'] ?? '100'));

        $caf->appendChild($da);

        // FRMA (Firma del CAF) - Placeholder
        $frma_caf = $dom->createElement('FRMA', $data['frma_caf'] ?? '');
        $frma_caf->setAttribute('algoritmo', 'SHA1withRSA');
        $caf->appendChild($frma_caf);

        $dd->appendChild($caf);
        $dd->appendChild($dom->createElement('TSTED',  $data['fechahora']));
        // TmstFirma
        $documento->appendChild($dom->createElement('TmstFirma', $data['fechahora']));
        // Generar el XML
        return $dom->saveXML();
    }

    function ActualizarLiquidacionFolio($QueryBuilder, $id_liquidacion_comision, $tipo_doc, $NroFolio)
    {

        echo $id_liquidacion_comision."<br>";

        $table = "propiedades.propiedad_comision_liquidacion";
        $data = [
            "folio" => $NroFolio,
            "tipo_documento" => $tipo_doc,
        ];
        $conditions = [
            "id" => $id_liquidacion_comision,
        ];

        $ResultadoUpdate = $QueryBuilder->update($table, $data, $conditions);

        if (!$ResultadoUpdate) {
            throw new Exception("Error al guardar el folio");
        }
    }

    function ActualizarLiquidacionEstado($QueryBuilder, $id_liquidacion)
    {
        $table = 'propiedades.propiedad_liquidaciones';
        $data = [
            'estado' => 1, // Estado 1: pagado
        ];
        $conditions = [
            'id' => $id_liquidacion,
        ];

        $result = $QueryBuilder->update($table, $data, $conditions);

        if (!$result) {
            throw new Exception("Error al actualizar el estado ");
        }
    }

    function SolicitarFolio($url, $tipo_doc)
    {
        $client = new SoapClient(trim($url));
        $response = $client->Solicitar_Folio(['RutEmpresa' => '77367969K', 'TipoDocto' => $tipo_doc]);

        if (!$response || !isset($response->Solicitar_FolioResult)) {
            throw new Exception("Error al solicitar folio: respuesta no válida.");
        }

        return $response->Solicitar_FolioResult;
    }

    function GuardarPDF($index, $pdfContent)
    {
        $pdfFileName = "boletas/boleta_{$index}.pdf";
        if (!file_put_contents($pdfFileName, $pdfContent)) {
            throw new Exception("Error al guardar el archivo PDF.");
        }

        return $pdfFileName;
    }

    function eliminarTildes($cadena)
    {
        $reemplazos = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        ];
        return strtr($cadena, $reemplazos);
    }

    foreach ($resultadoQuery as $index => $row) {
        try {
            // Datos generales
            $tipo_doc = 39; // 39: boletas, 33: factura, 61: nota crédito
            $url = $config->url_DTE; // URL del servicio SOAP
            $rut = $config->rut;
            $rut_empresa = $config->rut_empresa;
            $id_liquidacion_comision = $row['id_liquidacion_comision'];
    

            // Solicitar número de folio
            $folioResult = SolicitarFolio($url, $tipo_doc);
            $NroFolio = $folioResult->Folio;

            if (!$NroFolio) {
                throw new Exception("No se pudo obtener un folio válido.");
            } else {

                // Convertir y concatenar el nombre completo del propietario
                $NombrePropietario = $row['nombre'] . " " . $row['apellido_paterno'] . " " . $row['apellido_materno'];
                $rutPropietario = $row['dni'];
                $cantidadItems = 1;
                $descripcionCobro = $row['razon'];
                $porcentaje_iva = $row['iva'];

                /**
                 *  
                 * calculos
                 * 
                 */
                 $porcentaje_participacion = $row['porcentaje_participacion'];
                 $mnt_porcentaje = $row['monto'] * ($porcentaje_participacion / 100);
                 $mnt_bruto = $mnt_porcentaje; // monto bruto
                 $monto_neto =  round($mnt_bruto / (1  + ($porcentaje_iva / 100)));
                 $monto_iva = ($mnt_bruto - $monto_neto);

                /**
                 *  
                 * end calculos
                 * 
                 */

                $precio_item = $mnt_porcentaje;
                $CdgIntRecep = 1;
                $Contacto = isset($row['correo_electronico']) ? $row['correo_electronico'] : '';
                $DirPostal = $row['direccion'];

                // Convertir comuna y ciudad a UTF-8
                $CmnaPostal = $row['comuna'];
                $CiudadRecep = $row['comuna'];

                // Datos adicionales
                $CmnaRecep = $row['comuna'];;
                $DirRecep = $row['direccion'];
                $razon_social_emisor = '1';
                $giro_emisor = '1'; // Fuenzalida
                $dir_origen = '1'; // Fuenzalida
                $comuna_origen = $row['direccion'];;
                $ciudad_origen = '1';
                $cdg_item_tipo = $row['ficha_propiedad'];

                // Preparar datos para el XML
                $data = [

                    'rut_emisor' => '77367969-K', // rut fuenzalida
                    'rut_envia' => '6285461-8', // rut certificado
                    'rut_receptor' => '60803000-K', // rut sii
                    'fch_resol' => '2014-08-22',
                    'nro_resol' => '80',
                    'folio' =>  $NroFolio,
                    'fch_emis' => date('Y-m-d'),
                    'razon_social_emisor' =>   eliminarTildes(strtoupper($razon_social_emisor)),
                    'giro_emisor' =>  eliminarTildes(strtoupper($giro_emisor)),
                    'dir_origen' =>    eliminarTildes(strtoupper($dir_origen)),
                    'comuna_origen' =>   eliminarTildes(strtoupper($comuna_origen)),
                    'ciudad_origen' =>  eliminarTildes(strtoupper($ciudad_origen)),
                    'nombre_item' =>   substr(eliminarTildes(strtoupper($descripcionCobro)), 0, 100),
                    'cantidad_item' => $cantidadItems,
                    'precio_item' =>$precio_item,
                    'mnt_bruto' => $monto_neto,
                    'iva' => $monto_iva,
                    'mnt_total' => $mnt_bruto,
                    'razon_social_receptor' => substr(eliminarTildes(strtoupper($NombrePropietario)), 0, 100),
                    'fechahora' => $fecha,
                    'rutrecep' => $rutPropietario, // rut del pripietario  
                    'CdgIntRecep' => $CdgIntRecep,
                    'Contacto' => '', // datos de contacto
                    'DirPostal' => eliminarTildes(strtoupper($DirPostal)),
                    'CmnaPostal' =>  eliminarTildes(strtoupper($CmnaPostal)),
                    'fecha_vencmimeinto'  => date('Y-m-d'),
                    'CiudadRecep' => eliminarTildes(strtoupper($CiudadRecep)),
                    'CmnaRecep' => eliminarTildes(strtoupper($CmnaRecep)),
                    'DirRecep' => substr(eliminarTildes(strtoupper($DirRecep)), 0, 20),
                    'cdg_item_tipo' => $cdg_item_tipo,
                    'cdg_item_valor' => $cdg_item_tipo,

                ];

                // Generar el XML
                $xml = generarXMLBoleta($data);

                // Enviar XML al web service
                $boletaClient = new SoapClient(trim($url));
                $soapData = ['ArchivoTXT' => $xml, 'TipoArchivo' => 'XML'];
                $resultDTE = $boletaClient->Carga_TXTBoleta($soapData);

                // resultados de la peticion
                $resultDTEMsg = $result->MsgEstatus;
                if (!$resultDTE || !isset($resultDTE->Carga_TXTBoletaResult->PDF)) {
                    throw new Exception("Error al generar el PDF en el servicio DTE.");
                } else {

                    // Guardar PDF generado
                    $pdfFileName = GuardarPDF($index, $resultDTE->Carga_TXTBoletaResult->PDF);

                    // Actualizar la base de datos
                    ActualizarLiquidacionFolio($QueryBuilder, $id_liquidacion_comision, $tipo_doc, $NroFolio);
                    ActualizarLiquidacionEstado($QueryBuilder, $idLiquidacion);

                    // Respuesta exitosa
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Documento procesado correctamente.',
                        'file' => $pdfFileName,
                    ]);
                }

            }

        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error procesando liquidación {$idLiquidacion }: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
} catch (Exception $e) {
    // Manejo de errores generales
    // header('Content-Type: application/json', true, 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
    ]);
}
