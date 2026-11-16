$(document).ready(function(){
    rellenarTablaACT();
});

//------------------------------------------------------------------------------------------------//
//esta funcion rellena la tabla de actividad
function rellenarTablaACT(){
    let acciones = {'a': 'Alta', 'b': 'Baja', 'm': 'Modificación', 'r': 'Recuperación'};
    let tablas = {
        'objetos': 'Pieza',
        'objeto_etiquetas': 'Etiquetas de pieza',
        'objeto_atributos': 'Atributos de pieza',
        'etiquetas': 'Etiqueta',
        'atributos': 'Atributo'
    };

    $.ajax({
        url: BASE + 'actividad/api',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let conteACT = " ";
            response.actividad.forEach(act => {
                conteACT += `<tr>
                    <td class='text-center'>${act.fecha}</td>
                    <td class='text-center'>${act.usuario}</td>
                    <td class='text-center'>${acciones[act.accion]}</td>
                    <td class='text-center'>${tablas[act.tabla]}</td>
                    <td class='text-center'>${act.id_dato}</td>
                </tr>`;
            });
            $('#bodyTablaACT').html(conteACT);

            $('#tablaACT').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.7/i18n/es-ES.json'
                },
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                    bottom1Start: {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text:'excel',
                                titleAttr:'formato exel'
                            },{
                                extend: 'pdfHtml5',
                                text:'pdf',
                                titleAttr:'formato pdf'
                            },{
                                extend: 'copyHtml5',
                                text:'copiar',
                                titleAttr:'formato texto plano'
                            },{
                                extend: 'csvHtml5',
                                text:'csv',
                                titleAttr:'formato planilla'
                            },{
                                extend: 'print',
                                text: 'imprimir <i class="bi bi-printer-fill"></i>',
                                titleAttr: 'imprimir tabla'
                            }
                        ]
                    }
                },
                order: []
            });

            $("#buscador").on('keyup', function(){
                $('#tablaACT').DataTable().search(this.value).draw();
            });
        },
        error: function() {
            mensajes('Error en el servidor','error');
        }
    });
}