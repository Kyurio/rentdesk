<script src="js/region_ciudad_comuna.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<?php
$config		= new Config;
$peso_archivo = $config->maxSizeMB;
?>

<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});
</script>



<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Arriendo</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Arriendo</li>
		</ol>
	</div>
</div>

<div class="content content-page">

	<div>
		<?php if ($token) : ?>
			<h1> Edición de Arriendo</h1>
		<?php else : ?>
			<h1> Creación de Arriendo</h1>
		<?php endif; ?>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
	</div>
	<form name="formulario" id="formulario" method="post" action="javascript: enviarRentdesk();" enctype="multipart/form-data" class="my-3">
		<input type="hidden" id="id_propiedad" name="id_propiedad" value="<?php echo $id_propiedad; ?>">
		<div class="row g-3">
			<fieldset class="form-group border p-3" id="section-Informacion">



				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información </h5>
				</legend>



				<div class="row">
					<div class="col text-left" id="Propiedad"><label><strong>Propiedad #<?php echo $id_propiedad ?></strong></label><br> <label></label></div>
					<div class="col text-left"><label><strong>Arrendatario Tipo: <?php if ($tipo_persona == 1) {
																						echo "Natural";
																					} else {
																						echo "Juridico";
																					} ?> </strong></label><br> <label id="Arrendatarios"></label></div>
					<div class="col text-left"><label><strong>Codeudor</strong></label><br> <label id="Codeudor"></label></div>
				</div>

				<div class="row">
					<div class="col"><label>Dirección:</label><a href="index.php?component=propiedad&view=propiedad&token=<?php echo $token_propiedad ?>" target="_blank"> <?php echo $direccion_propiedad ?> ,N° <?php echo $numero_propiedad ?> </a><br> <label id="direccionPropiedad"></label></div>
					<div class="col"><label>Nombre:
							<a href="index.php?component=arrendatario&view=arrendatario&token=<?php echo $token_arrendatario ?>" target="_blank"> <?php echo $nombre_arrendatario ?></a>
							<!-- Button trigger modal -->
							<br>

							<?php if ($cantidadArrendatario > 1) { ?>

								<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
									<i class="fa-solid fa-plus"></i>
								</button>
							<?php } ?>


							<br>
							<label id="nombreArrendador"></label>
					</div>
					<div class="col"><label>Nombre: </label><a href="index.php?component=codeudor&view=codeudor&token=<?php echo $token_codeudor ?>" target="_blank"> <?php echo $nombre_codeudor ?> </a><br> <label id="nombreCodeudor"></label></div>
				</div>
				<div class="row">
					<div class="col"><label>Comuna: <?php echo $comuna_propiedad ?></label><br> <label id="comunaPropiedad"></label></div>
					<div class="col"><label>Mail: <?php echo $correo_electronico_arrendatario ?></label><br> <label id="mailArrendador"></label></div>
					<div class="col"><label>Mail: <?php echo $correo_electronico_codeudor ?></label><br> <label id="mailCodeudor"></label></div>
				</div>
				<div class="row">
					<div class="col"><label>Región: <?php echo $region_propiedad ?></label><br> <label id="regionPropiedad"></label></div>
					<div class="col"><label>Telefono: <?php echo $telefono_arrendatario ?></label><br> <label id="telefonoArrendador"></label></div>
					<div class="col"><label>Telefono: <?php echo $telefono_codeudor ?></label><br> <label id="telefonoCodeudor"></label></div>
				</div>
			</fieldset>
			<div id="Titulo-contrato">
				<span>
					<span></span> Datos del contrato de arriendo
				</span>
			</div>



			<!-- Modal detalle arrendatarios-->
			<div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>

						<div class="modal-body">
							<div class="mt-2">
								<h6>Arrendatarios:</h6>
								<?php


								foreach ($jsonArrendatarios  as $arrendatario) {
									echo "<b><i class='fa-solid fa-user'></i> Nombre: </b>" . $arrendatario->nombre . "<br>";
								}

								?>
							</div>
						</div>
					</div>
				</div>
			</div>


			<fieldset class="form-group border p-3" id="section-Contrato">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Contrato</h5>
				</legend>
				<div class="row g-3">
					<div class="col-md-2">
						<label for="fechaInicio"><span class="obligatorio">*</span> Fecha Inicio</label>
						<input name="fechaInicio" id="fechaInicio" onchange="recalcularMes()" class="form-control" type="date" value="<?php echo $fecha_inicio ?>" />
						<span id="startDateSelected"></span>
					</div>
					<div class="col-md-2">
						<label for="fechaTermino"> Fecha Término Real</label>
						<input name="fechaTermino" id="fechaTermino" onchange="recalcularMes()" class="form-control" type="date" value="<?php echo $fecha_termino_real ?>" />
						<input name="fechaTerminoAux" id="fechaTerminoAux" class="form-control" type="hidden" value="<?php echo $fecha_termino_real ?>" />
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="duracionContrato"><span class="obligatorio">*</span> Duración Contrato en meses</label>
							<input type="hidden" min="0" class="form-control" name="duracionContrato" id="duracionContrato" placeholder="Duración Contrato" required data-validation-required autofocus value="<?php echo $duracion_contrato_meses ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							<div for="mesesContrato" class="mesesContrato" id="mesesContrato"></div>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="monedaContrato"><span class="obligatorio">*</span> Moneda</label>
							<?php echo $opcion_tipo_moneda_precio; ?>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="precioContrato"><span class="obligatorio">*</span> Precio</label>
							<input type="text" min="0" class="form-control" name="precioContrato" id="precioContrato" placeholder="Precio" required data-validation-required autofocus value="<?php echo $precio ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>




					<script>
						$("#precioContrato").keyup(function(event) {
							if (event.which >= 37 && event.which <= 40) {
								event.preventDefault();
							}

							var moneda = $('#monedaContrato').val();

							if (moneda == 2) {
								$(this).val(function(index, value) {
									return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
								});
							}

						});
					</script>

					<script>
						$(document).ready(function() {
							var fechaincial1 = moment($("#fechaInicio").val(), "YYYY-MM-DD");
							var fechainicial2 = moment($("#fechaTermino").val(), "YYYY-MM-DD");
							var diferenciaMesesInicial = fechainicial2.diff(fechaincial1, 'months');
							$('#duracionContrato').val(diferenciaMesesInicial);
							$("#mesesContrato").text(diferenciaMesesInicial);



						});

						function recalcularMes() {
							var fecha1 = moment($("#fechaInicio").val(), "YYYY-MM-DD");
							var fecha2 = moment($("#fechaTermino").val(), "YYYY-MM-DD");
							var diferenciaMeses = fecha2.diff(fecha1, 'months');
							if (fecha1.isAfter(fecha2)) {
								Swal.fire({
									title: "Atencion!",
									text: "La fecha de inicio no puede ser mayor que la fecha de termino",
									icon: "warning"
								});
								$('#fechaInicio').val("<?php echo $fecha_inicio ?>");
								$('#fechaTermino').val("<?php echo $fecha_termino_real ?>");
								$('#mesesContrato').val("12");
								$('#duracionContrato').val("12");

							} else {
								$('#duracionContrato').val(diferenciaMeses);
								$("#mesesContrato").text(diferenciaMeses);
							}
						}
					</script>

					<div class="col-md-2">
						<div class="form-group">
							<label for="estadoContrato"><span class="obligatorio">*</span> Estado Contrato</label>
							<?php echo $estado_contrato; ?>
						</div>
					</div>


				</div>
				<div class="row g-3">
					<div class="col-md-2">
						<div class="form-group">
							<label for="montoGarantia"><span class="obligatorio">*</span> Monto Garantía</label>
							<input type="text" min="0" class="form-control" name="montoGarantia" id="montoGarantia" placeholder="Monto Garantía" required data-validation-required autofocus value="<?php echo $monto_garantia; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" required <?php if ($flag_disabled_garantia == 1) {
																																																																																		echo "readonly";
																																																																																	} ?>>
							<small>Valor Cuota aprox: <label id="valor_cuota">$<?php echo $monto_por_cuota; ?></label> </small>
						</div>
					</div>
					<div class="col-md-2">

						<div class="form-group">
							<label for="cuotasGarantia"><span class="obligatorio">*</span> Cuotas Garantía</label>
							<?php echo $select_Cuotas; ?>
						</div>
					</div>

					<script>
						$("#montoGarantia").keyup(function(event) {
							if (event.which >= 37 && event.which <= 40) {
								event.preventDefault();
							}
							$(this).val(function(index, value) {
								return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
							});
						});
					</script>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mesesGarantia"><span class="obligatorio">*</span> Meses Garantía</label>
							<input type="text" min="0" class="form-control" name="mesesGarantia" id="mesesGarantia" placeholder="Meses Garantía" required data-validation-required autofocus value="<?php echo $mesesGarantia; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" required <?php if ($flag_disabled_garantia == 1) {
																																																																																		echo "readonly";
																																																																																	} ?>>
						</div>
					</div>

					<script>
						$("#mesesGarantia").keyup(function(event) {
							if (event.which >= 37 && event.which <= 40) {
								event.preventDefault();
							}
							$(this).val(function(index, value) {
								return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
							});
						});
					</script>



					<div class="col-md-2">
						<div class="form-group">
							<label for="pagoGarantiaProp"><span class="obligatorio">*</span> ¿Pago de garantía a propietario?</label>
							<select name="pagoGarantiaProp" id="pagoGarantiaProp" class="form-control">
								<option selected="selected" value="SI" id="1">Si</option>
								<option value="NO" id="2">No</option>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="cobroMesCalendario"><span class="obligatorio">*</span> ¿Cobrar mes Calendario?</label>
							<select name="cobroMesCalendario" id="cobroMesCalendario" class="form-control">
								<option selected="selected" value="SI" id="1">Si</option>
								<option value="NO" id="2">No</option>
							</select>
						</div>
					</div>



					<script>
						$("#montoMultaAtraso").keyup(function(event) {
							if (event.which >= 37 && event.which <= 40) {
								event.preventDefault();
							}
							$(this).val(function(index, value) {
								return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
							});
						});
					</script>


				</div>
				<div class="row g-3">

					<div class="col-md-2">
						<div class="form-group">
							<label for="tipoMulta">Tipo Multa</label>
							<?php echo $opcion_tipo_multa; ?>

						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="monedaMulta"><span class="obligatorio">*</span> Moneda Multa</label>
							<?php echo $opcion_tipo_moneda_multa; ?>

						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="montoMultaAtraso"><span class="obligatorio">*</span> Monto Multa por atraso</label>
							<input type="text"
								class="form-control"
								name="montoMultaAtraso" id="montoMultaAtraso"
								placeholder="Monto multa atraso"
								value="<?php echo $montoMultaAtraso; ?>"
								onBlur="validarMontoMulta();  ValidarMontoMoneda('tipomulta')" required>

						</div>

						<script>
							// Función para aplicar el formato adecuado basado en la moneda seleccionada
							function aplicarFormatoMoneda() {
								var tipo_moneda = $('#tipoMulta').val(); // Obtener el tipo de moneda seleccionado

								if (tipo_moneda == '2') { // Si la moneda es "Pesos"
									$('#montoMultaAtraso').on('input', function() {
										var value = $(this).val().replace(/[^\d]/g, ''); // Solo números
										var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Formato de miles
										$(this).val(formattedValue);
									});
								} else { // Para cualquier otra moneda
									$('#montoMultaAtraso').on('input', function() {
										var value = $(this).val().replace(/[^0-9.,]/g, ''); // Solo permitir números, comas y puntos
										var parts = value.split(/[.,]/); // Separar parte entera y decimal
										if (parts.length > 2) {
											value = parts[0] + '.' + parts[1]; // Mantener solo la parte antes y después del primer separador decimal
										}
										$(this).val(value);
									});
								}
							}

							// Ejecutar la función cuando se cargue la página
							$(document).ready(function() {
								aplicarFormatoMoneda(); // Llama la función al cargar la página

								// Cambiar el formato automáticamente cuando cambie el tipo de moneda
								$('#tipoMulta').on('change', function() {
									$('#montoMultaAtraso').off('input'); // Desactivar cualquier event listener previo
									aplicarFormatoMoneda(); // Aplicar el nuevo formato
								});

								// Evitar que las teclas de navegación alteren el formato
								$('#montoMultaAtraso').on('keydown', function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
								});
							});
						</script>


					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="montoMultaAtraso" id="titulodiascobro"><span class="obligatorio">*</span> Dias para pago desde ùltimo cobro</label>
							<input type="number" min="0" max="31" class="form-control" name="diascobro" id="diascobro" placeholder="Monto pago ultimo cobro" autofocus value="<?php echo $diascobro; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

				</div>


		</div>

		</fieldset>
		<fieldset class="form-group border p-3" id="section-Reajuste">
			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Reajuste</h5>
			</legend>
			<div class="row g-3">
				<div class="col-md">
					<div class="form-group">
						<label for="tipoReajuste"><span class="obligatorio">*</span> Tipo Reajuste</label>
						<?php echo $opcion_tipo_ajuste ?>
					</div>
				</div>


				<script>
					// Obtener el campo al cargar la página
					var valorCampo = document.getElementById('tipoReajuste').value;
					// Mostrar el valor en la consola
					console.log('El valor del campo es: ' + valorCampo);
				</script>
				<div class="col-md">

					<div class="form-group">
						<label for="mesesReajuste"><span class="obligatorio">*</span> Meses Reajuste</label>
						<select style="display:none" id="meses" name="meses[]" class="js-example-responsive" multiple="multiple">
							<?php foreach ($meses as $index => $mes): ?>
								<option value="<?= $index + 1 ?>"
									<?= is_array($mesesSeleccionados) && in_array($index + 1, array_column($mesesSeleccionados, 'id_mes_reajuste')) ? 'selected' : '' ?>>
									<?= $mes ?>
								</option>
							<?php endforeach; ?>
						</select>

					</div>

				


				</div>

				<div class="col-md">
					<div class="form-group">
						<label for="permiteReajusteNegativo">¿Permite Reajuste Negativo?</label>
						<select name="permiteReajusteNegativo" id="permiteReajusteNegativo" class="form-control">
							<?php echo $opcion_piscina; ?>
						</select>
					</div>
				</div>
				<div class="col-md">
					<div class="form-group">
						<label for="cantReajuste">Cantidad Reajuste</label>
						<div class="input-group mb-3">
							<input type="text" min="0" class=" form-control" name="CantidadReajuste" id="CantidadReajuste" value="<?php echo $cantidadReajuste ?>" onBlur="validarTipoReajuste(); ValidarMontoMoneda('reajuste');" aria-label="Cantidad Reajuste" aria-describedby="basic-addon2">
						</div>
					</div>
				</div>

				<script>
					$("#CantidadReajuste").keyup(function(event) {
						if (event.which >= 37 && event.which <= 40) {
							event.preventDefault();
						}
						$(this).val(function(index, value) {
							return value.replace(/[^\d.]/g, "") // Elimina todos los caracteres que no sean dígitos o puntos
								.replace(/^100(\.0{1,2})?|[1-9]?\d(\.\d{0,2})?$/, function(match) {
									return match; // Devuelve el número validado sin modificar
								});
						});
					});
				</script>

			</div>
			<?php if ($json_mes_ajustes_registro) : ?>
				<label> <strong>Importante: </strong>Existe al menos un mes especial</label>

			<?php endif; ?>

			<div class="row">
				<button class="btn btn-info btn-mas-filtros" style="width:auto; text-align:left;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Rentas especiales <i class="fas fa-chevron-down"></i></button>
				<div class="collapse col-12 col-md-12 col-lg-12 p-0" id="collapseExample">
					<div class="row">
						<div class="col-md-12">
							<hr>
							<div id="meses" class="row g-2">
								<?php
								// Imprimir todos los selects generados dinámicamente
								foreach ($selectsMeses as $select) {
									echo $select;
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="form-group border p-3" id="section-Comisiones">
			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Comisiones</h5>
			</legend>
			<div class="row g-3">
				<div class="col-md">
					<fieldset class="form-group border-0 p-3">
						<legend>
							<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Administración</h5>
						</legend>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label for="cobrarComisionArriendo">¿Cobrar Administración de Arriendo?</label>
									<select name="cobrarComisionArriendo" id="cobrarComisionArriendo" class="form-control">
										<option selected="selected" value="true" id="1">Si</option>
										<option value="false" id="2">No</option>
									</select>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="monedaComisionArriendo"><span class="obligatorio">*</span> Moneda Administración de Arriendo</label>
									<?php echo $opcion_tipo_moneda_comision_arriendo; ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="comisionArriendo"><span class="obligatorio">*</span> Administración de Arriendo</label>
									<input type="text" class="form-control" min="0" name="comisionArriendo" id="comisionArriendo" value="<?php echo $arriendo_comision_monto; ?>" onBlur="validarComisionArriendo(); elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this); ValidarMontoMoneda('administracion')" required>
								</div>
							</div>

							<script>
								$("#comisionArriendo").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/[^\d.]/g, "") // Elimina todos los caracteres que no sean dígitos o puntos
											.replace(/^100(\.0{1,2})?|[1-9]?\d(\.\d{0,2})?$/, function(match) {
												return match; // Devuelve el número validado sin modificar
											});
									});



								});
							</script>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label for="tipoFacturaComisionArriendo"><span class="obligatorio">*</span> tipo de documento</label>
									<?php echo $opcion_documento_arriendo; ?>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label for="facturarComisionAdministracion">¿Cobrar Administraciónde en primera liquidación?</label>
									<select name="facturarComisionAdministracion" id="facturarComisionAdministracion" class="form-control" style="width: 120px">
										<option selected="selected" value="SI" id="1">Si</option>
										<option value="NO" id="2">No</option>
									</select>
								</div>
							</div>
						</div>
						<br>
					</fieldset>
				</div>
				<div class="col-md">
					<fieldset class="form-group border-0 p-3">
						<legend>
							<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Corretaje</h5>
						</legend>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label for="cobrarComisionAdministracion">¿Cobrar corretaje de arriendo?</label>
									<select name="cobrarComisionAdministracion" id="cobrarComisionAdministracion" class="form-control">
										<option selected="selected" value="true" id="1">Si</option>
										<option value="false" id="2">No</option>
									</select>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="monedaComisionAdministracion"><span class="obligatorio">*</span> Moneda corretaje arriendo</label>
									<?php echo $opcion_tipo_moneda_comision_adm; ?>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="comisionAdministracion"><span class="obligatorio">*</span> corretaje arriendo</label>
									<input type="text" class="form-control" min="0" name="comisionAdministracion" id="comisionAdministracion" value="<?php echo $adm_comision_monto; ?>" onBlur="validarComisionAdministracion(); elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this); ValidarMontoMoneda('corretaje')" required>
								</div>
							</div>
							<script>
								$("#comisionAdministracion").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/[^\d.]/g, "") // Elimina todos los caracteres que no sean dígitos o puntos
											.replace(/^100(\.0{1,2})?|[1-9]?\d(\.\d{0,2})?$/, function(match) {
												return match; // Devuelve el número validado sin modificar
											});
									});
								});
							</script>

						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
								
									<!-- <label for="tipoFacturaComisionAdministracion"><span class="obligatorio">*</span>tipo de documento</label> -->
									<?php echo $opcion_documento_adm; ?>

								</div>
							</div>

						</div>
					</fieldset>
				</div>
			</div>
		</fieldset>

		<fieldset class="form-group border p-3" id="section-ServicioYSeguros">
			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Servicio Básicos</h5>
			</legend>
			<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Servicio Básico" data-bs-toggle="modal" data-bs-target="#modalServicioIngreso">
				<span>Agregar Servicio Básico</span>
			</button>

			<div class="modal fade" id="modalServicioIngreso" tabindex="-1" aria-labelledby="modalServicioIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog" style="max-width: 800px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalServicioIngresoLabel">Agregar Servicio Básico</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>

						<div class="modal-body">
							<div class="row g-3"> <!-- Clase Bootstrap para espaciar las filas -->
								<div class="col-lg-4">
									<div class="form-group">
										<label for="TipoServicio">Tipo de servicios</label>
										<?php echo $opcion_servicio_basico ?>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="TipoProveedorServicio">Proveedor</label>
										<select id="TipoProveedorServicio" name="TipoProveedorServicio" class="form-control" data-select2-id="TipoProveedorServicio"></select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="numeroCliente">Número cliente</label>
										<input type="text" class="form-control" name="numeroCliente" id="numeroCliente" autofocus>
									</div>
								</div>
							</div>

						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal" onClick="resetearFieldsetServicio();">Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onClick="guardaServicio();">Guardar</button>
						</div>
					</div>

				</div>
			</div>
			<div class="modal fade" id="modalSeguroIngreso" tabindex="-1" aria-labelledby="modalSeguroIngresoLabel" aria-hidden="true" data-bs-backdrop="static">


				<div class="modal-dialog" style="max-width: 800px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalServicioIngresoLabel">Agregar Seguro</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
							<div class="row">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoServicioSeguro">Tipo de seguro</label>
											<?php echo $opcion_seguro ?>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoProveedorSeguro">Compañía</label>
											<select id='TipoProveedorSeguro' name='TipoProveedorSeguro' class='form-control ' data-select2-id='TipoProveedorSeguro'>
											</select>
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="montoServicioSeguro"> Monto</label>
											<!-- <input type="text" min="0" class="form-control" name="montoServicioSeguro" id="montoServicioSeguro" placeholder="Monto" autofocus value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"> -->
											<input type="text" min="0" class="form-control" name="montoServicioSeguro" id="montoServicioSeguro" oninput="onInput(this)" oninput="onInput(this)" onblur="validarComas(this)">
										</div>
									</div>
									<script>
										// $("#montoServicioSeguro").keyup(function(event) {
										// 	if (event.which >= 37 && event.which <= 40) {
										// 		event.preventDefault();
										// 	}
										// 	$(this).val(function(index, value) {
										// 		return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
										// 	});
										// });
									</script>
								</div>

								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="PlanSeguro">Número de Póliza</label>
											<input type="text" class="form-control" name="PlanSeguro" id="PlanSeguro" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="monedaServicioSeguro"> Tipo Moneda</label>
											<select name="monedaServicioSeguro" id="monedaServicioSeguro" class="form-control">
												<option value="Pesos" id="1" selected>Pesos</option>
												<option value="UF" id="2">UF</option>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="periocidadServicioSeguro"> Periocidad</label>
											<select name="periocidadServicioSeguro" id="periocidadServicioSeguro" class="form-control">
												<option value="anual" id="1" selected>Anual</option>
												<option value="semestral" id="2">Semestral</option>
												<option value="mensual" id="3">Mensual</option>
												<option value="trimestral" id="4">Trimestral</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="servicioSeguroFechaInicio">Fecha Inicio</label>
											<input name="servicioSeguroFechaInicio" id="servicioSeguroFechaInicio" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="servicioSeguroFechaVencimiento">Fecha vencimiento</label>
											<input name="servicioSeguroFechaVencimiento" id="servicioSeguroFechaVencimiento" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="servicioSeguroNotificacion">Recibir alerta de término</label>
											<select name="servicioSeguroNotificacion" id="servicioSeguroNotificacion" class="form-control">
												<option value="true" id="1" selected>Si</option>
												<option value="false" id="2">No</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal" onClick='resetearFieldsetSeguro();'>Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onClick='guardaSeguro();'>Guardar</button>
						</div>
					</div>
				</div>


			</div>
			<div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="modalEditarServicioLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog" style="max-width: 800px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalEditarServicioLabel">Edicion Seguro básico</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
							<div class="row">

								<div class="row">

									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoEditarServicio">Tipo de servicios</label>
											<select onClick='buscaProveedorEditar("basico")' id='TipoEditarServicio' name='TipoEditarServicio' class='form-control' data-select2-id='TipoEditarServicio'>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoProveedorEditar">Proveedor</label>
											<select id='TipoProveedorEditar' name='TipoProveedorEditar' class='form-control ' data-select2-id='TipoProveedorEditar'>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="numeroClienteEditar">Número cliente</label>
											<input type="text" class="form-control" name="numeroClienteEditar" id="numeroClienteEditar" autofocus value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

										</div>
									</div>

								</div>

								<!-- <div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="PlanEditar">Plan Servicio</label>
											<input type="text" class="form-control" name="PlanEditar" id="PlanEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="monedaEditar"> Tipo Moneda</label>
											<select name="monedaEditar" id="monedaEditar" class="form-control">
												<option value="Pesos" id="1" selected>Pesos</option>
												<option value="UF" id="2">UF</option>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="periocidadEditar"> Periocidad</label>
											<select name="periocidadEditar" id="periocidadEditar" class="form-control">
												<option value="anual" id="1" selected>Anual</option>
												<option value="semestral" id="2">semestral</option>
												<option value="mensual" id="3">mensual</option>
												<option value="trimestral" id="4">trimestral</option>
											</select>
										</div>
									</div>
								</div> -->

								<!-- <div class="row">

									<div class="col-lg-4">
										<div class="form-group">
											<label for="montoEditar"> Monto</label>
											<input type="text" min="0" class="form-control" name="montoEditar" id="montoEditar" placeholder="Monto" autofocus value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"> 
											<input type="text" min="0" class="form-control" name="montoEditar" id="montoEditar" placeholder="Monto" autofocus value="" oninput="onInput(this)" onblur="validarComas(this)">
										</div>
									</div> -->



								<!-- <script>
										$("#montoEditar").keyup(function(event) {
											if (event.which >= 37 && event.which <= 40) {
												event.preventDefault();
											}
											$(this).val(function(index, value) {
												return value.replace(/[^\d.]/g, "") // Elimina todos los caracteres que no sean dígitos o puntos
													.replace(/^100(\.0{1,2})?|[1-9]?\d(\.\d{0,2})?$/, function(match) {
														return match; // Devuelve el número validado sin modificar
													});
											});
										});
									</script> -->



								<!-- <div class="col-lg-4">
										<div class="form-group">
											<label for="servicioFechaInicioEditar">Fecha Inicio</label>
											<input name="servicioFechaInicioEditar" id="servicioFechaInicioEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="servicioFechaVencimientoEditar">Fecha vencimiento</label>
											<input name="servicioFechaVencimientoEditar" id="servicioFechaVencimientoEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>
								</div> -->

							</div>
						</div>

						<input type="hidden" class="form-control" min="0" name="ServicioTokenEditar" id="ServicioTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarServicio();'>Guardar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modalEditarSeguro" tabindex="-1" aria-labelledby="modalEditarSeguroLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog" style="max-width: 800px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalEditarSeguroLabel">Edicion Seguro </h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
							<div class="row">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoEditarSeguro">Tipo de seguro</label>
											<select onClick='buscaProveedorEditar("seguro")' id='TipoEditarSeguro' name='TipoEditarSeguro' class='form-control ' data-select2-id='TipoEditarSeguro'>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="TipoProveedorEditarSeguro">Compañía</label>
											<select id='TipoProveedorEditarSeguro' name='TipoProveedorEditarSeguro' class='form-control ' data-select2-id='TipoProveedorEditarSeguro'>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="montoEditarSeguro"> Monto</label>
											<input type="text" min="0" class="form-control" name="montoEditarSeguro" id="montoEditarSeguro" placeholder="Monto" autofocus value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>
									<script>
										$("#montoEditarSeguro").keyup(function(event) {
											if (event.which >= 37 && event.which <= 40) {
												event.preventDefault();
											}
											$(this).val(function(index, value) {
												return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
											});
										});
									</script>
								</div>

								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="PlanEditarSeguro">Número de Póliza</label>
											<input type="text" class="form-control" name="PlanEditarSeguro" id="PlanEditarSeguro" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="monedaEditarSeguro"> Tipo Moneda</label>
											<select name="monedaEditarSeguro" id="monedaEditarSeguro" class="form-control">
												<option value="Pesos" id="1" selected>Pesos</option>
												<option value="UF" id="2">UF</option>
											</select>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="periocidadEditarSeguro"> Periocidad</label>
											<select name="periocidadEditarSeguro" id="periocidadEditarSeguro" class="form-control">
												<option value="anual" id="1" selected>Anual</option>
												<option value="semestral" id="2">semestral</option>
												<option value="mensual" id="3">mensual</option>
												<option value="trimestral" id="4">trimestral</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="seguroFechaInicioEditar">Fecha Inicio</label>
											<input name="seguroFechaInicioEditar" id="seguroFechaInicioEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>


									<div class="col-lg-4">
										<div class="form-group">
											<label for="seguroFechaVencimientoEditar">Fecha vencimiento</label>
											<input name="seguroFechaVencimientoEditar" id="seguroFechaVencimientoEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
											<span id="startDateSelected"></span>
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label for="servicioSeguroNotificacionEditar">Recibir alerta de término</label>
											<select name="servicioSeguroNotificacionEditar" id="servicioSeguroNotificacionEditar" class="form-control">
												<option value="true" id="1" selected>Si</option>
												<option value="false" id="2">No</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<input type="hidden" class="form-control" min="0" name="SeguroTokenEditar" id="SeguroTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarSeguro();'>Guardar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<div class="table-responsive overflow-auto">

						<table id="servicios" class="table table-striped" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Servicio básico</th>
									<th>Proveedor</th>
									<th>Plan servicio</th>
									<th>Número cliente</th>
									<th>Tipo moneda</th>
									<th style='text-align: right;'>Monto</th>
									<th>Periodo</th>

									<th>Fecha inicio</th>
									<th>Fecha vencimiento</th>
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
			<br>
			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Seguros</h5>
			</legend>
			<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Seguro" data-bs-toggle="modal" data-bs-target="#modalSeguroIngreso">
				<span>Agregar Seguro</span>
			</button>
			<div class="card">
				<div class="card-body">
					<div class="table-responsive overflow-auto">

						<table id="seguros" class="table table-striped" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Seguro</th>
									<th>Compañía</th>
									<th>Número de Póliza</th>
									<th>Tipo moneda</th>
									<th style='text-align: right;'>Monto</th>
									<th>Periodo</th>
									<th>Fecha inicio</th>
									<th>Fecha vencimiento</th>
									<th>Fecha modificacion</th>
									<th>Recibir alerta de término</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>


							</tbody>
						</table>
					</div>
				</div>
			</div>

		</fieldset>


		<fieldset class="form-group border p-3" id="section-Documentos">

			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Documentos</h5>
			</legend>

			<button type="button" id="btn_agregar_documento" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Documento" data-bs-toggle="modal" data-bs-target="#modalDocumentoIngreso">
				<span>Agregar Documento</span>
			</button>

			<div class="modal fade" id="modalDocumentoIngreso" tabindex="-1" aria-labelledby="modalDocumentoIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
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

									<!--	                        
										<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
											<div class="form-group">
												<label for="documentoFecha">Fecha vencimiento</label>
												<input name="documentoFecha" id="documentoFecha" onchange="recalcularMes()" class="form-control" type="date" value="" />
												<span id="startDateSelected"></span>
											</div>
										</div>
									-->

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
							<button type="button" class="btn btn-danger" id="addItemButton" onClick='cargaDocumento("arriendo");'>Guardar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="modalDocumentoEditar" tabindex="-1" aria-labelledby="modalDocumentoEditarLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog" style="max-width: 800px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalDocumentoEditarLabel">Editar documento</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

							<div class="row" style="width:100%;">
								<!-- Se deja solo el nombre documentoTituloEditar para editar
									<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
										<div class="form-group">
										<label for="documentoTituloEditar">Titulo</label>
										<input type="hidden" class="form-control" min="0" name="documentoTituloEditar" id="documentoTituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>

							</div> -->
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
			</div>

			<div class="modal fade" id="modalTituloEditar" tabindex="-1" aria-labelledby="modalTituloEditarLabel" aria-hidden="true" data-bs-backdrop="static">
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
			</div>

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
		</fieldset>
</div>
<script>
	function formatCurrencyInput(inputId) {
		$("#" + inputId).on("keyup", function(event) {
			if (event.which >= 37 && event.which <= 40) {
				event.preventDefault();
			}
			$(this).val(function(index, value) {
				return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
			});
		});
	}
</script>

<script>
	$("#diasPagoUltimoCobroEnero").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroFebrero").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroMarzo").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroMayo").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroAbril").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroJunio").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroJulio").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroAgosto").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroSeptiembre").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroOctubre").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroNoviembre").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>
<script>
	$("#diasPagoUltimoCobroDiciembre").keyup(function(event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}
		$(this).val(function(index, value) {
			return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		});
	});
</script>


<div class="col-lg-12 text-center">
	<a type="button" class="btn btn-info" id="bt-volver" onClick="salirArriendo()"> &lt;&lt; volver </a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type="submit" class="btn btn-danger" class="btn btn-danger" id="bt-aceptar"> Guardar </button>
</div>


</form>
</div>