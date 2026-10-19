$(document).ready(function(){
//------------------------------------------------------------------------------------------------//
    //funcion para poder ingresar en la pagina de logueo
    $('#formularioIngreso').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        const dato = {
            denominacion : $('#denominacion').val(),
            password : $('#password').val(),
        }
        $.ajax({
            url: BASE + 'login/validacion',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes2(response.mensaje);//responde mensaje desde el servidor
                    $('#formularioIngreso')[0].reset();//limpia formulario
                    setTimeout(function(){
                        window.location.href = (response.redirigir);
                    },  2005);
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(){//si existiese un error en el servidor podria verse un mensaje de error
                $('#formularioIngreso')[0].reset();
                mensajes('Error en el servidor','error'); 
            },
            complete: function(){//siempre limpia por defecto en carga
                $('#formularioIngreso')[0].reset();
            } 
        });
    });

//------------------------------------------------------------------------------------------------//
    function mensajes2(mensaje, tipo = 'success'){
        const toast = document.getElementById('toats');
        toast.innerHTML = `${mensaje}`;
        // limpiamos clases de tipo anteriores
        toast.classList.remove('success', 'error', 'warning');
        // agregamos la clase según el tipo
        toast.classList.add(tipo);
        toast.classList.add('show');
        setTimeout(() => {
          toast.classList.remove('show');
        }, 2000);}

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