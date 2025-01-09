function LeerServipag() {

    $('#servipagTable').DataTable({
        ajax: {
            url: 'components/servipag/models/leercargaservipag.php', 
            dataSrc: ''
        },

        
        columns: [
            { data: 'canal_de_pago' },
            { data: 'oficina' },
            { data: 'txcliente' },
            { data: 'id_documento' },
            { data: 'boleta' },
            { data: 'rut_cliente' },
            { data: 'id_pago' },
            { data: 'monto' },
            { data: 'medio_pago' },
            { data: 'nro_serie_doc' },
            { data: 'banco' },
            { data: 'plaza_banco' },
            { data: 'ctacte' },
            { data: 'fecha_pago' },
            { data: 'hora' },
            { data: 'fecha_contab' },
            { data: 'tipo_trx' },
            { data: 'procesado' }
        ]

       
    });

}


$(document).ready(function () {

    LeerServipag();

});