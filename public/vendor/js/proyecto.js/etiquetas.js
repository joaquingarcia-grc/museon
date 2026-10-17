$(document).ready(function() {
    
    let idABorrar = null;
    let idARecuperar = null;
    let idActualizar = null;
    rellenarTabla();
    rellenarTabla2();
    
//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla(){
        $.ajax({
            url: BASE + 'etiquetas/api',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteETQ = " ";
                response.etiquetas.forEach(etiqueta => {
                    let botones = '';

                    if (permiso_ETQ.includes('b')) {
                        botones += `<button type="submit" class="btn btn-danger" id="btnETBorrar" data-id="${etiqueta.id}">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>`;
                    }
                    if (permiso_ETQ.includes('m')) {
                                botones += `<a class='btn btn-primary' href='${BASE}etiquetas/editar/${etiqueta.id}'>
                                                <i class='bi bi-pencil-square'></i>
                                            </a>`;
                    }
                    conteETQ += `<tr>
                        <td class='text-center'>${etiqueta.id}</td>
                        <td class='text-center'>${etiqueta.denominacion}</td>
                        <td class='text-center'>${etiqueta.fecha_alta}</td>
                        <td class='text-center'>${botones}</td>
                    </tr>`;
                });
                $('#bodyTablaETQ').html(conteETQ);

                // ACÁ va el bloque de layout
                $('#tablaETQ').DataTable({
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
                                    window.location.href = BASE + 'etiquetas/papelera';
                                }}]
                        }
                    },
                    order: [[0, 'desc']]
                });

                $("#buscador").on('keyup', function(){
                    $('#tablaETQ').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//
    //esta funcion rellena la tabla de atributos
    function rellenarTabla2(){
        $.ajax({
            url: BASE + 'etiquetas/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteETQ = " ";
                response.etiquetas.forEach(etiqueta => {
                    let boton = '';
                    if (permiso_ETQ.includes('b')) {
                        boton +=`<button type="submit" class='btn btn-info' 
                                    data-id="${etiqueta.id}" id="btnRecuperarETQ">
                                    Resuperar
                                    <i class="bi bi-recycle"></i>
                                </button>`;
                    }
                    conteETQ += `<tr>
                        <td class='text-center'>${etiqueta.id}</td>
                        <td class='text-center'>${etiqueta.denominacion}</td>
                        <td class='text-center'>${etiqueta.fecha_baja}</td>
                        <td class='text-center'>${boton}</td>
                    </tr>`;
                });
                $('#bodyBrTablaETQ').html(conteETQ);

                // ACÁ va el bloque de layout
                $('#tablaETQBr').DataTable({
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
                    $('#tablaETQBr').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor','error');
            }
        });
    };

//------------------------------------------------------------------------------------------------//  

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
    $(document).on('click','#btnETBorrar',function(){
        idABorrar = $(this).data('id');
        $('#modalBorradoET').modal('show');
    });
    $('#btnBorrarET').click(function(){
        $.ajax({
            url: BASE + 'etiquetas/borrar/' + idABorrar,
            method: 'POST',
            success: function(response){
                if(response.exito){
                    $('#modalBorradoET').modal('hide');
                    mensajes(response.mensaje);
                    $('#tablaETQ').DataTable().destroy();
                    rellenarTabla();
                }else{
                    $('#modalBorradoET').modal('hide');
                    mensajes(response.mensaje, 'warning');
                    $('#tablaETQ').DataTable().destroy();
                    rellenarTabla();
                }
            },
            error: function(){
                $('#modalBorradoET').modal('hide');//ocultamos el modal de guardar
                mensajes('Error en el servidor');         
            },
            complete: function(){
                
            } 
        });
    });
    //obtenemos el selector del boton recuperar de vista papelera
    $(document).on('click','#btnRecuperarETQ',function(){

        idARecuperar = $(this).data('id');
        
        $.ajax({
            url: BASE + 'etiquetas/recuperacion/' + idARecuperar,
            method: 'POST',
            success: function(response){
                if (response.exito){
                    mensajes("Etiqueta Recuperada: " + response.mensaje);                    
                    $('#tablaETQBr').DataTable().destroy();
                    rellenarTabla2();
                }else{
                    mensajes('Aviso: ' + response.mensaje,'warning');
                    $('#tablaETQBr').DataTable().destroy();
                    rellenarTabla2();
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
                    },  4010);
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