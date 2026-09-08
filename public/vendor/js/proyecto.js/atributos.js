$(document).ready(function() {
    
    var denominacion;
    var tipoDato;
    let idABorrar = null;
    //atraves del selector de id busco el btnGuardar el que se encarga de accionar la siguiente funcion
    $('#btnGuardar').click(function(){
        denominacion = $('#denominacion').val();//por id guardo el las variables locales denominacion el dato
        tipoDato = $('#tipo_dato').val();       //recuperado del formulario
        if (!denominacion || !tipoDato){//pregunto si no estan vacios
            mensaje('Fltan comlpetar Campos')//llamo a la funcion mostrar error mas un parametro
        }else{//si no acciona el modal de guardar
            $('#modalGuardar').modal('show');
        }
    });
    //desde el modal guardar recupero la accion del boton modalGuardado
    $('#modalGuardado').click(function(){
        $.ajax({//para realizar la siguiente peticion post en ajax
            url:  "http://localhost/ci.03/public/atributos/insertar",
            method: 'POST',
            data: {
                "denominacion": denominacion,
                "tipo_dato": tipoDato
            },
            success: function(response){
                $('#modalGuardar').modal('hide');//ocultamos el modal de guardar
                mensaje('Datos Guardados exitosamente');//mostramos el mensaje de exito
            },
            error: function(){
            console.log("Error, Esta mal");},
            complete: function(){
                limpiarCampos();//como complemento limpiamos los campos
            } 
        });
    });
    function mensaje(mensaje){//funcion del mensaje que aparece una vez hacemos una insercion
      const toast = document.getElementById('toats');//se podria utilizar una sola funcion para ejecutar el 
      toast.innerHTML = `${mensaje}`;                     //mensaje pero todavia no se que hacer  
      toast.classList.add('show');

      setTimeout(() => {
        toast.classList.remove('show');
        },2000
    );}    
    function limpiarCampos() {
        $('#denominacion').val('');
        $('#tipo_dato').val('');
    }

    $('#btnBorrarAtributo').click(function(){
        idABorrar = $(this).data('id');
        $('#modalBorrado').modal('show')
    });
    $('#botonBorradosAtributos').click(function(){
        $.ajax({
            url: 'http://localhost/ci.03/public/atributos/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                $('#modalBorrado').modal('hide');
                 mensaje('Datos borrados exitosamente');
                setTimeout(function(){
                    location.reload();
                }, 2005);
            },
            error: function(){
                alert('Error al borrar');
                
            },
            complete: function(){
                
            } 
        });
    });
});