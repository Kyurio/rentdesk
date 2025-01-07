<script src="js/region_ciudad_comuna.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});

	const triggerTabList = document.querySelectorAll('#myTab button')
	triggerTabList.forEach(triggerEl => {
		const tabTrigger = new bootstrap.Tab(triggerEl)

		triggerEl.addEventListener('click', event => {
			event.preventDefault()
			tabTrigger.show()
		})
	})

	function validarNumero(input) {
		// Obtener el valor actual del campo
		var valor = input.value;

		// Eliminar guiones y caracteres no numéricos
		var valorNumerico = valor.replace(/[^0-9-]/g, '');

		// Eliminar guiones adicionales (por ejemplo, si el usuario ingresó "--")
		valorNumerico = valorNumerico.replace(/-{2,}/g, '-');

		valorNumerico = valorNumerico.replace(/-/g, '');

		// Si el valor ha cambiado, actualizar el campo
		if (valor !== valorNumerico) {
			input.value = valorNumerico;
		}
	}
</script>

<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Ficha Técnica</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propiedad <?php echo "#" . $ficha_tecnica; ?></li>
			<input type="hidden" id="ficha_tecnica_id" value="<?php echo $ficha_tecnica; ?>">
		</ol>
	</div>
</div>

<div class="content content-page">

	<div class="row">

		<div class="col-lg-0 p-0" style="display:none;">
			<ul class="nav flex-column nav-pills p-0" id="myTab" role="tablist" aria-orientation="vertical" style="display: flex;gap:1rem">
				<li class="nav-item" role="presentation">
					<button onclick="cargarInfoPropiedad()" class="d-flex nav-link active w-100" id="propiedad-ft-informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">Información</button>
				</li>
				<li class="nav-item" role="presentation">
					<button onclick="cargarInfoCoPropietarios()" class="d-flex nav-link w-100" id="propiedad-ft-co-propietarios-tab" data-bs-toggle="tab" data-bs-target="#co-propietarios" type="button" role="tab" aria-controls="co-propietarios" aria-selected="true">Propietarios</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-retencionesP-tab" data-bs-toggle="tab" data-bs-target="#retencionesP" type="button" role="tab" aria-controls="retencionesP" aria-selected="false">Retenciones</button>
				</li>
				<li class="nav-item" role="presentation">
					<button onclick="cargarInfoCuentaCorriente()" class="d-flex nav-link w-100" id="propiedad-ft-cuentaCorriente-tab" data-bs-toggle="tab" data-bs-target="#cuentaCorriente" type="button" role="tab" aria-controls="cuentaCorriente" aria-selected="false">Cuenta Corriente</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-cuentaServicio-tab" onclick="cargarInfoCtaServicios()" data-bs-toggle="tab" data-bs-target="#cuentaServicio" type="button" role="tab" aria-controls="cuentaServicio" aria-selected="false">Cuentas de Servicio</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-liquidacionesCoPropietarios-tab" onclick="cargarLiquidaciones(), cargarInfoCoPropietarios()" data-bs-toggle="tab" data-bs-target="#liquidacionesCoPropietarios" type="button" role="tab" aria-controls="liquidacionesCoPropietarios" aria-selected="false">Liquidaciones propietarios</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-notasDeCredito-tab" data-bs-toggle="tab" data-bs-target="#notasDeCredito" type="button" role="tab" aria-controls="notasDeCredito" aria-selected="false">Notas de Crédito</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="false">Roles</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propiedad-ft-recordatorios-tab" data-bs-toggle="tab" data-bs-target="#recordatorios" type="button" role="tab" aria-controls="recordatorios" aria-selected="false">Recordatorios</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" onClick="cargarHistorialArriendoList();" id="propiedad-ft-historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">Historial</button>
				</li>
			</ul>
		</div>

		<div class="col-lg-12">

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="informacion" role="tabpanel" aria-labelledby="arriendo-ft-informacion-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Información</h3>
								<div class="row">
									<div class="col-md-12">
										<fieldset class="form-group border p-3" id="section-info-propiedad">
											<div class="container">
												<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
													<div class="col">
														<label class="fw-bold">Nro. Ficha Técnica</label><br>
														<label id="ctaBancNombreTitular">#<?php echo $ficha_tecnica; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold"><i class='fa-solid fa-house-user' style='color:#313131;font-size:12px;' title='Propietario'></i> Propietario / <i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> Beneficiario
														</label><br>

														<label id="ctaBancNombreTitular"><?php echo $propietarios_con_saltos; ?></label>

													</div>
													<div class="col">
														<label class="fw-bold">Tipo Propiedad</label><br>
														<label id="ctaBancNombreTitular"><?php echo $tipo_propiedad; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Estado</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $estado_propiedad; ?><?php if (isset($idFichaArriendo)) : ?> | <a href="<?php echo "index.php?component=arriendo&view=arriendo_ficha_tecnica&token=" . $objetoArrendatario->token_arriendo; ?>" class="link-info"><?php echo "Arriendo " . $idFichaArriendo; ?></a></label> <?php else : ?> <?php endif; ?>
													</div>

													<!-- imput oculto para el token -->
													<input type="hidden" id="tokenPropiedad" value="<?php $_GET["token"]; ?>">

													<!-- <div class="col">
															<label class="fw-bold">Documentos</label><br>
															<label id="ctaBancNombreTitular"><a class="link-info" target="_blank" href="upload/contrato/CONTRATO_5_DE_ABRIL_840__203.pdf">CONTRATO_5_DE_ABRIL_840__203.pdf</a></label>
														</div> -->
													<div class="col">
														<label class="fw-bold">Avalúo Fiscal</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $avaluo_fiscal == "-" ? $avaluo_fiscal : "$" . $avaluo_fiscal; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Dirección</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $direccion; ?></label>
													</div>
													<!-- <div class="col">
															<label class="fw-bold">Complemento</label><br>
															<label id="ctaBancNombreTitular">Departamento</label>
														</div> -->
													<div class="col">
														<label class="fw-bold">Sucursal</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $nombre_sucursal; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Fecha Ingreso</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $fecha_ingreso; ?></label>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<fieldset class="form-group border p-3" id="section-info-propiedad">
											<div class="container">
												<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
													<div class="col">
														<label class="fw-bold">M2</label><br>
														<label id="ctaBancNombreTitular"><?php echo  $m2; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Edificado</label><br>
														<label id="ctaBancNombreTitular"><?php echo $edificado == "-" ? $edificado : ($edificado == true ? "SI" : "NO"); ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Dormitorios</label><br>
														<label id="ctaBancNombreTitular"><?php echo $dormitorios; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Dorm. Servicio</label><br>
														<label id="ctaBancNombreTitular"><?php echo $dormitorios_servicio; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Baños</label><br>
														<label id="ctaBancNombreTitular"><?php echo $banos; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Baños Visita</label><br>
														<label id="ctaBancNombreTitular"><?php echo $banos_visita; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Bodegas</label><br>
														<label id="ctaBancNombreTitular"><?php echo $bodegas; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Logia</label><br>
														<label id="ctaBancNombreTitular"><?php echo $logias; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Piscina</label><br>
														<label id="ctaBancNombreTitular"><?php echo $piscina == "-" ? $piscina : ($piscina == true ? "SI" : "NO"); ?></label>
													</div>

												</div>
											</div>
										</fieldset>
									</div>
								</div>

							</div>
						</div>
						<fieldset class="form-group border-0 p-3" id="section-Documentos">
							<form name="formulario" id="formulario-propiedad" method="post" action="" enctype="multipart/form-data" class="my-3">
								<input id="token_propiedad_defecto" type="hidden" class="form-control" value="<?php echo @$token_propiedad_defecto ?>">

								<legend>
									<h6 class="text-muted my-4 display-6">Documentos</h6>
								</legend>

								<!-- <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Documento" data-bs-toggle="modal" data-bs-target="#modalDocumentoIngreso">
									<span>Agregar Documento</span>
								</button> -->

								<!-- <div class="modal fade" id="modalDocumentoIngreso" tabindex="-1" aria-labelledby="modalDocumentoIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
									<div class="modal-dialog" style="max-width: 800px;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="modalChequesIngresoLabel">Ingreso Documento</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
												<div class="modal-body" id="modalBodyOriginal">
													<div class="row" style="width:100%;">
														<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
															<div class="form-group">
																<label for="documentoTitulo">Titulo</label>
																<input type="text" class="form-control" min="0" name="documentoTitulo" id="documentoTitulo" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
															</div>
														</div>

													</div>

													<div class="row" id="seccionDocumento_archivo_0">
														<div class="col-lg-6">
															<div class="form-group">
																<label for="archivo">Carga Documento</label>
																<input id="archivo_0" name="archivo_0" type="file" onchange="validaArchivo(this,<?php echo $peso_archivo; ?>);" class="btn btn-file btn-xs opacity-100 position-relative h-auto  btn-upload" />
															</div>
														</div>


														<div class="col-lg-4">
															<div class="form-group">
																<label for="documentoFechaCreacion">Fecha vencimiento</label>
																<input name="documentoFecha_0" id="documentoFecha_0" onchange="recalcularMes()" class="form-control" type="date" value="" />
																<span id="startDateSelected"></span>
															</div>

														</div>

														<div class="col-lg-2 align-self-end" id="botonEliminaSeccion">
															<div class="form-group">
																<button id='eliminarRegistroDocumento' onclick='eliminarSeccion("seccionDocumento_archivo_0")' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
																	<i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
																</button>
															</div>
														</div>
													</div>
												</div>



											</div>
											<div class="row">
												<div class="form-group">
													<label><strong>Importante :</strong> El Archivo debe ser una imagen, word, excel o pdf y no debe superar los <?php echo $peso_archivo; ?>MB </label>
												</div>
											</div>

											<div class="modal-footer">
												<button type="button" class="btn btn-info" onClick='resetForm();'>Cerrar</button>
												<button type="button" class="btn btn-danger" id="addItemButton" onClick='cargaDocumento();'>Guardar</button>
											</div>
										</div>
									</div>
								</div> -->

								<!-- <div class="modal fade" id="modalDocumentoEditar" tabindex="-1" aria-labelledby="modalDocumentoEditarLabel" aria-hidden="true" data-bs-backdrop="static">
									<div class="modal-dialog" style="max-width: 800px;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="modalDocumentoEditarLabel">Editar documento</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

												<div class="row" style="width:100%;">

													<input type="hidden" class="form-control" min="0" name="documentoTituloEditar" id="documentoTituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

													<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
														<label for="archivoEditar">Carga Documento</label>
														<input id="archivoEditar" name="archivoEditar" type="file" onchange="validaArchivo(this,<?php echo $peso_archivo; ?>);" class="btn btn-file btn-xs opacity-100 position-relative h-auto  btn-upload" />
													</div>
													<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
														<div class="form-group">
															<label for="documentoFechaEditar">Fecha vencimiento</label>
															<input name="documentoFechaEditar" id="documentoFechaEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
															<span id="startDateSelected"></span>
														</div>

													</div>
												</div>


												<div class="row">
													<div class="d-flex" style="gap: .5rem;">
														<a id="linkDocumento2" style="color: #51bcda;"></a>
														<a id="linkDocumento" download type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem" aria-label="documentoActual" title="documentoActual"></a>
													</div>
													<div class="form-group">
														<label><strong>Importante :</strong> El Archivo debe ser una imagen, word, excel o pdf y no debe superar los <?php echo $peso_archivo; ?>MB </label>
													</div>
												</div>


												<input type="hidden" class="form-control" min="0" name="documentoTokenEditar" id="documentoTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
												<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarDocumento()'>Guardar</button>
											</div>
										</div>
									</div>
								</div> -->

								<!-- <div class="modal fade" id="modalTituloEditar" tabindex="-1" aria-labelledby="modalTituloEditarLabel" aria-hidden="true" data-bs-backdrop="static">
									<div class="modal-dialog" style="max-width: 800px;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="modalDocumentoEditarLabel">Editar Titulo</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

												<div class="row" style="width:100%;">
													<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
														<div class="form-group">
															<label for="TituloEditar">Titulo</label>
															<input type="text" class="form-control" min="0" name="TituloEditar" id="TituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
														</div>

													</div>
												</div>


												<input type="hidden" class="form-control" min="0" name="TokenEditar" id="TokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
												<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarTituloDocumento()'>Guardar</button>
											</div>
										</div>
									</div>
								</div> -->
								<div class="card">
									<div class="card-body">
										<div class="table-responsive overflow-auto">

											<table id="lectura" class="table table-striped" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Título</th>
														<th>Nombre archivo</th>
														<th>Fecha Carga</th>
														<th>Fecha Vencimiento</th>
														<th>Documento</th>
														<th>Fecha modificación</th>
													</tr>
												</thead>
												<tbody>


												</tbody>
											</table>
										</div>
									</div>
								</div>
							</form>
						</fieldset>
						<fieldset class="form-group border-0 p-3" id="section-info-comentarios">
							<h6 class="text-muted my-4 display-6">Comentarios</h6>
							<!-- <button form="comentario_formulario" type="button" onclick="guardarInfoComentario();" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Añadir" data-bs-toggle="modal" data-bs-target="#modalServicioIngreso"> -->
							<button form="comentario_formulario" type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Añadir" data-bs-toggle="modal" data-bs-target="#modalInfoComentarioIngreso">
								<span>Agregar Comentario</span>
							</button>
							<div class="card">
								<div class="card-body">
									<div class="table-responsive overflow-auto">

										<table id="info-comentarios" class="table table-striped" cellspacing="0" width="100%">

											<thead>
												<tr>
													<th>Comentario</th>
													<th>Fecha Creación</th>
													<th>Fecha Modificación</th>
													<th>Usuario Modificación</th>
												</tr>
											</thead>
											<tbody>


											</tbody>


										</table>
									</div>

									<div class="mb-3">
										<!-- <form id="comentario_formulario" action="">
														<label for="ComentarioEditar" class="form-label">Añadir Comentario</label>
														<textarea class="form-control" id="ComentarioEditar" style="padding:1rem" placeholder="Ingrese un comentario..."></textarea>
													</form> -->
										<!-- <button form="comentario_formulario" type="button" onclick="guardarInfoComentario();" class="btn btn-info" style="padding: .5rem;" title="Añadir" > -->
										<!-- <button form="comentario_formulario" type="button" onclick="guardarInfoComentario();" class="btn btn-info" style="padding: .5rem;" title="Añadir"  data-bs-toggle="modal" data-bs-target="#modalServicioIngreso">

														<span>Guardar Comentario</span>
													</button> -->
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="tab-pane" id="co-propietarios" role="tabpanel" aria-labelledby="arriendo-ft-co-propietarios-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Propietarios</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: $0</span></li>
											<li><span>Saldo histórico: $0</span></li>
										</ul>


									</div> -->
								<div class="row">
									<!-- <h6 class="text-muted my-4 display-6">Movimientos</h6> -->

									<div class="card">
										<div class="card-body">
											<form name="copropietario_porcentaje" id="copropietario_porcentaje" method="post" action="" enctype="multipart/form-data" class="my-3">
												<input type="hidden" name="ficha_tecnica" id="ficha_tecnica" value="<?php echo $ficha_tecnica; ?>">
												<input type="hidden" name="idPropietario" id="idPropietario" value="">
												<input type="hidden" name="idRegistro" id="idRegistro" value="">


												<div class="table-responsive overflow-auto">

													<table id="info-copropietarios" class="table table-striped" cellspacing="0" width="100%">


														<thead>
															<tr>
																<th></th>
																<th>Propietario</th>
																<th>RUT Propietario</th>
																<th>Nombre Titular</th>
																<th>RUT Titular</th>
																<th>Cuenta Banco</th>
																<th>% Porcentaje Propietario</th>
																<th>% Porcentaje Beneficiario</th>
																<!-- <th>Acciones</th> -->
															</tr>
														</thead>
														<tbody>

															<?php /* foreach ($dataTableCoPropietarios as $row) : ?>
																<tr>
																	<?php foreach ($row as $key => $cell) : ?>

																		<td><?php echo $cell; ?></td>

																	<?php endforeach; ?>
																	<!-- <td>
																									<a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
																									<i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
																									</a>
																									</td> -->
																	<td>
																		<div class="d-flex" style="gap: .5rem;">
																			<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
																				<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																			</a> -->
																			<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																				<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																			</button>
																		</div>
																	</td>
																</tr>
															<?php endforeach;*/ ?>
														</tbody>
														<tfoot>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>

															<td colspan="2"> <button onclick="guardarCoPropietarioPorcentaje()" type="button" class="btn btn-info" style="padding: .5rem;" title="Guardar">
																	<span>Guardar Participaciones</span>
																</button>
															</td>
															<td></td>

															<!-- <div id="sumResult">Total: 0</div> -->

														</tfoot>

													</table>
													<div id="alertAvisoPorcentajeTotal" class="alert alert-danger" role="alert" style="display: none;">
														Recuerde modificar los % de Participación de los Propietarios para abarcar el 100%. <strong>Porcentaje Total Actual: <span id="current-sum"></span>%</strong>
													</div>
												</div>

											</form>
										</div>
									</div>
								</div>
								<div class="row">

									<form name="formulario-propietario" id="formulario-propietario" method="post" action="" enctype="multipart/form-data" class="my-3" style="padding-left:0px; padding-right:0px;">
										<div class="row g-3">
											<fieldset class="form-group border p-3">


												<div class="d-flex d-row align-items-center " style="gap:1rem">
													<h6 class="text-muted my-4 display-6">Agregar Propietario</h6>
													<p class="my-4"> (Debe estar creado previamente como cliente).</p>
												</div>

												<div class="row g-3">
													<div class="col-md-3">
														<label><span class="obligatorio">*</span> Busqueda por RUT</label>
														<div class="input-group mb-3">
															<input id="DNIProp" type="text" class="form-control" placeholder="RUT" aria-label="DNI" aria-describedby="button-addon2" required value="" onblur="ocultarAutocomplete('DNIProp');" autocomplete='off' onkeyup='buscarClienteAutocompleteGenerica(this.value,"DNIProp");'>
															<div id='suggestions_DNIProp' class="suggestionsAutoComplete"></div>
															<button class="btn btn-info m-0" type="button" id="busqueda-dni-prop" onClick="busquedaDNIProp();">Buscar</button>
														</div>
													</div>
												</div>
												<!-- Hidden input field to store item data -->
												<input type="hidden" id="persona" name="persona">
												<input type="hidden" id="suggested_cta_banc" value="">
												<fieldset class="form-group border p-3" id="section-info-cliente-natural" style="display: none">
													<legend style="display: flex;
															align-items: center;
															justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre</label><br> <label id="nombrePersona"></label></div>
														<div class="col"><label>Telefono</label><br> <label id="telefonoMovilPersona"></label></div>
														<div class="col"><label>Email </label><br> <label id="emailPersona"></label></div>
														<div class="col"><label>Tipo Persona</label><br> <label id="tipoPersona"></label></div>

													</div>
													<div class="row" style="margin-top: 10px">
														<div class="col"><label>Direccion</label><br> <label id="direccionPersona"></label>
															<a href="" id="linkMaps" target="_blank">ver ubicacion</a>
														</div>
													</div>
												</fieldset>
												<fieldset class="form-group border p-3" id="section-info-cliente-juridico" style="display: none">
													<legend style="display: flex;
															align-items: center;
															justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre Fantasia</label><br> <label id="nombrePersonaJuridica"></label></div>
														<div class="col"><label>Razon Social</label><br> <label id="razonPersonaJuridica"></label></div>
														<div class="col"><label>Telefono</label><br> <label id="telefonoMovilPersonaJuridica"></label></div>
														<div class="col"><label>Email </label><br> <label id="emailPersonaJuridica"></label></div>
														<div class="col"><label>Tipo Persona</label><br> <label id="tipoPersonaJuridica"></label></div>

													</div>
													<div class="row" style="margin-top: 10px">
														<div class="col"><label>Direccion</label><br> <label id="direccionPersonaJuridica"></label>
															<a href="" id="linkMapsJuridica" target="_blank">ver ubicacion</a>
														</div>
													</div>
												</fieldset>
												<fieldset class="form-group border p-3" id="section-info-cta-bancaria" style="display: none">
													<legend style="display: flex;
															align-items: center;
															justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Cuenta Bancaria</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre Titular</label><br> <label id="ctaBancNombreTitular"></label></div>
														<div class="col"><label>Rut Titular</label><br> <label id="ctaBancRutTitular"></label></div>
														<div class="col"><label>Número Cuenta </label><br> <label id="ctaBancNumero"></label></div>
													</div>
												</fieldset>
											</fieldset>

										</div>
										<button id="agregar_coprop" type="button" class="btn btn-info" style="padding: .5rem;display:none" title="Añadir" onclick="guardarInfoCoPropietario()">
											<span>Añadir</span>
										</button>
									</form>
									<!-- <div class="card">
											<div class="card-body">
												<div class="table-responsive overflow-auto">

													<table id="infoComentarios" class="table table-striped" cellspacing="0" width="100%">

												
														<tbody>

															<?php foreach ($dataTableAgregarCoPropietario as $row) : ?>
																<tr>
																	<?php foreach ($row as $key => $cell) : ?>

																		<td><?php echo $cell; ?></td>

																	<?php endforeach; ?>
																<td>
																		<div class="d-flex" style="gap: .5rem;">
																		
																		</div>
																	</td>
																</tr>
															<?php endforeach; ?>
														</tbody>

														</thead>

													</table>
												</div>
											</div>
										</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="retencionesP" role="tabpanel" aria-labelledby="propiedad-ft-retencionesP-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Retenciones</h3>
								<div class="row">
									<!-- <ul>
						<li><span>Saldo al día: $0</span></li>
						<li><span>Saldo histórico: $0</span></li>
					</ul> -->
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Movimientos</h6>

									<div class="d-inline">
										<!-- Botón para agregar retención -->
										<button type="button" class="btn btn-success btn-sm mb-8" onclick="abrirModalAgregarRetencion(<?php echo $id_arriendo; ?>, <?php echo $id_propiedad; ?>)">
											Agregar Retención
										</button>
									</div>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">
												<table id="retenciones" class="display" style="width:100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Motivo</th>
															<th>Monto</th>
															<th>Cargo</th>
															<th>Saldo</th>
															<th>Estado</th>
															<th>Acciones</th>
														</tr>
													</thead>

													<tbody>
														<?php foreach ($dataTableRetenciones as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>
																	<td><?php echo $cell; ?></td>
																<?php endforeach; ?>
																<td>

																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- actualizacion de retencion -->

				<!-- Modal para agregar retenciones -->
				<div class="modal fade" id="agregarRetencionModal" tabindex="-1" aria-labelledby="agregarRetencionModalLabel" aria-hidden="true" data-bs-backdrop="static">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="agregarRetencionModalLabel">Agregar Retención</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="formAgregarRetencion">
									<input type="hidden" id="id_arriendo" value="" />
									<input type="hidden" id="id_propiedad" value="" />

									<div class="mb-2">
										<label for="tipo_retencion" class="form-label">Tipo de Retención</label>
										<select class="form-select form-select-sm" id="tipo_retencion" required>
											<option value="1">Monto Total a Retener</option>
											<option value="2">Retener en Cuotas</option>
										</select>
									</div>

									<div class="mb-2">
										<label for="monto_total" class="form-label">Monto</label>
										<input type="text" class="form-control" id="monto_total" required />
									</div>

									<div id="fechasContainer" class="row mb-2" style="display: none;">
										<div class="col">
											<label for="fecha_desde" class="form-label">Fecha Desde</label>
											<input type="date" class="form-control" id="fecha_desde" />
										</div>

										<div class="col">
											<label for="fecha_hasta" class="form-label">Fecha Hasta</label>
											<input type="date" class="form-control" id="fecha_hasta" />
										</div>
									</div>

									<div class="d-flex justify-content-between">

										<button type="button" class="m-3 ms-0 btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
										<button type="submit" class="m-3 me-0  btn btn-primary">Agregar Retención</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="cuentaCorriente" role="tabpanel" aria-labelledby="propiedad-ft-cuentaCorriente-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cuenta Corriente</h3>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCuentaCorrienteIngresoDescuentoAutorizado">
										<span>Ingresar Cargo</span>
									</button>
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCuentaCorrienteIngresoPagoNoLiquidable">
										<span>Ingresar Abono</span>
									</button>

								</div>

								<div class="row">
									<h6 class="text-muted my-4 display-6">Movimientos</h6>
									<div id="descargaCcMovimientos" style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">


									</div>
									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="cc-movimientos-propiedad" class="display">
													<thead>
														<tr>
															<th>Fecha Movimiento</th>
															<th>Hora Movimiento</th>
															<th>Razón</th>
															<th>Monto 1</th>
															<th>Monto 2</th>
															<th>Saldo</th>
														</tr>
													</thead>
													<tbody>
														<!-- Los datos se llenarán aquí -->
													</tbody>
												</table>



											</div>
										</div>
									</div>
								</div>
								<!-- <form name="formulario" id="formulario" method="post" action="" enctype="multipart/form-data" class="my-3">
													<h6 class="text-muted my-4 display-6">Documentos</h6>

													<div class="card">
														<div class="card-body">
															<div class="table-responsive overflow-auto">

																<table id="documentos" class="table table-striped" cellspacing="0" width="100%">
																	<thead>
																		<tr>
																			<th>Titulo</th>
																			<th>Nombre archivo</th>
																			<th>Fecha Carga</th>
																			<th>Fecha Vencimiento</th>
																			<th>Documento</th>
																			<th>Fecha modificacion</th>
																			<th>Acciones</th>
																		</tr>
																	</thead>
																	<tbody>


																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</form> -->
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="cuentaServicio" role="tabpanel" aria-labelledby="propiedad-ft-cuentaServicio-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cuentas de Servicio</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: ...</span></li>
											<li><span>Saldo histórico: ...</span></li>
										</ul>


									</div> -->
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Fijar Estado" data-bs-toggle="modal" data-bs-target="#modalCuentaServiciosFijarEstado">
										<span>Fijar Estado</span>
									</button>

								</div>
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="info-ctas-servicio" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Cuenta de Servicio</th>
															<th>Monto Adeudado</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php /* foreach ($dataTableCuentasDeServicio as $row) : ?>
																<tr>
																	<?php foreach ($row as $key => $cell) : ?>

																		<td><?php echo $cell; ?></td>

																	<?php endforeach; ?>
																	<td>
																		<div class="d-flex" style="gap: .5rem;">
																			<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</a> -->
																			<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																				<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																			</button>
																		</div>
																	</td>
																</tr>
															<?php endforeach;*/ ?>
													</tbody>

												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="liquidacionesCoPropietarios" role="tabpanel" aria-labelledby="propiedad-ft-liquidacionesCoPropietarios-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Liquidaciones propietarios</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: ...</span></li>
											<li><span>Saldo histórico: ...</span></li>
										</ul>


									</div> -->

								<!-- valida que el porcentaje de Participaciones -->
								<div class="alert alert-danger" id="procentajePropietarioValidacion" role="alert">
									El porcentaje de participación debe ser de 100% para poder liquidar, para modificar, debe ir al menú "Propietarios".
								</div>

								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<?php


									$mostrarboton = 'display:block !important;';
									$mostrarAlerta = 'display:none !important;';


									if ($estado_liquidar == true || $estado_liquidar === 1) {

										$mostrarboton = 'display:block !important;';
										$mostrarAlerta = 'display:none !important;';
									} else {
										$mostrarboton = 'display:none !important;';
										$mostrarAlerta = 'display:block !important;';
									}


									?>



									<button type="button" id="btnLiquidard" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap; <?php echo $mostrarboton; ?>" title="Liquidar" data-bs-toggle="modal" data-bs-target="#modalLiqCoPropsLiquidar">
										<span>Liquidar</span>
									</button>


									<div id="infoPropiedad" class="alert alert-warning" style="color:#313131; width: 100% !important; <?php echo $mostrarAlerta; ?>" role="alert">
										<strong>Estimado usuario :</strong> Debe haber movimientos pendientes de liquidar para poder realizar una liquidación.
									</div>



								</div>
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="liqCoPropietarios" class="table table-striped" cellspacing="0" width="100%" style="display:none">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Propietario</th>
															<th>Monto Total a Pagar</th>
															<th>Porcentaje</th>
															<th>Monto a Pagar</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableLiqCoPropietarios as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</a> -->
																		<a href="" target="_blank" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver liquidación">
																			<i class="fa-regular fa-file-pdf" style="font-size: .75rem;"></i>
																		</a>
																		<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																			<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																		</button>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>


												</table>

												<table id="liProp1" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Ficha Arriendo</th>
															<th>Comisión</th>
															<th>IVA</th>
															<th>Abonos</th>
															<th>Descuentos</th>
															<th>Total</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="notasDeCredito" role="tabpanel" aria-labelledby="propiedad-ft-notasDeCredito-tab" tabindex="0">

































					<!-- bruno -->
					<!--Se cambió la vista de Roles desde acá -->

					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Notas de Crédito</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: ...</span></li>
											<li><span>Saldo histórico: ...</span></li>
										</ul>


									</div> -->
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="notasCredito" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Folio Factura</th>
															<th>Monto</th>
															<th>Glosa</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableNotasDeCredito as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</a> -->
																		<!-- <a href="https://gladius-control-prop.s3.sa-east-1.amazonaws.com/property/106418/co_ownership_liquidations/liquidaci_n-copropietario-60011.pdf?X-Amz-Expires=604800&X-Amz-Date=20240220T123851Z&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAUDM5QTO3L5F4JLQF%2F20240220%2Fsa-east-1%2Fs3%2Faws4_request&X-Amz-SignedHeaders=host&X-Amz-Signature=4dc0315b2556a3ccac04702af4cb9196272c56a7638bedf402cb6a49fe887d6a" target="_blank" type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Ver liquidación">
																				<i class="fa-regular fa-file-pdf" style="font-size: .75rem;"></i>
																			</a>
																			<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																				<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																			</button> -->
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>



				<div class="tab-pane" id="roles" role="tabpanel" aria-labelledby="propiedad-ft-roles-tab" tabindex="0">

					<!-- cabecera    -->
					<div class="content content-page">

						<!-- contenido de la pagina  -->
						<div class="tab-content" id="nav-tabContent">

							<!-- tabla -->
							<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
								<div class="card">
									<div class="card-body ">
										<div class="table-responsive overflow-auto">
											<h3>Roles</h3>
											<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
												<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento" data-bs-toggle="modal" data-bs-target="#modalRolIngreso">
													<span>Ingresar Rol</span>
												</button>
											</div>
											<!-- Tabla para mostrar los datos -->
											<div class="table-responsive overflow-auto">
												<table id="tablaValoresRol" class="table table-striped">
													<thead>
														<tr>
															<!-- <th>ID</th> -->

															<th>Nùmero</th>
															<th>Principal</th>
															<th>Descripcìon</th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<!-- Aquí se llenarán dinámicamente las filas -->
													</tbody>
												</table>
											</div>


										</div>
									</div>
								</div>
							</div>

						</div>

					</div>
					<!-- Modal ROLES - INGRESAR Rol-->
					<div class="modal fade" id="modalRolIngreso" tabindex="-1" aria-labelledby="modalRolIngresoLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalRolIngresoLabel">Ingresar Rol</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="formRolIngreso" action="post">
										<div class="row">
											<div class="col-md mb-3">
												<label for="modalRolNumero" class="form-label"><span class="obligatorio">*</span> Número</label>
												<input type="text" maxlength="11" class="form-control" id="modalRolNumero" placeholder="Ingrese Número" required>
											</div>
										</div>

										<div class="row">
											<div class="col-md mb-3">
												<label for="modalRolDescripcion" class="form-label">Descripción</label>
												<input type="text" maxlength="50" class="form-control" id="modalRolDescripcion" placeholder="Descripción" required>
											</div>
										</div>

										<div class="row">
											<label for="modalRolPrincipal" class="form-label"><span class="obligatorio">*</span> Es el Rol Principal?</label>
											<label class="switch ms-3 my-2">
												<input value="false" type="checkbox" class="" id="modalRolPrincipal" name="modalRolPrincipal">
												<span class="slider round"></span>
											</label>
										</div>
									</form>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
									<button id="btnGuardarRol" type="button" class="btn btn-primary">Guardar</button>
								</div>
							</div>
						</div>
					</div>


					<!-- Modal ROLES - EDITAR Rol-->
					<div class="modal fade" data-bs-backdrop="static" id="modalRolEditar" tabindex="-1" aria-labelledby="modalRolEditarLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalRolEditarLabel">Editar Rol</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="formEditarRol">
										<div class="row">
											<div class="col-md mb-3">
												<label for="modalRolEditarNumero" class="form-label"><span class="obligatorio">*</span> Número</label>
												<input type="text" class="form-control" id="modalRolEditarNumero" placeholder="Ingrese Número">
											</div>
										</div>

										<div class="row">
											<label for="modalEditarRolPrincipal" class="form-label"><span class="obligatorio">*</span> Es el Rol Principal?</label>
											<label class="switch ms-3 my-2">
												<input value="false" type="checkbox" id="modalEditarRolPrincipal" name="modalEditarRolPrincipal">
												<span class="slider round"></span>
											</label>
										</div>


									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
									<button id="btnEditarRol" type="button" class="btn btn-primary">Guardar</button>
								</div>
							</div>
						</div>
					</div>



					<!-- Modal agregar valor  -->
					<div class="modal modal-lg fade" id="ModalAgregarValor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Valor Rol</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">

									<form id="ingreso_valor_rol" action="" name="ingreso_valor_rol" method="post" enctype="multipart/form-data">
										<div class="row g-3">
											<input type="hidden" id="id_propiedad" name="id_propiedad">
											<input type="hidden" id="id_propiedades_roles">

											<div class="col-lg-5">
												<div class="form-group">
													<label><span class="obligatorio">*</span> Año</label>
													<input type="text" name="valorRolAño" id="valorRolAño" o class="form-control" />
												</div>
											</div>

											<div class="col-lg-5">
												<div class="form-group">
													<label for="valorRolvalor">Valor</label>
													<span id="valorRolvalor" class="conteo-input">0/30</span>
													<input required type="number" class="form-control" maxlength="30" name="ValorRol" id="ValorRol" oninput="conteoInput('ValorRol','valorRolvalor');" placeholder="Valor" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
												</div>
											</div>

											<div class="col-lg-5">
												<div class="form-group">
													<select class="form-select" aria-label="cuota" name="mes" id="mes">
														<option selected>Selecciona una cuota</option>
														<option value="1">Abril</option>
														<option value="2">Junio</option>
														<option value="3">Septiembre</option>
														<option value="4">Noviembre</option>
													</select>
												</div>
											</div>

										</div>
									</form>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancelar</button>
									<button type="button" class="btn btn-primary" id="btnGrabar">Grabar</button>
								</div>

							</div>
						</div>
					</div>

					<!-- Modal editar valor -->
					<div class="modal modal-lg fade" id="ModalEditarValor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1050;">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Editar Valor Rol</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="editar_valor_rol" action="" name="editar_valor_rol" method="post" enctype="multipart/form-data">
										<div class="row g-3">
											<div class="col-lg-5">
												<div class="form-group">
													<label><span class="obligatorio">*</span> Año</label>
													<span id="rolValorAñoEdit" class="conteo-input">0/4</span>
													<input required type="number" class="form-control" maxlength="4" name="valorRolAñoEdit" id="valorRolAñoEdit" oninput="conteoInput('valorRolAñoEdit','rolValorAñoEdit');" placeholder="Año" data-validation-required="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
												</div>
											</div>
											<div class="col-lg-5">
												<div class="form-group">
													<label for="valorRolvalorEdit">Valor</label>
													<span id="valorRolvalorEdit" class="conteo-input">0/30</span>
													<input required type="number" class="form-control" maxlength="30" name="ValorRolEdit" id="ValorRolEdit" oninput="conteoInput('ValorRolEdit','valorRolvalorEdit');" placeholder="Valor" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
												</div>
											</div>
											<div class="col-lg-5">
												<div class="form-group">
													<select class="form-select" aria-label="cuota" name="mesEdit" id="mesEdit">
														<option selected>Selecciona una cuota</option>
														<option value="1">Abril</option>
														<option value="2">Junio</option>
														<option value="3">Septiembre</option>
														<option value="4">Noviembre</option>
													</select>
												</div>
											</div>
										</div>
										<input type="hidden" id="idEdit" name="idEdit">
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancelar</button>
									<button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
								</div>
							</div>
						</div>
					</div>


					<!-- Modal detalle -->
					<div class="modal modal-xl fade" id="ModalDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Valores Rol</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">

									<div class="d-flex justify-content">
										<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalAgregarValor">
											Agregar Valor
										</button>
									</div>

									<!-- contenido de la pagina  -->
									<div class="tab-content" id="nav-tabDetalleRolValo">

										<!-- tabla -->
										<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
											<div class="card">
												<div class="card-body ">
													<div class="table-responsive overflow-auto">


														<!-- Tabla para mostrar los datos -->
														<div class="table-responsive overflow-auto">
															<table id="tablaValoresRolDetalle" class="table table-striped">
																<thead>
																	<tr>
																		<!-- <th>ID</th> -->
																		<th>Año</th>
																		<th>Valor</th>
																		<th>Cuota</th>
																		<th>¿Cobrado?</th>
																		<th>¿Pagado?</th>
																	</tr>
																</thead>
																<tbody>
																	<!-- Aquí se llenarán dinámicamente las filas -->
																</tbody>
															</table>
														</div>


													</div>
												</div>
											</div>
										</div>

									</div>

								</div>

							</div>
						</div>
					</div>
				</div>

				<!-- hasta acá -->
				<!-- bruno -->

				<div class="tab-pane" id="recordatorios" role="tabpanel" aria-labelledby="propiedad-ft-recordatorios-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Recordatorios</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: ...</span></li>
											<li><span>Saldo histórico: ...</span></li>
										</ul>


									</div> -->
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Recordatorio" data-bs-toggle="modal" data-bs-target="#modalRecordatoriosNuevo">
										<span>Agregar Recordatorio</span>
									</button>

								</div>
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="ListadoRecordatiorios" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha Notificación</th>
															<th>Tipo</th>
															<th>Descripción</th>
															<th>Ejecutivo</th>
															<th>Realizado</th>
															<!-- <th>Acciones</th> -->
														</tr>
													</thead>
													<tbody>

													</tbody>


												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="historial" role="tabpanel" aria-labelledby="propiedad-ft-historial-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Historial</h3>
								<!-- <div class="row">
										<ul>
											<li><span>Saldo al día: ...</span></li>
											<li><span>Saldo histórico: ...</span></li>
										</ul>


									</div> -->
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="historial-table" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Responsable</th>
															<th>Acción</th>
															<th>Recurso</th>
															<th>ID</th>
															<th>Cambios</th>
														</tr>
													</thead>



												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

	<!-- Modal Cuenta Corriente - Ingresar Descuento-->
	<div class="modal fade" data-bs-backdrop="static" id="modalCuentaCorrienteIngresoDescuento" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoDescuentoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoDescuentoLabel">Ingresar Descuento</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="Ingrese Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="email" class="form-control" id="modalMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="Pesos">
							</div>
						</div>


						<div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>

								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

								<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php echo @$archivo; ?>
							</div>
						</div>

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Cuotas</label>
								<input type="email" class="form-control" id="modalMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="20/02/2024">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Abono-->
	<div class="modal fade" data-bs-backdrop="static" id="modalCuentaCorrienteIngresoAbono" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoAbonoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoAbonoLabel">Ingresar Abono</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">

						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="modalRazon" placeholder="Ingrese Razón">
							</div>
						</div>

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" class="form-control" id="modalMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<input type="text" class="form-control" id="modalMoneda" placeholder="Pesos">
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">

								<strong>Documentos</strong><br>
								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />
								<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php echo @$archivo; ?>

							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="20/02/2024">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal Cuenta Servicios - Fijar Estado-->
	<div class="modal fade" data-bs-backdrop="static" id="modalCuentaServiciosFijarEstado" tabindex="-1" aria-labelledby="modalCuentaServiciosFijarEstadoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaServiciosFijarEstadoLabel">Fijar Estado</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('cta_servicio')"></button>
				</div>
				<div class="modal-body">
					<form id="cta_servicio" name="cta_servicio" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="modalCtaServicioCuenta" class="form-label"><span class="obligatorio">*</span> Cuenta Servicio</label>
								<?php echo $opcion_tipo_servicio; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="modalCtaServicioFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="modalCtaServicioFecha" name="modalCtaServicioFecha" placeholder="">
							</div>
						</div>


						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="modalCtaServicioMontoAdeudado" class="form-label"><span class="obligatorio">*</span> Monto Adeudado</label>
								<input type="number" min="0" class="form-control" id="modalCtaServicioMontoAdeudado" name="modalCtaServicioMontoAdeudado" value="">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('cta_servicio')">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarInfoCtaServicio()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Liquidaciones CO-Propietarios - Liquidar-->
	<!-- <div class="modal fade" id="modalLiqCoPropsLiquidar" tabindex="-1" aria-labelledby="modalLiqCoPropsLiquidarLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalLiqCoPropsLiquidarLabel">Liquidación</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form action="./components/propiedad/models/liquidar_pdf_fpdf.php" method="POST" id="formLiquidar" target="_blank">
							<div class="row">
								<input type="hidden" name="ficha_tecnica" id="ficha_tecnica" value="<?php echo $ficha_tecnica; ?>">

								<div class="col-md-12 mb-3">
									<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Liquidación</label>
									<input type="text" class="form-control" id="motivoLiquidacion" placeholder="Primera Liquidación">
								</div>

							</div>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Mes Liquidación</label>
									<input type="month" class="form-control" id="mesLiquidacion" placeholder="02-2024">
								</div>

							</div>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label for="exampleFormControlTextarea1" class="form-label">Mensaje</label>
									<textarea class="form-control" id="mensajeLiquidacion" style="padding:1rem" placeholder="Ingrese un comentario..."></textarea>
								</div>
							</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Crear</button>
					</div>
					</form>
				</div>
			</div>
		</div> -->

	<!-- Modal Liquidaciones CO-Propietarios - Liquidar-->
	<div class="modal fade" data-bs-backdrop="static" id="modalLiqCoPropsLiquidar" tabindex="-1" aria-labelledby="modalLiqCoPropsLiquidarLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLiqCoPropsLiquidarLabel">Liquidación</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<!-- <form action="./components/propiedad/models/liquidar_pdf_fpdf.php" method="POST" id="formLiquidar" target="_blank"> -->
					<form action="./components/officesbanking/models/Api.php" method="POST" id="formLiquidar" target="_blank">
						<div class="row">
							<input type="hidden" name="ficha_tecnica" id="ficha_tecnica" value="<?php echo $ficha_tecnica; ?>">

							<div class="col-md-12 mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Liquidación</label>
								<input type="text" class="form-control" id="motivoLiquidacion" placeholder="Ingrese texto">
							</div>

						</div>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Mes Liquidación</label>
								<input type="month" class="form-control" id="mesLiquidacion" placeholder="">
							</div>

						</div>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label for="exampleFormControlTextarea1" class="form-label">Nota</label>
								<textarea class="form-control" id="mensajeLiquidacion" style="padding:1rem" placeholder="Ingrese una nota..."></textarea>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Crear</button>
				</div>
				</form>
			</div>
		</div>
	</div>







	<!-- Modal Recordatorios - Nuevo Recordatorio-->
	<div class="modal fade" data-bs-backdrop="static" id="modalRecordatoriosNuevo" tabindex="-1" aria-labelledby="modalRecordatoriosNuevoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalRecordatoriosNuevoLabel">Nuevo Recordatorio</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<form action="POST" id="formRecordatorio">
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="tipoRecordatorio" class="form-label">
									<span class="obligatorio">*</span> Tipo Recordatorio
								</label>
								<select class="form-control" id="tipoRecordatorio" name="tipoRecordatorio" required>
									<option value="">Selecciona un tipo</option>
									<!-- Opciones de tipo de recordatorio aquí -->
								</select>
							</div>
							<div class="col-md-6">
								<label for="nombreEjecutivo" class="form-label">Ejecutivo</label>
								<input type="text" class="form-control" id="nombreEjecutivo" name="nombreEjecutivo" disabled>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="frecuenciaRecordatorio" class="form-label">
									<span class="obligatorio">*</span> Frecuencia Recordatorio
								</label>
								<select class="form-control" id="frecuenciaRecordatorio" name="frecuenciaRecordatorio" required>
									<option value="">Selecciona una frecuencia</option>
									<option value="DIARIAMENTE">Diariamente</option>
									<option value="SEMANALMENTE">Semanalmente</option>
								</select>

							</div>
							<div class="col-md-6">
								<label for="repeticionesRecordatorio" class="form-label">Repetir</label>
								<input type="text" class="form-control" id="repeticionesRecordatorio" name="repeticionesRecordatorio" placeholder="0" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="fechaNotificacion" class="form-label">
									<span class="obligatorio">*</span> Fecha Notificación
								</label>
								<input type="date" class="form-control" id="fechaNotificacion" name="fechaNotificacion" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-12">
								<label for="descripcionRecordatorio" class="form-label">Descripción</label>
								<input type="text" class="form-control" id="descripcionRecordatorio" name="descripcionRecordatorio" placeholder="Descripción" required>
							</div>
						</div>

						<!-- Otros campos del formulario -->
						<input type="hidden" id="idPropiedad" name="idPropiedad">
						<input type="hidden" id="idEjecutivo" name="idEjecutivo">
					</form>

					<div class="modal-footer">
						<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
						<button id="btnGuardarRecordatorio" type="button" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal ROLES - Ingresar Rol-->
	<div class="modal fade" data-bs-backdrop="static" id="modalRolIngreso" tabindex="-1" aria-labelledby="modalRolIngresoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalRolIngresoLabel">Ingresar Rol</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">
						<div class="row">
							<div class="col-md mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Descripción o Razón</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="Ingrese Descripción o Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md mb-3">
								<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Número</label>
								<input type="email" class="form-control" id="modalMonto" placeholder="Ingrese Número">
							</div>

						</div>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Propietarios - Ingresar Beneficiario-->
	<div class="modal fade" data-bs-backdrop="static" id="modalBeneficiarioIngreso" tabindex="-1" aria-labelledby="modalBeneficiarioIngresoLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalBeneficiarioIngresoLabel">Ingreso Beneficiario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_beneficiario')"></button>
				</div>
				<div class="modal-body">
					<form id="ingreso_beneficiario" action="" name="ingreso_beneficiario" method="post" enctype="multipart/form-data">
						<input type="hidden" name="ficha_tecnica" id="ficha_tecnica" value="<?php echo $ficha_tecnica; ?>">
						<input type="hidden" name="idPropietario" id="idPropietario" value="">
						<input type="hidden" name="idRegistro" id="idRegistro" value="">


						<div class="row g-3">
							<div class="col-lg-4">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Nombre</label>
									<span id="cuentaNombre" class="conteo-input">0/60</span>
									<input required type="text" class="form-control" maxlength="60" name="nombreBeneficiario" id="nombreBeneficiario" oninput="conteoInput('nombreBeneficiario','cuentaNombre');" placeholder="Nombre" required data-validation-required value="<?php echo @$result->datosNatural->nombres; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label><span class="obligatorio">*</span> RUT</label>
									<input required type="text" oninput="checkRut(this);" class="form-control" maxlength="100" name="rutBeneficiario" id="rutBeneficiario" placeholder="Rut" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Correo Electrónico</label>
									<span id="cuentaCorreo" class="conteo-input">0/30</span>
									<input required type="email" class="form-control" maxlength="250" name="correoElectronicoBeneficiario" id="correoElectronicoBeneficiario" oninput="conteoInput('correoElectronicoBeneficiario','cuentaCorreo');" placeholder="Correo Electrónico" value="<?php echo @$result->correoElectronico; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="beneficiarioTelefonoFijo">Teléfono Fijo</label>
									<span id="cuentaTelefonoFijo" class="conteo-input">0/30</span>
									<input required type="text" class="form-control" maxlength="30" name="beneficiarioTelefonoFijo" id="beneficiarioTelefonoFijo" oninput="conteoInput('beneficiarioTelefonoFijo','cuentaTelefonoFijo');validarNumero(this);" placeholder="Teléfono Fijo" value="<?php echo @$result->telefonoFijo; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="beneficiarioTelefonoMovil">Teléfono Móvil</label>
									<span id="cuentaMovil" class="conteo-input">0/30</span>
									<input required type="text" class="form-control" maxlength="30" name="beneficiarioTelefonoMovil" id="beneficiarioTelefonoMovil" oninput="conteoInput('beneficiarioTelefonoMovil','cuentaMovil');validarNumero(this);" placeholder="Teléfono Móvil" value="<?php echo @$result->telefonoMovil; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
						</div>

						<fieldset class="form-group border-0 p-3">
							<legend>
								<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Datos Cuenta</h5>

							</legend>
							<div class="row g-3">
								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> Nombre Titular</label>
										<input required readonly type="text" class="form-control" maxlength="100" name="nombreTitular" id="nombreTitular" placeholder="Nombre Titular" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> RUT</label>
										<input required readonly type="text" oninput="checkRut(this);" class="form-control" maxlength="100" name="rutTitular" id="rutTitular" placeholder="Rut" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> Email Titular</label>
										<input required readonly type="email" class="form-control" maxlength="100" name="emailTitular" id="emailTitular" placeholder="Email Titular" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label for="banco"><span class="obligatorio">*</span> Banco</label>
										<?php echo $opcion_banco; ?>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="cta-banco"><span class="obligatorio">*</span> Tipo de Cuenta</label>
										<?php echo $opcion_cta_banco; ?>

									</div>
								</div>
								<input type="hidden" name="idCuenta" value="">
								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> Número de Cuenta</label>
										<input required type="number" min="0" class="form-control" maxlength="100" name="numCuenta" id="numCuenta" placeholder="Número Cuenta" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>
							</div>
						</fieldset>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_beneficiario')">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarInfoBeneficiario()">Guardar</button>
				</div>
			</div>
		</div>
	</div>



	<!-- Modal Cuenta Corriente - Ingresar Pago No Liquidable-->
	<div class="modal fade" data-bs-backdrop="static" id="modalCuentaCorrienteIngresoPagoNoLiquidable" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoPagoNoLiquidableLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoPagoNoLiquidableLabel">Ingresar Abono</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_pago_no_liquidable" name="cc_pago_no_liquidable" method="post" enctype="multipart/form-data">

						<div class="row">
							<div class="col mb-3">
								<label for="ccTipoMovimientoAbono" class="form-label"><span class="obligatorio">*</span> Tipo Movimiento</label>
								<select class="form-select form-select-sm"  name="ccTipoMovimientoAbono" id="ccTipoMovimientoAbono">
								</select>
							</div>
						</div>

						<div class="row">
							<div class="col mb-3">
								<label for="ccIngresoPagoNLRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="ccIngresoPagoNLRazon" placeholder="Ingrese Razón">
							</div>
						</div>

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoPagoNLMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" class="form-control" id="ccIngresoPagoNLMonto" placeholder="Ingrese Monto">
							</div>

							<script>
								$("#ccIngresoPagoNLMonto").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>

							<div class="col-md-4 mb-3">
								<label for="ccIngresoPagoNLMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoPagoNLMoneda" id="ccIngresoPagoNLMoneda" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="ccIngresoPagoNLFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccIngresoPagoNLFecha">
							</div>
						</div>

						<input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $ficha_tecnica; ?>">

					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcAbono()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar cargo Autorizado-->
	<div class="modal fade" data-bs-backdrop="static" id="modalCuentaCorrienteIngresoDescuentoAutorizado" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoDescuentoAutorizadoLabel" aria-hidden="true">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoDescuentoAutorizadoLabel">Ingresar Cargo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_descuento_autorizado" name="cc_descuento_autorizado" method="post" enctype="multipart/form-data">
						<div class="row">

							<div class="col mb-3">
								<label for="ccTipoMovimiento" class="form-label"><span class="obligatorio">*</span> Tipo Movimiento</label>
								<select class="form-select form-select-sm"  name="ccTipoMovimiento" id="ccTipoMovimiento">
								</select>
							</div>

						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="ccIngresoDescAutorizadoRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="ccIngresoDescAutorizadoRazon" placeholder="Ingrese Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoDescAutorizadoMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" class="form-control" id="ccIngresoDescAutorizadoMonto" placeholder="Ingrese Monto">
							</div>

							<script>
								$("#ccIngresoDescAutorizadoMonto").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>

							<div class="col-md-4 mb-3">
								<label for="ccIngresoDescAutorizadoMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoDescAutorizadoMoneda" id="ccIngresoDescAutorizadoMoneda" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>


						<!-- <div class="row">
								<div class="col-md-12">
									<strong>Documentos</strong><br>

									<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

									<input type="hidden" name="archivo_cuenta_corrientebd" id="archivo_cuenta_corrientebd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

									<?php echo @$archivo; ?>
								</div>
							</div> -->

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoDescAutorizadoCobraComision" class="form-label"><span class="obligatorio">*</span> Cobra Comisión</label>
								<select name="ccIngresoDescAutorizadoCobraComision" id="ccIngresoDescAutorizadoCobraComision" class="form-control">
									<option selected>Selecciona una opcion</option>
									<option value="true" id="1">SI</option>
									<option value="false" id="2">NO</option>
								</select>
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoDescAutorizadoFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccIngresoDescAutorizadoFecha">
							</div>
						</div>

						<input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $ficha_tecnica; ?>">

					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcDescuento()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Comentarios - Ingreso-->

	<div class="modal fade" id="modalInfoComentarioIngreso" tabindex="-1" aria-labelledby="modalInfoComentarioIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog" style="max-width: 800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarInfoComentarioLabel">Agregar Comentario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" id="comentario_formulario" name="comentario_formulario" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<label for="ComentarioIngreso" class="form-label">Comentario</label>
								<textarea class="form-control" id="ComentarioIngreso" style="padding:1rem" placeholder="Ingrese un comentario..." value=""></textarea>
							</div>
						</div>
						<input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $ficha_tecnica; ?>">

						<div class="modal-footer mt-4">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onclick="guardarInfoComentario()">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal Comentarios - Edición-->
	<div class="modal fade" id="modalEditarInfoComentario" tabindex="-1" aria-labelledby="modalEditarInfoComentarioLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog" style="max-width: 800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarInfoComentarioLabel">Edición Comentario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="comentario_formulario_editar" name="comentario_formulario_editar" action="javascript: editarInfoComentario();" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<label for="ComentarioEditar" class="form-label">Comentario</label>
								<textarea class="form-control" name="ComentarioEditar" id="ComentarioEditar" style="padding:1rem" placeholder="Ingrese un comentario..." value=""></textarea>
							</div>
						</div>
						<input type="hidden" class="form-control" min="0" name="InfoComentarioTokenEditar" id="InfoComentarioTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						<input type="hidden" name="ID_Info_Comentario_Editar" id="ID_Info_Comentario_Editar">

						<div class="modal-footer mt-4">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-danger">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- <script>
		cargarCCMovimientosList(); 
	</script> -->






<script>
	document.getElementById('formLiquidar').addEventListener('submit', function(event) {
		event.preventDefault(); // Evitar el envío del formulario inmediato para realizar acciones adicionales


		$('#modalLiqCoPropsLiquidar').on('hidden.bs.modal', function(e) {
			location.reload(); // Recargar la página una vez que se haya cerrado el modal
		});

		$('#modalLiqCoPropsLiquidar').on('hidden.bs.modal', function(e) {
			$("#propiedad-ft-liquidacionesCoPropietarios-tab").trigger("click");
		});


		$('#modalLiqCoPropsLiquidar').modal('hide');

		// Luego de cerrar el modal, puedes proceder con el envío del formulario
		event.target.submit();



	});
</script>