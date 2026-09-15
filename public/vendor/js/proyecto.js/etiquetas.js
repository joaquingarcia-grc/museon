$(document).ready(function() {
    
    let idABorrar = null;
    let idARecuperar = null;
    let idActualizar = null;

    $('#formularioET').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardar').modal('show');
    });
    //los datos seran enviado una ves obtengamos la accion desde el boton del modal
    $('#modalGuardado').on('click', function(){
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
        }   
        $.ajax({
            url: 'http://localhost/ci.03/public/etiquetas/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes("Etiqueta guardado con ID: " + response.id + response.mensaje);
                    $('#formularioET')[0].reset();
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/etiquetas';
                    },  3000);
                }else if (response.papelera){
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes('Aviso: ' + response.mensaje);
                    $('#formularioET')[0].reset();
                    
                }else{
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje);
                    $('#formularioET')[0].reset();
                    
                }
            },
            error: function() {
                $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor');
            },
            complete: function(){
                $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                $('#formularioET')[0].reset();
            } 
        });
    });

    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $('.btn-borrar').click(function(){
        idABorrar = $(this).data('id');
        $('#modalBorrado').modal('show');
    });
    $('#botonBorradosAtributos').click(function(){
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/borrar/' + idABorrar,
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
    $('.btn-recuperar').click(function(){

        idARecuperar = $(this).data('id');
        
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes("Atributo Recuperado: " + response.mensaje);                    
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/atributos';
                    },  3000);
                }else{
                    mensajes('Aviso: ' + response.mensaje);
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
    $('#ATBactualizar').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        idActualizar = $(this).data('id');
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
            tipo_dato: $('#tipo_dato').val(),
        } 
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/actualizar/' + idActualizar,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes("Atributo: " + response.mensaje);
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/atributos';
                    },  3000);
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje);
                }else{
                    mensajes('Error: ' + response.mensaje);
                }
            },
            error: function() {
                mensajes('Error en el servidor');
            },
            complete: function(){

            } 
        });
    });

    function mensajes(mensaje){//funcion del mensaje que aparece una vez hacemos una insercion
        const toast = document.getElementById('toats');//se podria utilizar una sola funcion para ejecutar el 
        toast.innerHTML = `${mensaje}`;                     //mensaje pero todavia no se que hacer  
        toast.classList.add('show');
        setTimeout(() => {
          toast.classList.remove('show');
          },4000
    );}
});