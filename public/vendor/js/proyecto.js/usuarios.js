$(document).ready(function(){
    $('#formularioUSR').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardadoUSR').modal('show');
    });

    $('#btnGuardarUSR').on('click', function(){
        const dato = {
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
            telefono : $('#telefono').val(),
            email : $('#email').val()
        }
        $.ajax({
            url: BASE + 'usuarios/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                    mensajes("Usuario guardado con ID: " + response.id + response.mensaje);
                    $('#formularioUSR')[0].reset();
                }else if(response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(){
                $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){
                $('#modalGuardadoUSR').modal('hide');//ocultamos el modal de guardar
                $('#formularioUSR')[0].reset();
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
        }, 4000);}

});