$(document).ready(function(){
    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null;
    //funcion que dispara el modal de usuarios
    $('#formularioUSR').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardadoUSR').modal('show');
    });
    //dispara la accion que se eespera cuando el usuario quiere guardar un nuevo usuario
    $('#btnGuardarUSR').on('click', function(){
        const dato = {
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
            telefono : $('#telefono').val(),
            email : $('#email').val()
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
                    },  5000);
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
            email : $('#email').val()
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
                    },  5000);

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

    $('.btn-borrarUSR').click(function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoUSR').modal('show');
    });
    $('#btnBorradoUSR').click(function(){
        $.ajax({
            url: BASE + 'usuarios/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                $('#modalBorradoUSR').modal('hide');
                mensajes('Usuario borrado exitosamente')
                setTimeout(function(){
                    location.reload();
                }, 5000);
            },
            error: function() {
                $('#modalBorradoUSR').modal('hide');
                mensajes('Error en el servidor', 'error'); 
            },
            complete: function(){} 
        });
    });

    $('.btn-recuperarUSR').click(function(){
        idARecuperar = $(this).data('id');

        $.ajax({
            url: BASE + 'usuarios/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response) {
                if(response.exito){
                    mensajes( response.mensaje);
                    setTimeout(function(){
                        window.location.href = BASE + 'usuarios';
                    },  5000);
                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    setTimeout(function(){
                        location.reload();
                    }, 5000);
                }
            },
            error: function() {
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){} 
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
        }, 4000);}

});