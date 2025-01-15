// Cargar los datos en la tabla
async function TablaLlenarLiquidaciones() {
    try {
        const response = await fetch('components/dte/models/GetDTE.php');
        if (!response.ok) throw new Error(`Error: ${response.status} ${response.statusText}`);

        const data = await response.json();
        const tableBody = document.getElementById("cierre-liquidaciones-tab-pane");
        tableBody.innerHTML = ''; // Limpiar la tabla antes de llenar

        // Crear fragmento para mejor rendimiento
        const fragment = document.createDocumentFragment();

        data.forEach(item => {
            const fecha = new Date(item.fecha_liquidacion);
            const fechaFormateada = `${('0' + fecha.getDate()).slice(-2)}-${('0' + (fecha.getMonth() + 1)).slice(-2)}-${fecha.getFullYear()}`;
            const montoFormateadoArrinedo = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.comision_arriendo);
            const montoFormateadoAdministracion = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.comision_administracion);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" class="row-check" onchange="toggleGenerarDTE()">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <input type="hidden" value="${item.id_liquidacion}">
                    <input type="hidden" value="${item.documento_comision}">
                    <input type="hidden" value="${item.documento_arriendo}">
                </td>
                <td>${item.id_liquidacion}</td>
                <td>${montoFormateadoArrinedo}</td>
                <td>${montoFormateadoAdministracion}</td>
                <td>${item.direccion}</td>
                <td>${fechaFormateada}</td>
            `;
            fragment.appendChild(row);
        });

        tableBody.appendChild(fragment);

        // Reinicializar DataTable
        $('#liq-generacion-masiva-table').DataTable().destroy();
        $('#liq-generacion-masiva-table').DataTable();

    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }
}

// Función para habilitar/deshabilitar el botón Generar DTE
function toggleGenerarDTE() {

    const checkboxes = document.querySelectorAll('.row-check');
    const generarDTEButton = document.getElementById('generarDTE');
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    generarDTEButton.disabled = !anyChecked;

}

// Función para seleccionar o deseleccionar todas las filas
function toggleSelectAll() {


    // Selección de todos los checkboxes
    const checkboxes = document.querySelectorAll('.row-check');
    const button = document.getElementById('select-all');

    // Determina si todos están seleccionados
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    // Cambia el estado de los checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    // Cambia el texto y color del botón
    if (!allChecked) {
        button.innerHTML = 'Deseleccionar todos';
        button.classList.remove('btn-info');
        button.classList.add('btn-danger');
    } else {
        button.innerHTML = 'Seleccionar todos';
        button.classList.remove('btn-danger');
        button.classList.add('btn-info');
    }

    // Actualiza el botón Generar DTE
    toggleGenerarDTE();
}

// genera el documento y envia a la url correcta segun el tipo de doc 
async function GenerarDocumento() {

    let message;
    const checkboxes = document.querySelectorAll('.row-check:checked');

    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin selección',
            text: 'Por favor, selecciona al menos un DTE para continuar.',
            confirmButtonText: 'Entendido',
        });
        return;
    }

    const selectedItems = Array.from(checkboxes).map(checkbox => {
        const row = checkbox.closest('tr');
        if (!row) return null;

        const idLiquidacion = row.querySelector('input[type="hidden"]:nth-child(2)')?.value || null;
        const documentoComision = row.querySelector('input[type="hidden"]:nth-child(3)')?.value || null;
        const documentoArriendo = row.querySelector('input[type="hidden"]:nth-child(4)')?.value || null;

        return { idLiquidacion, documentoComision, documentoArriendo };
    }).filter(item => item !== null);

    try {
        let totalSuccess = 0;
        let totalErrors = 0;
        const errorMessages = [];

        // Mostrar el preloader
        Swal.fire({
            title: 'Generando documentos...',
            text: 'Por favor, espera mientras se procesan los documentos.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        for (const item of selectedItems) {
            let action = '';
            const type = item.documentoComision || item.documentoArriendo;

            // Determinar la acción según el tipo de documento
            switch (type) {
                case '1':
                    action = 'components/dte/models/GenerarXMLFactura.php';
                    break;
                case '3':
                    action = 'components/dte/models/GenerarXMLBoleta.php';
                    break;
                case '9':
                    action = 'components/dte/models/GenerarXMLNotaCredito.php';
                    break;
                default:
                    Swal.fire({
                        icon: 'info',
                        title: 'Acción no determinada',
                        text: `No se pudo determinar la acción para la liquidación ${item.idLiquidacion}.`,
                        confirmButtonText: 'Ok',
                    });
                    totalErrors++;
                    continue;
            }

            const formData = new FormData();
            formData.append('id_liquidacion', item.idLiquidacion);

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.statusText}`);
                }

                const result = await response.json();
                message = result;


                if (result.status) {
                    throw new Error(result.message || 'No se generó el documento.');
                }

                if (result.status === 'success' && result.file) {
                    totalSuccess++;
                } else {
                    throw new Error('No se generó un archivo válido.');
                }
            } catch (error) {
                totalErrors++;
                errorMessages.push(error.message);
                console.error(`Error procesando la liquidación ${item.idLiquidacion}:`, error);
            }
        }

        Swal.close();

        const errorSummary = errorMessages.length > 0 ? errorMessages.join('<br>') : '';
        Swal.fire({
            icon: totalErrors > 0 ? 'warning' : 'success',
            title: 'Proceso terminado',
            html: `${errorSummary}`,
            confirmButtonText: 'Entendido',
        });

        if (totalErrors > 0) {
            console.warn('Errores detallados:', errorMessages);
        }

        // Actualizar la tabla
        TablaLlenarLiquidaciones();
        $('#historial-dte-tab').click();

    } catch (error) {
        Swal.close();

        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: `Error general: ${error.message}`,
            confirmButtonText: 'Entendido',
        });
        console.error('Error general:', error);
    }
}

// listado historial liquidacione
async function HistorialLiquidaciones() {

    try {
        // Llamada AJAX para obtener los datos
        const response = await $.ajax({
            url: "components/dte/models/GetHistorialLiquidaciones.php", // URL del archivo PHP
            method: "GET",
            dataType: "json" // Asegurar que el retorno sea JSON
        });

        // Obtener la tabla y vaciar su contenido
        const tableBody = $("#liq-historial");
        tableBody.empty(); // Limpiar el contenido previo

        // Construir las filas para la tabla
        const tableRows = response.map(item => {
            // Ajustar ruta del PDF
            let pdfUrl = `/components/dte/models/boletas/${item.folio}.pdf`;

            const fecha = new Date(); // Usar fecha actual, ya que no está en los datos
            const fechaFormateada = `${('0' + fecha.getDate()).slice(-2)}-${('0' + (fecha.getMonth() + 1)).slice(-2)}-${fecha.getFullYear()}`;
            const monto = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.monto);

            // Crear fila
            return `
                <tr>
                    <td>${item.id_ficha_arriendo}</td>
                    <td>${item.id_ficha_propiedad}</td>
                    <td>${item.direccion}</td>      
                    <td>${item.id_liquidacion}</td>
                    <td>${item.tipo_comision}</td>
                    <td>${item.folio}</td>
                    <td>${item.tipo_documento_texto}</td>
                    <td>
                        <button 
                            title='Descargar PDF'
                            class="btn btn-success btn-sm descargar-pdf" 
                            data-tipo-documento="${item.tipo_documento}" 
                            data-folio="${item.folio}" 
                            data-url="${pdfUrl}">
                            <i class='fa-regular fa-file-pdf'></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join("");

        // Insertar filas en la tabla
        tableBody.append(tableRows);

        // Agregar manejador de eventos a los botones dinámicos
        $(".descargar-pdf").off("click").on("click", function () {
            const tipoDocumento = $(this).data("tipo-documento");
            const folio = $(this).data("folio");

            obtenerDTE(tipoDocumento, folio); // Llamar a la función obtenerDTE
        });

        // Inicializar DataTable si no está ya inicializada
        if (!$.fn.DataTable.isDataTable('#tablaHistorial')) {
            $('#tablaHistorial').DataTable({
                responsive: true,
                pageLength: 10, // Número de registros por página
                searching: true, // Habilitar el buscador
                paging: true, // Habilitar paginación
                ordering: true, // Habilitar ordenamiento de columnas
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción al español
                }
            });
        }

    } catch (err) {
        console.error("Error cargando datos: ", err);
    }
}

// Función para enviar los datos al servidor y descargar el PDF
async function obtenerDTE(tipoDocumento, folio) {
    const url = 'components/dte/models/DescargarDTE.php'; // Cambia esto por la ruta real de tu script PHP
    const formData = new FormData();

    // Agregar datos al formulario
    formData.append('tipo_documento', tipoDocumento);
    formData.append('folio', folio);

    try {
        // Mostrar un SweetAlert de carga
        Swal.fire({
            title: 'Cargando...',
            text: 'Estamos procesando tu solicitud.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Hacer la solicitud al servidor
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
        });

        // Parsear la respuesta JSON
        const data = await response.json();

        Swal.close(); // Cerrar el SweetAlert de carga

        if (data.pdfLink) {
            // Mostrar éxito y abrir el enlace en una nueva pestaña
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'El archivo PDF se descargará en una nueva pestaña.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.open(data.pdfLink, '_blank');
            });
        } else {
            // Manejar el error devuelto por el servidor
            Swal.fire({
                icon: 'warning',
                title: 'Atencion',
                text: data.error || 'No se pudo obtener el enlace al PDF.',
            });
        }
    } catch (error) {
        Swal.close(); // Cerrar el SweetAlert de carga en caso de error
        // Manejar errores de red u otros errores inesperados
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al intentar obtener el DTE.',
            footer: `<p>Detalles del error: ${error.message}</p>`
        });
        console.error('Error al obtener el DTE:', error);
    }
}

// Cargar la tabla y agregar eventos a los botones al cargar el DOM
$(document).ready(function () {
    HistorialLiquidaciones();
    TablaLlenarLiquidaciones().then(() => {
        // Asignar evento al botón de seleccionar/deseleccionar después de cargar la tabla
        document.getElementById('select-all').addEventListener('click', toggleSelectAll);
    });
});
