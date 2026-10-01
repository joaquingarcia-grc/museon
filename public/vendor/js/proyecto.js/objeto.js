$(document).ready(function(){

    let idActualizar = null;
    let idABorrar = null;
    rellenarTabla();
//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla(){
        $.ajax({
            url: BASE + 'objetos/api',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteATB = " ";
                response.objetos.forEach(objeto => {
                    conteATB += `<tr>
                        <td class='text-center'>${objeto.id}</td>
                        <td class='text-center'>${objeto.codigo}</td>
                        <td class='text-center'>${objeto.denominacion}</td>
                        <td class='text-center'>${objeto.descripcion}</td>
                        <td class='text-center'>${objeto.cargado_por}</td>
                        <td class='text-center'>${objeto.fecha_alta}</td>
                        <td class='text-center'>
                            <button type="submit" class="btn btn-danger" id="btnBorrarOBJ" data-id="${objeto.id}">
                                <i class='bi bi-trash2-fill'></i>
                            </button>
                            <a class='btn btn-primary' href='${BASE}objetos/editar/${objeto.id}'>
                                <i class='bi bi-pencil-square'></i>
                            </a>
                            <a class='btn btn-info' href='${BASE}objetos/ver/${objeto.id}'>
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>`;//objetos/ver/<?php echo $objeto["id"];
                });
                $('#bodyTablaOBJ').html(conteATB);

                // ACÁ va el bloque de layout
                $('#tablaOBJ').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/2.1.7/i18n/es-ES.json'
                    },
                    layout: {
                        topStart: null,
                        topEnd: null,
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                        bottom1Start: {
                            buttons: ['excel', 'pdf', 'copy', 'csv', 'print']
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaOBJ').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };
//------------------------------------------------------------------------------------------------------------------------------//
        
    $('#formularioOBJ').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        $('#modalGuardarOBJ').modal('show');
    });
    //los datos seran enviado una ves obtengamos la accion desde el boton del modal
    $('#btnGuardarOBJ').on('click', function(){
        const dato = $('#formularioOBJ').serialize();  
        $.ajax({
            url: BASE + 'objetos/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    $('#modalGuardarOBJ').modal('hide');//ocultamos el modal de guardar
                    mensajes("Pieza guardado con ID: " + response.id + response.mensaje);
                    $('#formularioOBJ')[0].reset();
                    setTimeout(function(){
                        window.location.href = BASE + 'objetos';
                    },  4010);
                }else if (response.papelera){
                    $('#modalGuardarOBJ').modal('hide');//ocultamos el modal de guardar
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioOBJ')[0].reset();
                }else{
                    $('#modalGuardarOBJ').modal('hide');//ocultamos el modal de guardar
                    mensajes('Error: ' + response.mensaje, 'error');
                    $('#formularioOBJ')[0].reset();
                }
            },
            error: function() {
                $('#modalGuardarOBJ').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor', 'error');
                $('#formularioOBJ')[0].reset();
            },
            complete: function(){
                $('#modalGuardarOBJ').modal('hide');//ocultamos el modal de guardar
                $('#formularioOBJ')[0].reset();
            } 
        });
    });
//---------------------------------------------------------------------------------------------------//
    //continuamos con el modal de borrar el cual lo obtenemos por el selector de clases 
    $(document).on('click', '#btnBorrarOBJ', function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoOBJ').modal('show');
    });
    $('#botonBorradoOBJ').click(function(){
        //Controlador borrar
        $.ajax({
            url: BASE + 'objetos/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                $('#modalBorradoOBJ').modal('hide');
                mensajes('Objeto borrado exitosamente');
                $('#tablaOBJ').DataTable().destroy();
                rellenarTabla();
            },
            error: function(){
                $('#modalBorradoOBJ').modal('hide');//ocultamos el modal de borrado
                mensajes('Error en el servidor', 'error');         
            },
            complete: function(){  
            } 
        });
    });
//---------------------------------------------------------------------------------------------------------------//
    //Este si va hacer un update
    $('#OBJactualizar').on('submit',function(e){
        e.preventDefault();//accionamos un evento por defecto el cual detiene las acciones del formulario
        // en vez de guardar, solo mostramos el modal
        idActualizar = $(this).data('id');
        const dato = $(this).serialize();
        //controlador actualizar 
        $.ajax({
            url: BASE + 'objetos/actualizar/' + idActualizar,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes("Objeto: " + response.mensaje);
                    setTimeout(function(){
                        window.location.href = BASE + 'objetos';
                    },  4010);
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
            },
            complete: function(){

            } 
        });
    });

//---------------------------------------------------------------------------------------------------------------// 
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
//---------------------------------------------------------------------------------------------------------------//
});