$(document).ready(function() {
    
    let idABorrar = null;
    let idARecuperar = null;
    let idActualizar = null;
    
    rellenarTabla();
    rellenarTabla2();

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla(){
        $.ajax({
            url: BASE + 'atributos/api',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteATB = " ";
                response.atributos.forEach(atributo => {
                    conteATB += `<tr>
                        <td class='text-center'>${atributo.id}</td>
                        <td class='text-center'>${atributo.denominacion}</td>
                        <td class='text-center'>${atributo.tipo_dato}</td>
                        <td class='text-center'>${atributo.fecha_alta}</td>
                        <td class='text-center'>
                            <button type="submit" class="btn btn-danger" id="btnBorrarATB" data-id="${atributo.id}">
                                <i class='bi bi-trash2-fill'></i>
                            </button>
                            <a class='btn btn-primary' href='${BASE}atributos/editar/${atributo.id}'>
                                <i class='bi bi-pencil-square'></i>
                            </a>
                        </td>
                    </tr>`;
                });
                $('#bodyTablaATB').html(conteATB);

                // ACÁ va el bloque de layout
                $('#tablaATB').DataTable({
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
                                },{   
                                text: 'Papelera <i class="bi bi-trash2-fill"></i>',
                                titleAttr: 'Papelera',
                                className: 'btn btn-outline-secondary',
                                action: function () {
                                    window.location.href = BASE + 'atributos/papelera';
                                }}]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaATB').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla2(){
        $.ajax({
            url: BASE + 'atributos/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteATB = " ";
                response.atributos.forEach(atributo => {
                    conteATB += `<tr>
                        <td class='text-center'>${atributo.id}</td>
                        <td class='text-center'>${atributo.denominacion}</td>
                        <td class='text-center'>${atributo.fecha_baja}</td>
                        <td class='text-center'>
                            <button type="submit" class='btn btn-info' 
                                data-id="${atributo.id}" id="btnRecuperarATB">
                                Resuperar
                            <i class="bi bi-recycle"></i> </button>
                        </td>
                    </tr>`;
                });
                $('#bodyBrTablaATB').html(conteATB);

                // ACÁ va el bloque de layout
                $('#tablaATBBr').DataTable({
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
                                }]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaATBBr').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//  

    // --- Mostrar/ocultar el selector de unidad SIMELA según el tipo de dato ---
    function toggleUnidad() {
        if ($('#tipo_dato').val() === 'numerico') {
            $('#grupoUnidad').show();
        } else {
            $('#grupoUnidad').hide();
            $('#unidad').val(''); // limpiamos la unidad si cambian a otro tipo
        }
    }

    $('#tipo_dato').on('change', toggleUnidad);

    // En editar.php el select ya puede venir con "numerico" precargado,
    // así que ejecutamos la función una vez al cargar la página
    toggleUnidad();

    $('#formularioATB').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardarATB').modal('show');
    });
    //los datos seran enviado una ves obtengamos la accion desde el boton del modal
    $('#btnGuardarATB').on('click', function(){
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
            tipo_dato: $('#tipo_dato').val(),
            unidad: $('#unidad').val(),
        }   
        $.ajax({
            url: BASE + 'atributos/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    $('#modalGuardarATB').modal('hide');//ocultamos el modal de guardar
                    mensajes("Atributo guardado con ID: " + response.id + response.mensaje);
                    $('#formularioATB')[0].reset();
                    toggleUnidad();
                    setTimeout(function(){
                        window.location.href = BASE + 'atributos';
                    },  3010);
                }else if (response.papelera){
                    $('#modalGuardarATB').modal('hide');//ocultamos el modal de guardar
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioATB')[0].reset();
                    toggleUnidad();
                }else{
                    $('#modalGuardarATB').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje, 'error');
                    $('#formularioATB')[0].reset();
                    toggleUnidad();
                }
            },
            error: function() {
                $('#modalGuardadoATB').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor', 'error');
                $('#formularioATB')[0].reset();
                toggleUnidad();
            },
            complete: function(){
                $('#modalGuardadoATB').modal('hide');//ocultamos el modal de guardar
                $('#formularioATB')[0].reset();
                toggleUnidad();
            } 
        });
    });
//---------------------------------------------------------------------------------------------------//
    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $(document).on('click', '#btnBorrarATB', function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoATB').modal('show');
    });
    $('#botonBorradoATB').click(function(){
        //Controlador borrar
        $.ajax({
            url: BASE + 'atributos/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                $('#modalBorradoATB').modal('hide');
                mensajes('Atributo borrado exitosamente');
                $('#tablaATB').DataTable().destroy();
                rellenarTabla();
            },
            error: function(){
                $('#modalBorradoATB').modal('hide');//ocultamos el modal de borrado
                mensajes('Error en el servidor', 'error');         
            },
            complete: function(){  
            } 
        });
    });
//---------------------------------------------------------------------------------------------------------------//
    //obtenemos el selector del boton recuperar de vista papelera
    $(document).on('click',('#btnRecuperarATB'),function(){
    //este id a recuperar nos permiter almacenar el id obtenido desde la base de datos para posteriormente ser enviado al controlador
        idARecuperar = $(this).data('id'); 
        //Controlador de recuperacion
        $.ajax({
            url: BASE + 'atributos/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes(response.mensaje);
                    $('#tablaATBBr').DataTable().destroy();
                    rellenarTabla2();                    
                    
                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#tablaATBBr').DataTable().destroy();
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

    //Este si va hacer un update
    $('#ATBactualizar').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        idActualizar = $(this).data('id');
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
            tipo_dato: $('#tipo_dato').val(),
            unidad: $('#unidad').val(), 
        }
        //controlador actualizar 
        $.ajax({
            url: BASE + 'atributos/actualizar/' + idActualizar,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes("Atributo: " + response.mensaje);
                    setTimeout(function(){
                        window.location.href = BASE + 'atributos';
                    },  3010);
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
            },
            complete: function(){

            } 
        });
    });

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
});