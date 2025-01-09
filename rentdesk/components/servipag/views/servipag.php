<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadForm" onsubmit="event.preventDefault(); CargarServipag();">

                <div class="d-flex">
                    <div class="p-2 w-100">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Subir archivo</label>
                            <input class="form-control" type="file" id="formFile" name="file" required>
                        </div>
                    </div>
                    <div class="p-2 flex-shrink-1">
                        <button type="submit" class="btn btn-primary">Subir y Procesar</button>
                    </div>
                </div>

            </form>


            <button id="btnSeleccionarTodo" onclick="seleccionarTododeseleccionarTodo()" class="btn btn-success">Seleccionar Todo</button>
            <button id="btnDeseleccionarTodo" onclick="seleccionarTododeseleccionarTodo()" class="btn btn-danger">Deseleccionar Todo</button>
            <button class="btn btn-dark" onclick="ProcesarListado()">Procesar</button>

        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <table id="servipagTable" class="table caption-top">
                <caption>Servipag</caption>
                <thead>
                    <tr>
                        <td>Rut</td>
                        <td>Direccion</td>
                        <td>Contrato</td>
                        <td>fecha pago</td>
                        <td>Arriendo</td>
                        <td>Monto Pagado</td>
                        <td>UF a PESOS</td>
                        <td>Diferencia</td>
                        <td>procesar</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>