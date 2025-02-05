// Función para dar formato de moneda chilena, ya existente
function formatoMonedaChile(valor) {
	return new Intl.NumberFormat('es-CL', {
		style: 'currency',
		currency: 'CLP',
	}).format(valor);
}

// Función para formatear la fecha en el formato día/mes/año
function formatoFecha(fecha) {
	const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
	const fechaFormateada = new Date(fecha).toLocaleDateString('es-CL', opciones);
	return fechaFormateada;
}

// Función para leer el listado de Servipag registrado en la BD
function LeerServipag() {
	$.ajax({
		url: 'components/servipag/models/leercargaservipag.php',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			// Vaciar la tabla
			const tableBody = $('#servipagTable tbody');
			tableBody.empty();

			let montoTotal = 0;
			$.each(data, function (index, item) {
				let monto = parseInt(item.monto_pagado);
				montoTotal += monto;
			});
			$('#montoTotalPagado').text(formatoMonedaChile(montoTotal));

			// Recorremos los datos y armamos cada fila
			$.each(data, function (index, item) {
				const row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.rut}</td>
                        <td>${item.ficha_propiedad}</td>
                        <td>
                            <a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=${
															item.token
														}" target="_blank">
                                ${item.id_arriendo}
                            </a> 
                            ${item.direccion}
                        </td>
                        <td>${item.estado}</td>
                        <td>${formatoFecha(item.fecha_pago)}</td>
                        <td>${formatoMonedaChile(item.valor_arriendo)}</td>
                        <td>${formatoMonedaChile(item.monto_pagado)}</td>
                        <td>${formatoMonedaChile(item.diferencia)}</td>
						<td></td>
                    </tr>
                `;
				tableBody.append(row);
			});

			// Si la tabla ya fue inicializada, destruirla antes de reinicializarla
			if ($.fn.DataTable.isDataTable('#servipagTable')) {
				$('#servipagTable').DataTable().clear().destroy();
			}

			// Inicializar DataTables con botón de Excel y sin paginación (todos los registros en una sola página)
			$('#servipagTable').DataTable({
				paging: false, // Deshabilita la paginación
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						text: 'Descargar Excel',
						title: 'Servipag',
						exportOptions: {
							columns: ':visible',
							format: {
								body: function (data, row, column, node) {
									// 1) QUITAR HTML DE LA COLUMNA DIRECCIÓN (ÍNDICE 4)
									if (column === 3) {
										// Extrae el texto real de la celda (incluye ID y dirección)
										let rawText = $(node).text();

										//Partiendo líneas y eliminando la primera
										let lines = rawText
											.split('\n')
											.map((line) => line.trim())
											.filter(Boolean);
										// lines[0] será el ID, lines[1] la dirección
										// Te quedas con todo menos la primera línea
										if (lines.length > 1) {
											lines.shift(); // elimina el primer elemento del array (el ID)
										}
										// Une el resto con espacio en caso de que hubiera más de un salto de línea
										let direccionLimpia = lines.join(' ');
										return direccionLimpia.trim();
									}

									// 2) CONVERTIR A ENTERO LAS COLUMNAS DE MONTOS (7, 8, 9)
									if (column === 6 || column === 7 || column === 8) {
										// Elimina el símbolo $, puntos y comas
										let limpio = data
											.replace(/\$/g, '')
											.replace(/\./g, '')
											.replace(/,/g, '')
											.trim();
										// Conviértelo a entero
										let numero = parseInt(limpio, 10);
										if (isNaN(numero)) {
											numero = 0;
										}
										return numero;
									}

									// 3) EL RESTO DE COLUMNAS SE MANTIENE IGUAL
									return data;
								},
							},
						},
					},
				],
				columnDefs: [
					{
						targets: 9, // Aplica el contador en la columna "Nro"
						render: function (data, type, row, meta) {
							return meta.row + 1;
						},
					},
				],
				order: [[1, 'asc']],
			});
		},
		error: function (xhr, status, error) {
			console.error('Error al cargar los datos:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'No se pudieron cargar los datos. Inténtalo nuevamente más tarde.',
			});
		},
	});
}

// funcion para carga el txt en la bd
async function CargarServipag() {
	const fileInput = document.getElementById('formFile');

	if (fileInput.files.length === 0) {
		Swal.fire({
			icon: 'warning',
			title: 'Archivo no seleccionado',
			text: 'Por favor, selecciona un archivo antes de continuar.',
		});
		return;
	}

	const formData = new FormData();
	formData.append('file', fileInput.files[0]);

	// Mostrar mensaje de procesando
	Swal.fire({
		title: 'Procesando...',
		text: 'Por favor espera mientras procesamos el archivo.',
		icon: 'info',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});

	try {
		const response = await fetch(
			'components/servipag/models/cargarservipag.php',
			{
				method: 'POST',
				body: formData,
			}
		);

		// Convertir la respuesta en JSON
		const result = await response.json();

		// Si el servidor retornó success: true se muestra el mensaje de éxito
		if (result.success) {
			Swal.fire({
				icon: 'success',
				title: 'Éxito',
				text: result.message,
			});
		} else {
			// En caso de success: false, se muestra una alerta de error con el mensaje recibido
			Swal.fire({
				icon: 'info',
				title: 'Error',
				text: result.message,
			});
		}
	} catch (error) {
		Swal.fire({
			icon: 'error',
			title: 'Error de conexión',
			text: 'No pudimos conectar con el servidor. Por favor verifica tu conexión.',
		});
	} finally {
		LeerServipag();
	}
}

// procesar listado
function ProcesarListado() {
	// Verificar si la tabla muestra el mensaje de "tabla vacía"
	if ($('#servipagTable tbody td.dataTables_empty').length > 0) {
		Swal.fire({
			icon: 'warning',
			title: 'Tabla vacía',
			text: 'No hay datos para procesar.',
		});
		return; // Detener la ejecución si no hay filas reales
	}

	const dataToSend = [];

	// Si hay datos, se recorren las filas
	const tableRows = $('#servipagTable tbody tr');
	tableRows.each(function () {
		const row = $(this).find('td');
		const dataRow = {
			id_servipag: row.eq(0).text().trim(),
			id_propiedad: row.eq(2).text().trim(),
			fecha_pago: row.eq(5).text().trim(),
			monto_pagado: row.eq(7).text().replace(/\D/g, ''),
		};
		dataToSend.push(dataRow);
	});

	// Mostrar mensaje de "Procesando"
	Swal.fire({
		title: 'Procesando',
		text: 'Por favor, espera mientras procesamos los datos...',
		icon: 'info',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});

	// Enviar los datos al backend
	$.ajax({
		url: 'components/servipag/models/pago_transferencias.php',
		method: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(dataToSend),
		success: function (response) {
			Swal.close();
			Swal.fire({
				icon: 'success',
				title: 'Procesado',
				text: 'Los datos se enviaron correctamente.',
			});

			LeerServipag();
		},
		error: function (xhr, status, error) {
			Swal.close();
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Hubo un error al procesar los datos.',
			});
		},
	});

	// Llamada adicional para refrescar la tabla (si es necesaria)
	LeerServipag();
}

// ejecucion automatica
$(document).ready(function () {
	LeerServipag();
});
