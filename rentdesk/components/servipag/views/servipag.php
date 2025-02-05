<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadForm" onsubmit="event.preventDefault(); CargarServipag();">

                <div class="d-flex">
                    <div class="p-2 w-100">
                        <label for="formFile" class="form-label">Subir archivo Servipag</label>
                        <input class="form-control" type="file" id="formFile" name="file" required>
                    </div>
                    <div class="p-2 flex-shrink-1">
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Subir</button>
                        </div>
                    </div>
                </div>

            </form>

            <button class="btn btn-dark" onclick="ProcesarListado()">Procesar Pagos</button>

        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-4 mt-0">Monto total Pagado : <span id="montoTotalPagado"></span></h5>
            <table id="servipagTable" class="table caption-top">
                <thead>
                    <tr>
                        <!-- <th style="display: none;">ID</th> -->
                        <th>Nro</th>
                        <th>Rut</th>
                        <th>ID propiedad</th>
                        <th>Direccion</th>
                        <th>Contrato</th>
                        <th>Fecha pago</th>
                        <th>Arriendo</th>
                        <th>Monto Pagado</th>
                        <th>Diferencia</th>
                        <th>POS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>