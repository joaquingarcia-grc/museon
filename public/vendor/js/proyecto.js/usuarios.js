$(document).ready(function(){
    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null;
    let idEditar = null;

    rellenarTabla();
    //llamo a la funcion que rellena la tabla se uduarios en la papelera
    rellenarTabla2();

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de usuarios
    function rellenarTabla(){
        $.ajax({
            url: BASE + 'usuarios/api',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteUSR = " ";
                response.usuarios.forEach(usuario => {
                    let botones = '';

                    if (permiso_USR.includes('b')) {
                        botones += `<button type="submit" class="btn btn-danger" id="btnBorrarUSR" data-id="${usuario.id}">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>`;
                    }
                    if (permiso_USR.includes('m')) {
                                botones += `<a class='btn btn-primary' href='${BASE}usuarios/editar/${usuario.id}'>
                                                <i class='bi bi-pencil-square'></i>
                                            </a>`;
                    }

                    conteUSR += `<tr>
                        <td class='text-center'>${usuario.id}</td>
                        <td class='text-center'>${usuario.denominacion}</td>
                        <td class='text-center'>${usuario.email}</td>
                        <td class='text-center'>${usuario.telefono}</td>
                        <td class='text-center'>${usuario.fecha_alta}</td>
                        <td class='text-center'>${botones}</td>
                    </tr>`;
                });
                $('#bodyTabla').html(conteUSR);

                // ACÁ va el bloque de layout
                $('#tablaUSR').DataTable({
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
                                    window.location.href = BASE + 'usuarios/papelera';
                                }}]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaUSR').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//  
    //esta funcion rellena la tabla en la papelera 
    function rellenarTabla2(){
        $.ajax({
            url: BASE + 'usuarios/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteUSR = " ";
                response.usuarios.forEach(usuario => {
                    let boton = '';
                    if (permiso_USR.includes('b')) {
                        boton +=`<button type="submit" class='btn btn-info' id="btnRecuperarUSR" data-id="${usuario.id}">
                                    <i class="bi bi-recycle"></i>
                                </button>`;
                    }
                    conteUSR += `<tr>
                        <td class='text-center'>${usuario.id}</td>
                        <td class='text-center'>${usuario.denominacion}</td>
                        <td class='text-center'>${usuario.email}</td>
                        <td class='text-center'>${usuario.fecha_baja}</td>
                        <td class='text-center'>${boton}</td>
                    </tr>`;
                });
                $('#bodyTabla2').html(conteUSR);

                // ACÁ va el bloque de layout
                $('#tablaUSRPapelera').DataTable({
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
                    $('#tablaclientes').DataTable().search(this.value).draw();
                });
            },
            error: function(){
                mensajes('Error en el servidor','error');
            }
        });
    };
//----------------------------------------------------------------------------------------------------------//

    //funcion que dispara el modal de usuarios
    $('#formularioUSR').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardadoUSR').on('shown.bs.modal', function(){
            $('#btnGuardarUSR').focus();
        });
        $('#modalGuardadoUSR').modal('show');
    });
    //dispara la accion que se eespera cuando el usuario quiere guardar un nuevo usuario
    $('#btnGuardarUSR').on('click', function(){
        const dato = {
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
            telefono : $('#telefono').val(),
            email : $('#email').val(),
            rol : $('#rol').val()
        }
        //este ajax espera la respuesta desde el servidor dependiendo el paquete que sea armado antes de enviar
        //por tanto pienso que el usuario no podra infiltrar datos no deseados
        //existe el caso de que el usuario intente romper este js pero en el caso del servidor no podra alterarlo
        $.ajax({
            url: BASE + 'usuarios/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                    mensajes("Usuario guardado con ID: " + response.id + response.mensaje);//responde mensaje desde el servidor
                    $('#formularioUSR')[0].reset();//limpia formulario
                    setTimeout(function(){//activa funcion que dirije a la vista usuarios
                        window.location.href = BASE + 'usuarios';
                    },  3010);
                }else if(response.papelera){//espera condicion desde la papelera e imprime el mensaje obtenido
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(){//si existiese un error en el servidor podria verse un mensaje de error
                $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){//siempre limpia por defecto en carga
                $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                $('#formularioUSR')[0].reset();
            } 
        });
    });
    $('#actualizarUSR').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario   
        idActualizar = $(this).data('id');  //traemos un id el cual debe de viajar con el paquete de datos
        const dato = {                      //posteriormente este id nos servira para guardar el objeto con el id que obtuvimos de la BBDD
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
            telefono : $('#telefono').val(),
            email : $('#email').val(),
            rol : $('#rol').val()
        }
        //ajax de actualizar espera los datos y el id para poder actualizar el usuario con el id correspondiente
        //para esto trajimos el id desde la base de datos y lo almasenamos en la variable IDACTUALIZAR
        $.ajax({
            url: BASE + 'usuarios/actualizar/'+ idActualizar,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes("Usuario: " + response.mensaje);
                    setTimeout(function(){
                        window.location.href = BASE + 'usuarios';
                    },  3010);

                }else if(response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
            },
            complete: function(){} 
        });
    });

//------------------------------------------------------------------------------------------------//
    //funcion que borra los usuarios
    $(document).on('click', '#btnBorrarUSR', function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoUSR').on('shown.bs.modal', function(){
            $('#btnBorradoUSR').focus();
        });
        $('#modalBorradoUSR').modal('show');
    }); 

    $('#btnBorradoUSR').click(function(){
        $.ajax({
            url: BASE + 'usuarios/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                if(response.exito){
                    $('#modalBorradoUSR').modal('hide');
                    mensajes(response.mensaje)
                    $('#tablaUSR').DataTable().destroy();
                    rellenarTabla();
                }else{
                    $('#modalBorradoUSR').modal('hide');
                    mensajes(response.mensaje, 'error')
                    $('#tablaUSR').DataTable().destroy();
                    rellenarTabla();
                }
            },
            error: function() {
                $('#modalBorradoUSR').modal('hide');
                mensajes('Error en el servidor', 'error'); 
            },
            complete: function(){} 
        });
    });
//------------------------------------------------------------------------------------------------------------//
    $(document).on('click', '#btnRecuperarUSR', function(){
        
        idARecuperar = $(this).data('id');
        
        $.ajax({
            url: BASE + 'usuarios/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response) {
                if(response.exito){
                    mensajes( response.mensaje);
                    $('#tablaUSRPapelera').DataTable().destroy();
                    rellenarTabla2();

                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#tablaUSRPapelera').DataTable().destroy();
                    rellenarTabla2();
                }
            },
            error: function() {
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){} 
        });
    });

//------------------------------------------------------------------------------------------------//
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