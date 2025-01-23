	<div class="content content-page mb-5">

		<br><br>
		<!-- cabecera -->
		<fieldset id="info-cliente" class="form-group border p-3  mt-5">

			<ul class="nav" id="myTab" role="tablist">

				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="cierre-pago-tab" data-bs-toggle="tab" data-bs-target="#cierre-pago-tab-pane"
						type="button" role="tab" aria-controls="cierre-pago-tab-pane" aria-selected="true">
						Cierre Pagos
					</button>
				</li>

				<!-- <li class="nav-item" role="presentation">
					<button class="nav-link" id="cierre-liquidaciones-tab" data-bs-toggle="tab" data-bs-target="#cierre-liquidaciones-tab-pane" type="button" role="tab" aria-controls="cierre-liquidaciones-tab-pane" aria-selected="false">
						Cierre Liquidaciones
					</button>
				</li> -->


				<li class="nav-item" role="presentation">
					<button class="nav-link" id="documentos-liquidaciones-tab" data-bs-toggle="tab" data-bs-target="#documentos-liquidaciones-tab-pane" type="button" role="tab" aria-controls="documentos-liquidaciones-tab-pane" aria-selected="false">
						Documentos Office Banking
					</button>
				</li>

				<li>
					<a href="#" id="abrirModalMenuEditarPass" class="btn btn-primary"><i class="fa-solid fa-key"></i> Cambiar contraseña</a>
				</li>
			</ul>
		</fieldset>

		<!-- Modal Editar Contraseña (restaurando la estructura, conservando IDs nuevos) -->
		<div class="modal fade" id="modalMenuEditarPass" tabindex="-1"
			aria-labelledby="modalMenuEditarPassLabel" aria-hidden="true" data-bs-backdrop="static">
			<div class="modal-dialog" style="max-width: 800px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalMenuEditarPassLabel">Edición Contraseña</h5>
						<!-- Si deseas resetear el formulario al cerrar, usa el onclick -->
						<button type="button" class="btn-close" data-bs-dismiss="modal"
							onclick="resetModalUsuario('formMenuEditarContrasenia')"
							aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<!-- Formulario para editar la contraseña -->
						<form id="formMenuEditarContrasenia" name="formMenuEditarContrasenia"
							action="POST"
							method="POST" enctype="multipart/form-data">
							<div class="row">
								<!-- Primera contraseña -->
								<div class="col-lg-6">
									<div class="form-group">
										<label>Contraseña</label>
										<div class="password-input-container">
											<input
												type="password"
												class="form-control pr-password"
												oninput="conteoInput('contraseniaMenu','cuentaCorreo');"
												maxlength="60"
												name="contraseniaMenu"
												id="contraseniaMenu"
												onblur="elimina_slash(this); elimina_comillas(this); elimina_blancos_inicio_fin(this);">
											<!-- Ajusta la llamada a togglePasswordVisibility() 
                       para usar el nuevo ID con el selector de ícono. -->
											<span
												class="toggle-password fa fa-eye-slash"
												onclick="togglePasswordVisibility('contraseniaMenu', '.toggle-password')">
											</span>
										</div>
									</div>
								</div>

								<!-- Repetir contraseña -->
								<div class="col-lg-6">
									<div class="form-group">
										<label>Ingrese nuevamente contraseña</label>
										<div class="password-input-container">
											<input
												type="password"
												class="form-control"
												oninput="conteoInput('repetirContraseniaMenu','cuentaCorreo');"
												maxlength="60"
												name="repetirContraseniaMenu"
												id="repetirContraseniaMenu"
												onblur="elimina_slash(this); elimina_comillas(this); elimina_blancos_inicio_fin(this);">
											<!-- Ajusta la llamada a togglePasswordVisibility() 
                       para el nuevo ID repetido. -->
											<span
												class="toggle-password-validacion fa fa-eye-slash"
												onclick="togglePasswordVisibility('repetirContraseniaMenu', '.toggle-password-validacion')">
											</span>

										</div>
									</div>
								</div>
							</div>

							<!-- Footer con botones -->
							<div class="modal-footer mt-4">
								<!-- Si deseas resetear el formulario al cerrar, usa el onclick -->
								<button type="button" class="btn btn-info" data-bs-dismiss="modal"
									onclick="resetModalUsuario('formMenuEditarContrasenia')">
									Cerrar
								</button>
								<!-- El ID de este botón se mantiene del formulario nuevo -->
								<button id="btnCambiarContrasenia" type="button" class="btn btn-danger">
									Guardar
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- contenido -->
		<fieldset id="info-cliente" class="form-group border p-3">
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="cierre-pago-tab-pane" role="tabpanel" aria-labelledby="cierre-tab" tabindex="0">
					<div class="content content-page">

						<h1>Cierre Pagos</h1>
						<!-- <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalListadoPropiedeades" onclick="CargarListadoPropiedades()"> Nuevo Cierre. </button> -->

						<div class="row mb-3 h-auto">
							<div class="col-auto p-0 m-0">
								<button id="marcarTodosCierres" class="btn btn-info me-2">Marcar Todos</button>
							</div>
							<div class="col-auto p-0 m-0">
								<button id="DesmarcarTodosCierres" class="btn btn-info me-2">Desmarcar Todos</button>
							</div>
							<div class="col-auto p-0 m-0">
								<button id="generarOfficeBanking" class="btn btn-primary me-2">Generar Office Banking</button>
							</div>
							<div class="col-3 p-0 my-auto">
								<input id="barraBusquedaLiquidacion" class="form-control" type="search" placeholder="Buscar por id cierre" aria-label="Buscar por id cierre">
							</div>
						</div>
						<?php require 'components/officesbanking/views/components/tables/CierreLiquidaciones.php' ?>

					</div>
				</div>
				<div class="tab-pane fade" id="cierre-liquidaciones-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
					<div class="content content-page">
						<h1>Cierre Liquidaciones</h1>
						<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalListadoPropiedeades" onclick="CargarListadoPropiedades()"> Nuevo Cierre. </button>

					</div>
				</div>
				<div class="tab-pane fade" id="documentos-liquidaciones-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
					<div class="content content-page">
						<!-- El contenido dinámico de la tabla será cargado aquí por JavaScript -->
					</div>
				</div>
			</div>

		</fieldset>

	</div>