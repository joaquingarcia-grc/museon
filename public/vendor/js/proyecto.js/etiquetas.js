$(document).ready(function() {
    
    let idABorrar = null;
    let idARecuperar = null;
    let idActualizar = null;

    $('#formularioET').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardarET').modal('show');
    });
    //los datos seran enviado una ves obtengamos la accion desde el boton del modal
    $('#modalGuardadoET').on('click', function(){
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
        }   
        $.ajax({
            url: BASE + 'etiquetas/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    $('#modalGuardarET').modal('hide');//ocultamos el modal de guardar
                    mensajes("Etiqueta guardada con ID: " + response.id + response.mensaje,'success');
                    $('#formularioET')[0].reset();
                    setTimeout(function(){
                        window.location.href = BASE + 'etiquetas';
                    },  3000);
                }else if (response.papelera){
                    $('#modalGuardarET').modal('hide');//ocultamos el modal de guardar
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioET')[0].reset();
                    
                }else{
                    $('#modalGuardarET').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje, 'error');
                    $('#formularioET')[0].reset();
                    
                }
            },
            error: function() {
                $('#modalGuardarET').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor');
            },
            complete: function(){
                $('#modalGuardarET').modal('hide');//ocultamos el modal de guardar
                $('#formularioET')[0].reset();
            } 
        });
    });

    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $('.btn-borrarET').click(function(){
        idABorrar = $(this).data('id');
        $('#modalBorrado').modal('show');
    });
    $('#botonBorradosAtributos').click(function(){
        $.ajax({
            url: BASE + 'etiquetas/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                $('#modalBorrado').modal('hide');
                mensajes('Dato borrado exitosamente');
                setTimeout(function(){
                    location.reload();
                }, 3000);
            },
            error: function(){
                $('#modalBorrado').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor');         
            },
            complete: function(){
                
            } 
        });
    });
    //obtenemos el selector del boton recuperar de vista papelera
    $('.btn-recuperarET').click(function(){

        idARecuperar = $(this).data('id');
        
        $.ajax({
            url: BASE + 'etiquetas/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes("Etiqueta Recuperada: " + response.mensaje);                    
                    setTimeout(function(){
                        window.location.href = BASE + 'etiquetas';
                    },  3000);
                }else{
                    mensajes('Aviso: ' + response.mensaje,'warning');
                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                }
                    
            },
            error: function(){
                mensajes('Error en el servidor'); 
        
            },
            complete: function(){
                
            } 
        });
    });

    //Este si va hacer un update
    $('#actualizarETQ').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        idActualizar = $(this).data('id');
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
        } 
        $.ajax({
            url: BASE + 'etiquetas/actualizar/' + idActualizar,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes("Etiqueta: " + response.mensaje);
                    setTimeout(function(){
                        window.location.href = BASE + 'etiquetas';
                    },  3000);
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje,'error');
                }
            },
            error: function() {
                mensajes('Error en el servidor','error');
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
        }, 4000);}
});