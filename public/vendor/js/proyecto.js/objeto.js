$(document).ready(function(){

    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null; 
    
    rellenarTabla();
    rellenarTabla2();

    $("#buscador").on('keyup.objetos', function(){
        if ($.fn.DataTable.isDataTable('#tablaOBJ')) {
            $('#tablaOBJ').DataTable().search(this.value).draw();
        }
    });

    $('#formularioFiltroEtiquetas').on('submit', function(e){
        e.preventDefault();
        const formulario = $(this);
        const botonAplicar = $('#aplicarFiltroEtiquetas');
        botonAplicar.prop('disabled', true);

        $.ajax({
            url: BASE + 'objetos/filtrar-etiquetas',
            method: 'POST',
            data: formulario.serialize(),
            dataType: 'json',
            success: function(response){
                if (!response.exito || !Array.isArray(response.objetos)) {
                    mensajes(response.mensaje || 'No se pudo aplicar el filtro.', 'error');
                    return;
                }

                renderizarTabla(response.objetos);
                const etiquetasSeleccionadas = formulario.find('.filtro-etiqueta:checked').map(function(){
                    return $(this).siblings('span').text();
                }).get();
                const filtrosAplicados = etiquetasSeleccionadas;
                const descripcion = $('#descripcionFiltro').val().trim();
                const usuarioSeleccionado = $('#creadoPorFiltro').val();
                const fechaDesde = $('#fechaDesdeFiltro').val();
                const fechaHasta = $('#fechaHastaFiltro').val();

                if (descripcion) {
                    filtrosAplicados.push('descripción: ' + descripcion);
                }
                if (usuarioSeleccionado) {
                    filtrosAplicados.push('dado de alta por: ' + $('#creadoPorFiltro option:selected').text());
                }
                if (fechaDesde || fechaHasta) {
                    const tipoFecha = $('#tipoFechaFiltro option:selected').text().toLowerCase();
                    filtrosAplicados.push(
                        'fecha de ' + tipoFecha + ': ' + (fechaDesde || '...') + ' a ' + (fechaHasta || '...')
                    );
                }

                $('#resumenFiltroEtiquetas').text(
                    filtrosAplicados.length
                        ? 'Filtrado por: ' + filtrosAplicados.join(', ')
                        : 'Sin filtros aplicados'
                );

                const modal = document.getElementById('modalFiltroEtiquetas');
                if (window.bootstrap && window.bootstrap.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(modal).hide();
                }
            },
            error: function(xhr){
                const mensaje = xhr.responseJSON && xhr.responseJSON.mensaje
                    ? xhr.responseJSON.mensaje
                    : 'No se pudo aplicar el filtro. Inténtalo nuevamente.';
                mensajes(mensaje, 'error');
            },
            complete: function(){
                botonAplicar.prop('disabled', false);
            }
        });
    });

    $('#limpiarFiltroEtiquetas').on('click', function(){
        const formulario = $('#formularioFiltroEtiquetas')[0];
        formulario.reset();
        $('#buscarEtiquetaFiltro').trigger('input');
        $('#formularioFiltroEtiquetas').trigger('submit');
    });

    $('#buscarEtiquetaFiltro').on('input', function(){
        const busqueda = this.value.toLocaleLowerCase().trim();
        $('#listaEtiquetasFiltro label').each(function(){
            $(this).toggle($(this).text().toLocaleLowerCase().includes(busqueda));
        });
    });

    // si veníamos de guardar/actualizar un objeto, muestro el mensaje que quedó guardado
    let mensajeGuardado = sessionStorage.getItem('mensajeOBJ');
    if (mensajeGuardado !== null) {
        sessionStorage.removeItem('mensajeOBJ');//lo borro para que no se muestre de nuevo al recargar
        mensajes(mensajeGuardado);
    }

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla(){
        $.ajax({
            url: BASE + 'objetos/api',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                renderizarTabla(response.objetos);
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

    function renderizarTabla(objetos){
        const tablaInicializada = $.fn.DataTable.isDataTable('#tablaOBJ');
        if (tablaInicializada) {
            $('#tablaOBJ').DataTable().destroy();
        }

        let filas = '';
        objetos.forEach(objeto => {
            const id = encodeURIComponent(objeto.id);
            let botones = '';

            if (permiso_OBJ.includes('b')) {
                botones += `<button type="button" class="btn btn-danger btn-sm me-1" id="btnBorrarOBJ" data-id="${escaparHtml(objeto.id)}">
                                <i class="bi bi-trash2-fill"></i>
                            </button>`;
            }

            if (permiso_OBJ.includes('m')) {
                botones += `<a class="btn btn-primary btn-sm me-1" href="${BASE}objetos/editar/${id}">
                                <i class="bi bi-pencil-square"></i>
                            </a>`;
            }

            if (permiso_OBJ.includes('l')) {
                botones += `<a class="btn btn-info btn-sm" href="${BASE}objetos/ver/${id}">
                                <i class="bi bi-eye"></i>
                            </a>`;
            }

            filas += `<tr>
                <td class="text-center">${escaparHtml(objeto.id)}</td>
                <td class="text-center">${escaparHtml(objeto.codigo)}</td>
                <td class="text-center">${escaparHtml(objeto.denominacion)}</td>
                <td class="text-center">${escaparHtml(objeto.descripcion)}</td>
                <td class="text-center">${escaparHtml(objeto.cargado_por)}</td>
                <td class="text-center">${escaparHtml(objeto.fecha_alta)}</td>
                <td class="text-center text-nowrap">${botones}</td>
            </tr>`;
        });
        $('#bodyTablaOBJ').html(filas);

        $('#tablaOBJ').DataTable({
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
                            text: 'Excel',
                            titleAttr: 'Formato Excel'
                        }, {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            titleAttr: 'Formato PDF'
                        }, {
                            extend: 'copyHtml5',
                            text: 'Copiar',
                            titleAttr: 'Formato texto plano'
                        }, {
                            extend: 'csvHtml5',
                            text: 'CSV',
                            titleAttr: 'Formato planilla'
                        }, {
                            extend: 'print',
                            text: 'Imprimir <i class="bi bi-printer-fill"></i>',
                            titleAttr: 'Imprimir tabla'
                        }, {
                            text: 'Papelera <i class="bi bi-trash2-fill"></i>',
                            titleAttr: 'Papelera',
                            className: 'btn btn-outline-secondary',
                            action: function(){
                                window.location.href = BASE + 'objetos/papelera';
                            }
                        }
                    ]
                }
            },
            order: [[0, 'desc']]
        });

        const busquedaActual = $('#buscador').val();
        if (busquedaActual) {
            $('#tablaOBJ').DataTable().search(busquedaActual).draw();
        }
    }

    function escaparHtml(valor){
        const caracteres = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(valor === null || typeof valor === 'undefined' ? '' : valor)
            .replace(/[&<>"']/g, caracter => caracteres[caracter]);
    }
//------------------------------------------------------------------------------------------------------------------------------//
        
    $('#formularioOBJ').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        const dato = new FormData(this);  
        $.ajax({
            url: BASE + 'objetos/insertar',
            method: 'POST',
            data: dato,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    //guardo el mensaje del servidor para mostrarlo ya estando en la ruta objetos
                    sessionStorage.setItem('mensajeOBJ', "Pieza guardada con ID: " + response.id + response.mensaje);
                    $('#formularioOBJ')[0].reset();
                    window.location.href = BASE + 'objetos';//dirije a la vista objetos al toque, sin esperar
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioOBJ')[0].reset();
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                    $('#formularioOBJ')[0].reset();
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
                $('#formularioOBJ')[0].reset();
            },
            complete: function(){
                $('#formularioOBJ')[0].reset();
            } 
        });
    });
//---------------------------------------------------------------------------------------------------//
    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $(document).on('click', '#btnBorrarOBJ', function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoOBJ').on('shown.bs.modal', function(){
            $('#botonBorradoOBJ').focus();
        });
        $('#modalBorradoOBJ').modal('show');
    });
    $('#botonBorradoOBJ').click(function(){
        //Controlador borrar
        $.ajax({
            url: BASE + 'objetos/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                if(response.exito){
                    $('#modalBorradoOBJ').modal('hide');
                    mensajes(response.mensaje);
                    $('#tablaOBJ').DataTable().destroy();
                    rellenarTabla();
                }else{
                    $('#modalBorradoOBJ').modal('hide');
                    mensajes(response.mensaje, 'warning');
                    $('#tablaOBJ').DataTable().destroy();
                    rellenarTabla();
                }
            },
            error: function(){
                $('#modalBorradoOBJ').modal('hide');//ocultamos el modal de borrado
                mensajes('Error en el servidor', 'error');         
            },
            complete: function(){  
            } 
        });
    });
//---------------------------------------------------------------------------------------------------------------//
    //Este si va hacer un update
    $('#OBJactualizar').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        idActualizar = $(this).data('id');
        const dato = new FormData(this);
        //controlador actualizar 
        $.ajax({
            url: BASE + 'objetos/actualizar/' + idActualizar,
            method: 'POST',
            data: dato,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    //guardo el mensaje para mostrarlo ya estando en la ruta objetos
                    sessionStorage.setItem('mensajeOBJ', "Objeto: " + response.mensaje);
                    window.location.href = BASE + 'objetos';//dirije a la vista objetos al toque, sin esperar
                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
            },
            complete: function(){

            } 
        });
    });

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla2(){
        $.ajax({
            url: BASE + 'objetos/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteOBj = " ";
                response.objetos.forEach(objeto => {
                    let boton = '';
                    if (permiso_OBJ.includes('b')) {
                        boton +=    `<button type="submit" class='btn btn-info' 
                                        data-id="${objeto.id}" id="btnRecuperarOBJ">
                                        Resuperar
                                        <i class="bi bi-recycle"></i> 
                                    </button>`;
                    }
                    conteOBj += `<tr>
                        <td class='text-center'>${objeto.id}</td>
                        <td class='text-center'>${objeto.denominacion}</td>
                        <td class='text-center'>${objeto.cargado_por}</td>
                        <td class='text-center'>${objeto.fecha_baja}</td>
                        <td class='text-center'>${boton}</td>
                    </tr>`;
                });
                $('#bodyBrTablaOBJ').html(conteOBj);

                // ACÁ va el bloque de layout
                $('#tablaOBJBr').DataTable({
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
                                }]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaOBJBr').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//
    //obtenemos el selector del boton recuperar de vista papelera
    $(document).on('click',('#btnRecuperarOBJ'),function(){
    //este id a recuperar nos permiter almacenar el id obtenido desde la base de datos para posteriormente ser enviado al controlador
        idARecuperar = $(this).data('id'); 
        //Controlador de recuperacion
        $.ajax({
            url: BASE + 'objetos/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if(response.exito){
                    mensajes('Objeto ' + response.mensaje);
                    $('#tablaOBJBr').DataTable().destroy();
                    rellenarTabla2();
                }else{
                    mensajes(response.mensaje);
                    $('#tablaOBJBr').DataTable().destroy();
                    rellenarTabla2(); 
                }   
            },
            error: function(){
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){
                
            } 
        });
    });
//---------------------------------------------------------------------------------------------------------------//
    // Botón imprimir de la ficha (objetos/ver)
    $('#btn-imprimir').on('click', function(){
        window.print();
    });
//---------------------------------------------------------------------------------------------------------------// 
    function mensajes(mensaje, tipo = 'success'){
        const toast = document.getElementById('toats');
        toast.innerHTML = `${mensaje}`;
        // limpiamos clases de tipo anteriores
        toast.classList.remove('success', 'error', 'warning');
        // agregamos la clase según el tipo
        toast.classList.add(tipo);
        toast.classList.add('show');
        setTimeout(() => {
          toast.classList.remove('show');
        }, 3000);}
//---------------------------------------------------------------------------------------------------------------//
    //muestro el mensaje que quedó guardado del login
    const mensajeLogin = sessionStorage.getItem('mensajeLogin');
    if (mensajeLogin){
        const toast = document.getElementById('toats');
        toast.innerHTML = mensajeLogin;
        // limpiamos clases de tipo anteriores
        toast.classList.remove('success', 'error', 'warning');
        toast.classList.add('success');
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
        //lo borro para que no salga de nuevo al recargar
        sessionStorage.removeItem('mensajeLogin');
    }
});