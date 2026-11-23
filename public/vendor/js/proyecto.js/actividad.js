let tablaActividadLista = false;

$(document).ready(function(){
    if (!$('#tablaACT').length) {
        return;
    }

    rellenarTablaACT();

    $('input[name="tipoActividad"]').on('change', function(){
        $('#tipoSeleccionado').text(this.value || 'Todos los tipos');
    }).on('click', function(){
        $('#filtroTipo').prop('open', false).find('summary').trigger('focus');
    });

    $(document).on('click', function(e){
        if (!$(e.target).closest('#filtroTipo').length) {
            $('#filtroTipo').prop('open', false);
        }
    });

    $('#filtroTipo').on('keydown', function(e){
        if (e.key === 'Escape') {
            $(this).prop('open', false).find('summary').trigger('focus');
        }
    });

    $('#criterioActividad').on('change', function(){
        const campos = {fecha: '#filtroFecha', usuario: '#filtroUsuario', accion: '#filtroAccion'};
        $('.valor-actividad').prop('hidden', true);
        $(campos[this.value] || '#filtroTexto').prop('hidden', false);
        $('#valorActividad, #fechaActividad, #usuarioActividad').val('');
        $('#accionTodas').prop('checked', true);
        $('#valorActividad').attr('placeholder', this.value === 'pieza' ? 'Ingresá el ID de la pieza' : 'Ingresá un texto');
        $('#errorActividad').prop('hidden', true);
    });

    $('#filtroActividad').on('submit', function(e){
        e.preventDefault();
        const criterio = $('#criterioActividad').val();
        let valor = $('#valorActividad').val().trim();

        if (criterio === 'fecha') {
            const fecha = $('#fechaActividad').val();
            valor = fecha ? fecha.split('-').reverse().join('/') : '';
        } else if (criterio === 'usuario') {
            valor = $('#usuarioActividad').val();
        } else if (criterio === 'accion') {
            valor = $('input[name="accionActividad"]:checked').val();
        }

        if (criterio === 'pieza' && valor && (!/^\d+$/.test(valor) || Number(valor) < 1)) {
            $('#errorActividad').text('Ingresá un ID de pieza válido').prop('hidden', false);
            return;
        }

        const tipo = $('input[name="tipoActividad"]:checked').val();
        rellenarTablaACT(criterio, valor, tipo);
    });

    $('#limpiarActividad').on('click', function(){
        $('#filtroActividad')[0].reset();
        $('#tipoSeleccionado').text('Todos los tipos');
        $('#filtroTipo').prop('open', false);
        $('#criterioActividad').trigger('change');
        $('#buscador').val('');
        rellenarTablaACT();
    });

    $('#buscador').on('input.actividad', function(){
        if (tablaActividadLista) {
            $('#tablaACT').DataTable().search(this.value).draw();
        }
    });
});

//------------------------------------------------------------------------------------------------//
//esta funcion rellena la tabla de actividad
function rellenarTablaACT(criterio = 'todos', valor = '', tipo = ''){
    let acciones = {'a': 'Alta', 'b': 'Baja', 'm': 'Modificación', 'r': 'Recuperación'};
    let tablas = {
        'objetos': 'Pieza',
        'objeto_etiquetas': 'Etiquetas de pieza',
        'objeto_atributos': 'Atributos de pieza',
        'etiquetas': 'Etiqueta',
        'atributos': 'Atributo'
    };
    const explicacionesAccion = {};
    $('#filtroAccion label[data-accion]').each(function(){
        explicacionesAccion[$(this).attr('data-accion')] = $(this).attr('data-ayuda');
    });
    const explicacionesTipo = {};
    $('#filtroTipo label[data-tipo]').each(function(){
        explicacionesTipo[$(this).attr('data-tipo')] = $(this).attr('data-ayuda');
    });

    $('#errorActividad').prop('hidden', true);
    $('#buscarActividad, #limpiarActividad').prop('disabled', true);
    tablaActividadLista = false;

    $.ajax({
        url: BASE + 'actividad/api',
        method: 'GET',
        data: criterio === 'pieza' && valor ? {id_pieza: valor} : {},
        dataType: 'json',
        success: function(response) {
            if ($.fn.DataTable.isDataTable('#tablaACT')) {
                $('#tablaACT').DataTable().destroy();
            }
            let conteACT = " ";
            response.actividad.forEach(act => {
                conteACT += `<tr>
                    <td class='text-center'>${act.fecha}</td>
                    <td class='text-center'>${act.usuario}</td>
                    <td class='text-center'><span tabindex="0" class="ayuda-actividad" data-ayuda="${explicacionesAccion[acciones[act.accion]]}" aria-label="${acciones[act.accion]}: ${explicacionesAccion[acciones[act.accion]]}">${acciones[act.accion]}</span></td>
                    <td class='text-center'><span tabindex="0" class="ayuda-actividad" data-ayuda="${explicacionesTipo[tablas[act.tabla]]}" aria-label="${tablas[act.tabla]}: ${explicacionesTipo[tablas[act.tabla]]}">${tablas[act.tabla]}</span></td>
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
                                text:'Excel',
                                titleAttr:'Formato Excel'
                            },{
                                extend: 'pdfHtml5',
                                text:'PDF',
                                titleAttr:'Formato PDF'
                            },{
                                extend: 'copyHtml5',
                                text:'Copiar',
                                titleAttr:'Formato texto plano'
                            },{
                                extend: 'csvHtml5',
                                text:'CSV',
                                titleAttr:'Formato planilla'
                            },{
                                extend: 'print',
                                text: 'Imprimir <i class="bi bi-printer-fill"></i>',
                                titleAttr: 'Imprimir tabla'
                            }
                        ]
                    }
                },
                order: [],
                initComplete: function(){
                    const tabla = this.api();
                    const columnas = {fecha: 0, usuario: 1, accion: 2};
                    tabla.column(3).search(tipo, {exact: tipo !== ''});
                    if (criterio === 'todos') {
                        tabla.search.fixed('filtroActividad', valor);
                    } else if (criterio !== 'pieza') {
                        tabla.column(columnas[criterio]).search(valor, {exact: criterio !== 'fecha' && valor !== ''});
                    }
                    tabla.search($('#buscador').val() || '').draw();
                    tablaActividadLista = true;
                    $('#buscarActividad, #limpiarActividad').prop('disabled', false);
                }
            });
        },
        error: function(xhr) {
            const mensaje = xhr.responseJSON && xhr.responseJSON.mensaje
                ? xhr.responseJSON.mensaje
                : 'No se pudo cargar el historial. Intentá nuevamente.';
            $('#errorActividad').text(mensaje).prop('hidden', false);
            tablaActividadLista = $.fn.DataTable.isDataTable('#tablaACT');
            $('#buscarActividad, #limpiarActividad').prop('disabled', false);
        }
    });
}