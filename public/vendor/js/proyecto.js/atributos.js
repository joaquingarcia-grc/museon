$(document).ready(function() {
    
    let idABorrar = null;
    let idARecuperar = null;
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
            url: BASE + 'atributos/insertar',
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
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioATB')[0].reset();
                    
                }else{
                    $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje, 'error');
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

    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $('.btn-borrar').click(function(){
        idABorrar = $(this).data('id');
        $('#modalBorrado').modal('show');
    });
    $('#botonBorradosAtributos').click(function(){
        $.ajax({
            url: BASE + 'atributos/borrar/' + idABorrar,
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
                mensajes('Error en el servidor','error');         
            },
            complete: function(){
                
            } 
        });
    });
    //obtenemos el selector del boton recuperar de vista papelera
    $('.btn-recuperar').click(function(){

        idARecuperar = $(this).data('id');
        
        $.ajax({
            url: BASE + 'atributos/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes("Atributo Recuperado: " + response.mensaje);                    
                    setTimeout(function(){
                        window.location.href = 'http://localhost/ci.03/public/atributos';
                    },  3000);
                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    setTimeout(function(){
                        location.reload();
                    }, 3000);
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
        } 
        $.ajax({
            url: BASE + 'atributos/actualizar/' + idActualizar,
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
        }, 4000);}
});