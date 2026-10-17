$(document).ready(function(){

    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null; 
    
    rellenarTabla();
    rellenarTabla2();

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
                    let botones = '';

                    if (permiso_OBJ.includes('b')) {
                        botones += `<button type="button" class="btn btn-danger btn-sm me-1" id='btnBorrarOBJ' data-id="${objeto.id}">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>`;
                    }

                    if (permiso_OBJ.includes('m')) {
                                botones += `<a class='btn btn-primary btn-sm me-1' href='${BASE}objetos/editar/${objeto.id}'>
                                                <i class='bi bi-pencil-square'></i>
                                            </a>`;
                    }

                    if (permiso_OBJ.includes('l')) {
                                botones += `<a class='btn btn-info btn-sm' href='${BASE}objetos/ver/${objeto.id}'>
                                                <i class="bi bi-eye"></i>
                                            </a>`;
                    }
                    conteATB += `<tr>
                        <td class='text-center'>${objeto.id}</td>
                        <td class='text-center'>${objeto.codigo}</td>
                        <td class='text-center'>${objeto.denominacion}</td>
                        <td class='text-center'>${objeto.descripcion}</td>
                        <td class='text-center'>${objeto.cargado_por}</td>
                        <td class='text-center'>${objeto.fecha_alta}</td>
                        <td class='text-center text-nowrap'>${botones}</td>`;//objetos/ver/<?php echo $objeto["id"];
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
                            buttons: [ 
                                {   
                                    extend: 'excelHtml5',
                                    text:'excel',
                                    titleAttr:'formato exel'
                                },{ 
                                    extend: 'pdfHtml5',
                                    text:'pdf',
                                    titleAttr:'formato pdf'
                                },{
                                    extend: 'copyHtml5',
                                    text:'copiar',
                                    titleAttr:'formato texto plano'
                                },{
                                    extend: 'csvHtml5',
                                    text:'csv',
                                    titleAttr:'formato planilla'
                                },{
                                    extend: 'print',
                                    text: 'imprimir <i class="bi bi-printer-fill"></i>',
                                    titleAttr: 'imprimir tabla'
                                },{   
                                text: 'Papelera <i class="bi bi-trash2-fill"></i>',
                                titleAttr: 'Papelera',
                                className: 'btn btn-outline-secondary',
                                action: function () {
                                    window.location.href = BASE + 'objetos/papelera';
                                }}]
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
        const dato = $('#formularioOBJ').serialize();  
        $.ajax({
            url: BASE + 'objetos/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    mensajes("Pieza guardado con ID: " + response.id + response.mensaje);
                    $('#formularioOBJ')[0].reset();
                    setTimeout(function(){
                        window.location.href = BASE + 'objetos';
                    },  3010);
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#formularioOBJ')[0].reset();
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                    $('#formularioOBJ')[0].reset();
                }
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
                $('#formularioOBJ')[0].reset();
            },
            complete: function(){
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
                if(response.exito){
                    $('#modalBorradoOBJ').modal('hide');
                    mensajes(response.mensaje);
                    $('#tablaOBJ').DataTable().destroy();
                    rellenarTabla();
                }else{
                    $('#modalBorradoOBJ').modal('hide');
                    mensajes(response.mensaje, 'warning');
                    $('#tablaOBJ').DataTable().destroy();
                    rellenarTabla();
                }
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
                    },  3010);
                }else{
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

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla2(){
        $.ajax({
            url: BASE + 'objetos/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteOBj = " ";
                response.objetos.forEach(objeto => {
                    let boton = '';
                    if (permiso_OBJ.includes('b')) {
                        boton +=    `<button type="submit" class='btn btn-info' 
                                        data-id="${objeto.id}" id="btnRecuperarOBJ">
                                        Resuperar
                                        <i class="bi bi-recycle"></i> 
                                    </button>`;
                    }
                    conteOBj += `<tr>
                        <td class='text-center'>${objeto.id}</td>
                        <td class='text-center'>${objeto.denominacion}</td>
                        <td class='text-center'>${objeto.cargado_por}</td>
                        <td class='text-center'>${objeto.fecha_baja}</td>
                        <td class='text-center'>${boton}</td>
                    </tr>`;
                });
                $('#bodyBrTablaOBJ').html(conteOBj);

                // ACÁ va el bloque de layout
                $('#tablaOBJBr').DataTable({
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
                                    text:'excel',
                                    titleAttr:'formato exel'
                                },{ 
                                    extend: 'pdfHtml5',
                                    text:'pdf',
                                    titleAttr:'formato pdf'
                                },{
                                    extend: 'copyHtml5',
                                    text:'copiar',
                                    titleAttr:'formato texto plano'
                                },{
                                    extend: 'csvHtml5',
                                    text:'csv',
                                    titleAttr:'formato planilla'
                                },{
                                    extend: 'print',
                                    text: 'imprimir <i class="bi bi-printer-fill"></i>',
                                    titleAttr: 'imprimir tabla'
                                }]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaOBJBr').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//
    //obtenemos el selector del boton recuperar de vista papelera
    $(document).on('click',('#btnRecuperarOBJ'),function(){
    //este id a recuperar nos permiter almacenar el id obtenido desde la base de datos para posteriormente ser enviado al controlador
        idARecuperar = $(this).data('id'); 
        //Controlador de recuperacion
        $.ajax({
            url: BASE + 'objetos/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if(response.exito){
                    mensajes('Objeto ' + response.mensaje);
                    $('#tablaOBJBr').DataTable().destroy();
                    rellenarTabla2();
                }else{
                    mensajes(response.mensaje);
                    $('#tablaOBJBr').DataTable().destroy();
                    rellenarTabla2(); 
                }   
            },
            error: function(){
                mensajes('Error en el servidor','error'); 
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
        }, 3000);}
//---------------------------------------------------------------------------------------------------------------//
});