$(document).ready(function(){
    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null;
    let idEditar = null;

    if ($('#tablaUSR').length) rellenarTabla();
    //llamo a la funcion que rellena la tabla se uduarios en la papelera
    if ($('#tablaUSRPapelera').length) rellenarTabla2();

    // si veníamos de guardar/actualizar un usuario, muestro el mensaje que quedó guardado
    let mensajeGuardado = sessionStorage.getItem('mensajeUSR');
    if (mensajeGuardado !== null) {
        sessionStorage.removeItem('mensajeUSR');//lo borro para que no se muestre de nuevo al recargar
        mensajes(mensajeGuardado);
    }

    let rolSeleccionado = $('#rol').val();
    $('#formularioUSR #rol').on('change', function(){
        if (this.value === 'otros') {
            $(this).val(rolSeleccionado);
            $('#errorNuevoRol').prop('hidden', true);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNuevoRol')).show();
        } else {
            rolSeleccionado = this.value;
        }
    });

    $('.permiso-rol').on('change', function(){
        const grupo = $(this).closest('fieldset');
        if (this.value !== 'l' && this.checked) {
            grupo.find('.permiso-rol[value="l"]:not(:disabled)').prop('checked', true);
        } else if (this.value === 'l' && !this.checked) {
            grupo.find('.permiso-rol').prop('checked', false);
        }
    });

    $('#modalNuevoRol').on('hidden.bs.modal', function(){
        $('#formNuevoRol')[0].reset();
        $('#errorNuevoRol').prop('hidden', true);
    });

    $('#formNuevoRol').on('submit', function(e){
        e.preventDefault();
        $('#errorNuevoRol').prop('hidden', true);
        $('#guardarNuevoRol').prop('disabled', true);
        $.ajax({
            url: BASE + 'usuarios/crear-rol',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if (!response.exito) {
                    $('#errorNuevoRol').text(response.mensaje).prop('hidden', false);
                    return;
                }
                $('<option></option>').val(response.id).text(response.denominacion).insertBefore('#rol option[value="otros"]');
                $('#rol').val(String(response.id));
                rolSeleccionado = String(response.id);
                const nombres = {objeto: 'Piezas', etiqueta: 'Etiquetas', atributo: 'Atributos', usuario: 'Usuarios', sala: 'Salas'};
                const acciones = {l: 'ver', a: 'crear', m: 'editar', b: 'eliminar', f: 'ver fecha de alta'};
                const bloque = $('<div class="mb-3"></div>').append($('<strong></strong>').text(response.denominacion));
                const lista = $('<ul></ul>');
                Object.keys(nombres).forEach(function(modulo){
                    const seleccionados = response.permisos[modulo].split(',');
                    const textos = Object.keys(acciones).filter(codigo => seleccionados.includes(codigo)).map(codigo => acciones[codigo]);
                    lista.append($('<li></li>').text(nombres[modulo] + ': ' + (textos.length ? textos.join(', ') : 'sin permisos')));
                });
                lista.append($('<li></li>').text('Actividad: ' + (response.permisos.actividad === 'l' ? 'ver secciones permitidas' : 'sin permiso')));
                $('#listaPermisosRoles').append(bloque.append(lista));
                $('#formNuevoRol')[0].reset();
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoRol')).hide();
                mensajes(response.mensaje);
            },
            error: function(xhr){
                const mensaje = xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'No se pudo crear el rol';
                $('#errorNuevoRol').text(mensaje).prop('hidden', false);
            },
            complete: function(){
                $('#guardarNuevoRol').prop('disabled', false);
            }
        });
    });

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

                    if (permiso_USR.includes('b') && usuario.puede_gestionar) {
                        botones += `<button type="submit" class="btn btn-danger" id="btnBorrarUSR" data-id="${usuario.id}">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>`;
                    }
                    if (permiso_USR.includes('m') && usuario.puede_gestionar) {
                                botones += `<a class='btn btn-primary' href='${BASE}usuarios/editar/${usuario.id}'>
                                                <i class='bi bi-pencil-square'></i>
                                            </a>`;
                    }

                    conteUSR += `<tr>
                        <td class='text-center'>${usuario.id}</td>
                        <td class='text-center'>${usuario.denominacion}</td>
                        <td class='text-center'>${usuario.email}</td>
                        <td class='text-center'>${usuario.telefono}</td>
                        ${permiso_USR.includes('f') ? `<td class='text-center'>${usuario.fecha_alta}</td>` : ''}
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
                    if (permiso_USR.includes('a') && usuario.puede_gestionar) {
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
                    //guardo el mensaje del servidor para mostrarlo ya estando en la ruta usuarios
                    sessionStorage.setItem('mensajeUSR', "Usuario guardado con ID: " + response.id + response.mensaje);
                    $('#formularioUSR')[0].reset();//limpia formulario
                    window.location.href = BASE + 'usuarios';//dirije a la vista usuarios al toque, sin esperar
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
                    //guardo el mensaje para mostrarlo ya estando en la ruta usuarios
                    sessionStorage.setItem('mensajeUSR', "Usuario: " + response.mensaje);
                    window.location.href = BASE + 'usuarios';//dirije a la vista usuarios al toque, sin esperar

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
    //actualiza los datos del usuario logueado desde configuracion
    //aca no viaja el id ni el rol, el servidor toma el id de la sesion
    $('#formConfiguracion').on('submit',function(e){
        e.preventDefault();//frenamos el envio normal del formulario
        const dato = {
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
            telefono : $('#telefono').val(),
            email : $('#email').val()
        }
        $.ajax({
            url: BASE + 'usuarios/actualizar_configuracion',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes(response.mensaje);
                    $('#password').val('');//limpio la contraseña para que no quede escrita
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
    $('#btnCopiaSeguridad').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: BASE + 'copia_seguridad',
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (respuesta) {
                const url = window.URL.createObjectURL(respuesta);
                const enlace = document.createElement('a');
                enlace.href = url;
                enlace.download = 'copia_seguridad.zip';
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
                window.URL.revokeObjectURL(url);
            },
            error: function (xhr) {
                console.error(xhr);
                if (xhr.response instanceof Blob) {
                    xhr.response.text().then(function (mensaje) {
                        console.error(mensaje);
                    });
                } else {
                    console.error('Error al generar la copia de seguridad');
                }
            }
        });
    });

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