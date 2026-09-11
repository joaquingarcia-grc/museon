$(document).ready(function() {
    
    let idABorrar = null;
    let idActualizar = null;

    $('#formularioATB').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardar').modal('show');
    });
    //los datos seran enviado una ves obtengamos la accion desde el boton del modal
    $('#modalGuardado').on('click', function(){
        const dato = {//creamos un objeto con todos nuestros datos
            denominacion: $('#denominacion').val(),
            tipo_dato: $('#tipo_dato').val(),
        }   
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes("Atributo guardado con ID: " + response.id + response.mensaje);
                    $('#formularioATB')[0].reset();
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/atributos';
                    },  3000);
                }else if (response.papelera){
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes('Aviso: ' + response.mensaje);
                    $('#formularioATB')[0].reset();
                    
                }else{
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje);
                    $('#formularioATB')[0].reset();
                    
                }
            },
            error: function() {
                $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor');
            },
            complete: function(){
                $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                $('#formularioATB')[0].reset();
            } 
        });
    });
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
    $('.btn-recuperar').click(function(){

        idActualizar = $(this).data('id');
        
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/recuperacion/' + idActualizar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes("Atributo Recuperado: " + response.mensaje);                    
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/atributos';
                    },  3000);
                }else{
                    mensajes('Aviso: ' + response.mensaje);}
            },
            error: function(){
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