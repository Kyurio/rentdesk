// Función para dar formato de moneda chilena, ya existente
function formatoMonedaChile(valor) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP'
    }).format(valor);
}

// Función para formatear la fecha en el formato día/mes/año
function formatoFecha(fecha) {
    const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const fechaFormateada = new Date(fecha).toLocaleDateString('es-CL', opciones);
    return fechaFormateada;
}

// funcion para leer el listado de servipag registrado en la bd
function LeerServipag() {
    // Realizar una solicitud AJAX para obtener los datos
    $.ajax({
        url: 'components/servipag/models/leercargaservipag.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            // Vaciar la tabla antes de rellenarla
            const tableBody = $('#servipagTable tbody');
            tableBody.empty();

            // Usar $.each para recorrer los datos y agregarlos a la tabla
            $.each(data, function (index, item) {
                const switchChecked = item.diferencia == 0 ? 'checked' : ''; // Si diferencia es 0, el switch estará checked (true), sino estará sin marcar (false)
                const row = `
                    <tr>     
                        <td>${item.rut}</td>
                        <td><a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=${item.token}" target="_blank"> ${item.ficha_arriendo}</a> ${item.direccion}</td>
                        <td>${item.estado}</td>
                        <td>${formatoFecha(item.fecha_pago)}</td>
                        <td>${formatoMonedaChile(item.valor_arriendo)}</td>
                        <td>${formatoMonedaChile(item.monto_pagado)}</td>
                        <td>${formatoMonedaChile(item.diferencia)}</td>
              
                        <td>          

                            <div class="d-flex">
								<label class="switch">
									<input name="desposito" id="switchDeposito" class="form-check-input switchCheques" type="checkbox" role="switch" ${switchChecked}>
									<span class="slider round"></span>
									<span class="switchText">${item.diferencia == 0 ? 'Si' : 'No'}</span>
								</label>
							</div>

                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });




            // Inicializar o reiniciar el DataTable después de llenar la tabla
            if ($.fn.DataTable.isDataTable('#servipagTable')) {
                $('#servipagTable').DataTable().destroy();
            }

            $('#servipagTable').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los datos:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudieron cargar los datos. Inténtalo nuevamente más tarde.",
            });
        }
    });
}

// funcion para carga el txt en la bd 
async function CargarServipag() {
    const fileInput = document.getElementById("formFile");

    if (fileInput.files.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Archivo no seleccionado",
            text: "Por favor, selecciona un archivo antes de continuar.",
        });
        return;
    }

    const formData = new FormData();
    formData.append("file", fileInput.files[0]);

    // Mostrar mensaje de procesando
    Swal.fire({
        title: "Procesando...",
        text: "Por favor espera mientras procesamos el archivo.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const response = await fetch("components/servipag/models/cargarservipag.php", {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: "Archivo subido y procesado con éxito.",
            });
            LeerServipag();
        } else {
            const errorText = await response.text(); // Capturar posibles errores del servidor
            console.error("Error del servidor:", errorText);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un problema al procesar el archivo. Por favor intenta nuevamente.",
            });
        }
    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No pudimos conectar con el servidor. Por favor verifica tu conexión.",
        });
    }
}

// envia los elementos seleccionados
async function ProcesarListado() {

    // Crear un arreglo para almacenar los datos seleccionados
    let seleccionados = [];

    // Recorrer los checkboxes seleccionados
    $('#servipagTable tbody input[type="checkbox"]:checked').each(function () {
        // Obtener la fila del checkbox actual
        const fila = $(this).closest('tr');

        // Extraer datos de las celdas de la fila
        const rut = fila.find('td:nth-child(1)').text(); // Primera columna (RUT)
        const direccion = fila.find('td:nth-child(2)').text(); // Segunda columna (Dirección)

        // Agregar los datos al arreglo
        seleccionados.push({ rut, direccion });
    });

    // Verificar si hay seleccionados
    if (seleccionados.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Ninguna selección",
            text: "No has seleccionado ningún registro.",
        });
    } else {
        // Crear un mensaje con los datos seleccionados
        let mensaje = "Registros seleccionados:\n\n";
        seleccionados.forEach((item, index) => {
            mensaje += `${index + 1}. RUT: ${item.rut}, Dirección: ${item.direccion}\n`;
        });

        // Mostrar el mensaje en un alert
        Swal.fire({
            icon: "info",
            title: "Datos Seleccionados",
            text: mensaje,
        });
    }

}

function seleccionarTodo() {
    alert("entro..");
    // Marcar todos los checkboxes como seleccionados
    $('#servipagTable tbody input[type="checkbox"]').prop('checked', true);
}

function deseleccionarTodo() {
    alert("entro..");
    // Desmarcar todos los checkboxes
    $('#servipagTable tbody input[type="checkbox"]').prop('checked', false);
}


// ejecucion automatica
$(document).ready(function () {

    LeerServipag();

});