<script>loadTipoEstadoContrato();</script>
<h2>Estados Contrato</h2>           
<a href='index.php?component=estadoContrato&view=estadoContrato'><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un Estado de Contrato</span></a>

       <div class="herramientas">
		<button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Id.</th>
                <th>Descripción</th>
                <th>Orden</th>
				<th>Activo</th>
                <th>Ver/Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>