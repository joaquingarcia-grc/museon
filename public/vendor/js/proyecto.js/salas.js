$(document).ready(function(){
    // el museo es la sala con id 1, la raiz de todo
    const ID_MUSEO = 1;
    // guardo cual sala esta seleccionada
    let salaActual = null;
    let nombreSalaActual = '';
    // guardo lo ultimo que trajo el controlador, lo uso para el modal de editar y para la hoja de ruta
    let datosSalaActual = null;
    let hijasActuales = [];
    // los objetos que ya estan en la sala seleccionada
    let objetosActuales = [];
    // los objetos sin ubicacion que se muestran en el modal de agregar, y el que se eligio
    let objetosLibres = [];
    let objetoElegido = null;
    // la sala que estoy editando, la que estoy por borrar y la que recupero
    let idActualizar = null;
    let idABorrar = null;
    let idARecuperar = null;
    rellenarTablaPapelera();
    // muestra un mensaje en pantalla, tipo: success, error, warning
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
    // pasa un texto a html seguro para meterlo en la hoja de ruta
    function escaparHtml(texto){
        if(texto == null){
            return '';
        }
        return $('<div></div>').text(texto).html();
    }
    // arma la ruta completa de una sala subiendo por el arbol hasta el museo, ej: Museo > Sala 2 > Vitrina 3
    function armarRutaCompleta(idSala){
        const partes = [];
        let divSala = $('#sala_' + idSala);
        // si la sala no esta en el arbol devuelvo vacio
        if(divSala.length == 0){
            return '';
        }
        // voy subiendo de sala en sala, guardando el nombre de cada una
        while(divSala.length > 0){
            partes.push(divSala.find('span').text());
            const ul = divSala.parent().parent();
            // si la lista es la de arriba de todo ya llegue al museo
            if(ul.attr('id') == 'arbolSalas'){
                break;
            }
            // el div del padre es el que viene justo antes de la lista
            divSala = ul.prev();
        }
        // las junte de abajo para arriba, las doy vuelta para que quede desde el museo
        partes.reverse();
        return partes.join(' > ');
    }
    // abre o cierra una sala, recibe el div de la sala y true para abrir o false para cerrar
    function abrirCerrarSala(divSala, abrir){
        // el ul con las hijas es el que viene justo despues del div de la sala
        const hijas = divSala.next();
        const caret = divSala.find('i').first();
        const puerta = divSala.find('i').last();
        if(abrir == true){
            hijas.slideDown(150);
            caret.removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
            puerta.removeClass('bi-door-closed').addClass('bi-door-open');
        } else {
            hijas.slideUp(150);
            caret.removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
            puerta.removeClass('bi-door-open').addClass('bi-door-closed');
        }
    }
    // agrega la sala nueva al arbol de la izquierda sin recargar la pagina
    function agregarSalaAlArbol(idNueva, denominacion, idPadre){
        const divPadre = $('#sala_' + idPadre);
        // si el padre no esta en el arbol no hago nada
        if(divPadre.length == 0){
            return;
        }
        const li = $('<li></li>');
        const divNueva = $('<div class="d-flex align-items-center py-1" style="cursor:pointer;"></div>');
        divNueva.attr('id', 'sala_' + idNueva);
        divNueva.append('<i class="bi bi-door-closed me-2"></i>');
        // uso .text() para que no se interprete como html
        divNueva.append($('<span></span>').text(denominacion));
        li.append(divNueva);
        if(divPadre.next().is('ul')){
            // el padre ya tenia hijas, la sumo a su lista
            divPadre.next().append(li);
        } else {
            // el padre no tenia hijas: le pongo el triangulito y le creo su lista
            divPadre.prepend('<i class="bi bi-caret-right-fill me-2"></i>');
            const ulNuevo = $('<ul class="list-unstyled ms-4"></ul>');
            ulNuevo.hide();
            ulNuevo.append(li);
            divPadre.after(ulNuevo);
        }
        // dejo el padre abierto para que se vea la nueva
        abrirCerrarSala(divPadre, true);
    }
    // le cambia el nombre a una sala que ya esta en el arbol
    function cambiarNombreEnArbol(idSala, denominacion){
        $('#sala_' + idSala).find('span').text(denominacion);
    }
    // saca una sala del arbol, si el padre se queda sin hijas le saco el triangulito y la lista
    function sacarSalaDelArbol(idSala){
        const divSala = $('#sala_' + idSala);
        if(divSala.length == 0){
            return;
        }
        const li = divSala.parent();
        const ul = li.parent();
        li.remove();
        // si la lista es la de arriba de todo no toco nada mas
        if(ul.attr('id') == 'arbolSalas'){
            return;
        }
        if(ul.children().length == 0){
            const divPadre = ul.prev();
            ul.remove();
            // el primer icono es el triangulito, lo saco y la puerta queda cerrada
            divPadre.find('i').first().remove();
            divPadre.find('i').first().removeClass('bi-door-open').addClass('bi-door-closed');
        }
    }
    // arma la lista de lo que hay adentro de la sala en la columna derecha, con las descripciones y los objetos
    function armarDetalle(sala, hijas, objetos){
        const detalle = $('#detalleSala');
        detalle.empty();
        // en el museo el boton dice nueva sala, en las demas nueva ubicacion
        let textoBoton = '';
        if(salaActual == ID_MUSEO){
            textoBoton = 'Nueva sala';
        } else {
            textoBoton = 'Nueva ubicacion';
        }
        const tarjeta = $('<div class="card shadow"></div>');
        const encabezado = $('<div class="card-header d-flex justify-content-between align-items-center"></div>');
        // uso .text() para que lo que venga de la base no se interprete como html
        encabezado.append($('<span class="fw-bold"></span>').text(nombreSalaActual));
        const botones = $('<div class="d-flex gap-2"></div>');
        botones.append('<button type="button" class="btn btn-sm btn-outline-dark" id="btnImprimirRuta"><i class="bi bi-printer"></i> Hoja de ruta</button>');
        // el boton de nueva sala / ubicacion solo aparece si tiene permiso de alta
        if(permiso_SAL.includes('a')){
            botones.append('<button type="button" class="btn btn-sm btn-outline-success" id="btnAgregarUbicacion">' + textoBoton + ' <i class="bi bi-plus-lg"></i></button>');
        }
        // el boton de agregar objeto no aparece en el museo, porque un objeto tiene que estar en una sala o ubicacion
        if(salaActual != ID_MUSEO){
            if(permiso_SAL.includes('a')){
                botones.append('<button type="button" class="btn btn-sm btn-outline-success" id="btnAgregarObjeto">Agregar objeto <i class="bi bi-box"></i></button>');
            }
        }
        encabezado.append(botones);
        tarjeta.append(encabezado);
        // descripcion de la sala seleccionada, arriba de la lista
        const cuerpo = $('<div class="card-body border-bottom"></div>');
        if(sala != null && sala.descripcion){
            cuerpo.append($('<p class="mb-0"></p>').text(sala.descripcion));
        } else {
            cuerpo.append('<p class="mb-0 text-muted">Esta sala no tiene descripcion</p>');
        }
        tarjeta.append(cuerpo);
        const lista = $('<ul class="list-group list-group-flush"></ul>');
        if(hijas.length == 0){
            lista.append('<li class="list-group-item text-muted">Esta sala no tiene nada adentro todavia</li>');
        } else {
            for(let i = 0; i < hijas.length; i++){
                const item = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                const textos = $('<div></div>');
                textos.append($('<div class="fw-semibold"></div>').text(hijas[i].denominacion));
                // la descripcion de cada hija va abajo del nombre
                if(hijas[i].descripcion){
                    textos.append($('<small class="text-muted"></small>').text(hijas[i].descripcion));
                } else {
                    textos.append('<small class="text-muted">Sin descripcion</small>');
                }
                item.append(textos);
                // lapiz para editar y tachito para borrar, cada uno solo si tiene el permiso
                // el id de la sala va en el data-id del boton
                const accion = $('<div class="d-flex gap-1"></div>');
                if(permiso_SAL.includes('m')){
                    accion.append('<button type="button" class="btn btn-sm btn-outline-primary" id="btnEditarSala" data-id="' + hijas[i].id + '"><i class="bi bi-pencil"></i></button>');
                }
                if(permiso_SAL.includes('b')){
                    accion.append('<button type="button" class="btn btn-sm btn-outline-danger" id="btnBorrarSala" data-id="' + hijas[i].id + '"><i class="bi bi-trash"></i></button>');
                }
                item.append(accion);
                lista.append(item);
            }
        }
        tarjeta.append(lista);
        // lista de objetos de la sala, abajo de las ubicaciones (en el museo no se muestra)
        if(salaActual != ID_MUSEO){
            tarjeta.append('<div class="card-header border-top fw-bold">Objetos</div>');
            const listaObjetos = $('<ul class="list-group list-group-flush"></ul>');
            if(objetos.length == 0){
                listaObjetos.append('<li class="list-group-item text-muted">Esta sala no tiene objetos todavia</li>');
            } else {
                for(let i = 0; i < objetos.length; i++){
                    const itemObjeto = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                    const textosObjeto = $('<div></div>');
                    // icono de caja para distinguirlos de las ubicaciones
                    textosObjeto.append('<i class="bi bi-box me-2"></i>');
                    // uso .text() para que lo que venga de la base no se interprete como html
                    textosObjeto.append($('<span></span>').text(objetos[i].denominacion));
                    itemObjeto.append(textosObjeto);
                    // botoncito para sacar el objeto de la sala (queda sin ubicacion), el id va en el data-id
                    if(permiso_SAL.includes('b')){
                        itemObjeto.append('<button type="button" class="btn btn-sm btn-outline-danger" id="btnQuitarObjeto" data-id="' + objetos[i].id + '" title="Sacar de esta sala"><i class="bi bi-x-lg"></i></button>');
                    }
                    listaObjetos.append(itemObjeto);
                }
            }
            tarjeta.append(listaObjetos);
        }
        detalle.append(tarjeta);
    }
    // le pide al controlador la sala, sus hijas y sus objetos
    function traerHijas(idSala, denominacion){
        salaActual = idSala;
        nombreSalaActual = denominacion;
        $.ajax({
            url: BASE + 'salas/hijas',
            method: 'POST',
            data: { id: idSala },
            dataType: 'json',
            success: function(respuesta) {
                // me guardo lo que vino para el modal de editar y la hoja de ruta
                datosSalaActual = respuesta.sala;
                hijasActuales = respuesta.hijas;
                // los objetos de la sala, si no vinieron dejo la lista vacia
                if(respuesta.objetos != null){
                    objetosActuales = respuesta.objetos;
                } else {
                    objetosActuales = [];
                }
                armarDetalle(respuesta.sala, respuesta.hijas, objetosActuales);
            },
            error: function(error) {
                let texto = 'No se pudo traer el contenido de la sala';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                mensajes(texto, 'error');
            }
        });
    }
    // dibuja en el modal la lista de objetos sin ubicacion, filtrando por lo que se escribio en el buscador
    function dibujarObjetosLibres(filtro){
        const lista = $('#listaObjetosLibres');
        lista.empty();
        const texto = filtro.toLowerCase();
        let cantidad = 0;
        for(let i = 0; i < objetosLibres.length; i++){
            // junto denominacion y codigo para buscar en los dos
            let denominacion = '';
            if(objetosLibres[i].denominacion){
                denominacion = objetosLibres[i].denominacion;
            }
            let codigo = '';
            if(objetosLibres[i].codigo){
                codigo = objetosLibres[i].codigo;
            }
            const buscable = (denominacion + ' ' + codigo).toLowerCase();
            if(buscable.includes(texto)){
                cantidad++;
                const item = $('<button type="button" class="list-group-item list-group-item-action" id="itemObjetoLibre"></button>');
                item.attr('data-id', objetosLibres[i].id);
                item.append($('<div class="fw-semibold"></div>').text(denominacion));
                // el codigo va abajo del nombre si tiene
                if(codigo != ''){
                    item.append($('<small class="text-muted"></small>').text(codigo));
                }
                // si es el que estaba elegido lo dejo marcado
                if(objetoElegido == objetosLibres[i].id){
                    item.addClass('active');
                }
                lista.append(item);
            }
        }
        if(cantidad == 0){
            lista.append('<div class="list-group-item text-muted">No hay objetos sin ubicacion</div>');
        }
    }
    // arma la hoja de ruta de la sala seleccionada en otra ventana y abre la impresion
    function imprimirHojaDeRuta(){
        const ventana = window.open('', '_blank');
        if(ventana == null){
            mensajes('El navegador bloqueo la ventana de impresion', 'warning');
            return;
        }
        const fecha = new Date().toLocaleDateString('es-AR');
        let descripcionSala = '';
        if(datosSalaActual != null && datosSalaActual.descripcion){
            descripcionSala = escaparHtml(datosSalaActual.descripcion);
        } else {
            descripcionSala = 'Sin descripcion';
        }
        // la ruta completa de la sala, desde el museo hasta la sala seleccionada
        const rutaCompleta = armarRutaCompleta(salaActual);
        let html = '';
        html += '<html><head><meta charset="utf-8"><title>Hoja de ruta</title>';
        html += '<style>';
        html += 'body{font-family:Arial,sans-serif; margin:30px; color:#222;}';
        html += 'h1{margin-bottom:0;}';
        html += 'h3{margin-top:5px; color:#555; font-weight:normal;}';
        html += 'h4{margin-bottom:5px;}';
        html += 'ol{padding-left:20px;}';
        html += 'li{margin-bottom:14px;}';
        html += '.desc{color:#555; margin-top:3px;}';
        html += '.ruta{font-weight:bold;}';
        html += '</style></head><body>';
        html += '<h1>Hoja de ruta</h1>';
        html += '<h3>' + escaparHtml(nombreSalaActual) + ' - ' + fecha + '</h3>';
        // la ruta para llegar a la sala
        html += '<p class="ruta">Ubicacion: ' + escaparHtml(rutaCompleta) + '</p>';
        html += '<p>' + descripcionSala + '</p>';
        html += '<hr>';
        if(hijasActuales.length == 0){
            html += '<p>Esta sala no tiene nada adentro todavia</p>';
        } else {
            html += '<ol>';
            for(let i = 0; i < hijasActuales.length; i++){
                let descripcionHija = '';
                if(hijasActuales[i].descripcion){
                    descripcionHija = escaparHtml(hijasActuales[i].descripcion);
                } else {
                    descripcionHija = 'Sin descripcion';
                }
                html += '<li><strong>' + escaparHtml(hijasActuales[i].denominacion) + '</strong>';
                html += '<div class="desc">' + descripcionHija + '</div></li>';
            }
            html += '</ol>';
        }
        // los objetos de la sala (en el museo no se muestran), todos comparten la ruta de arriba
        if(salaActual != ID_MUSEO){
            html += '<h4>Objetos</h4>';
            if(objetosActuales.length == 0){
                html += '<p>Esta sala no tiene objetos todavia</p>';
            } else {
                html += '<ol>';
                for(let i = 0; i < objetosActuales.length; i++){
                    html += '<li><strong>' + escaparHtml(objetosActuales[i].denominacion) + '</strong></li>';
                }
                html += '</ol>';
            }
        }
        html += '</body></html>';
        ventana.document.open();
        ventana.document.write(html);
        ventana.document.close();
        ventana.focus();
        // le doy un toque de tiempo para que cargue antes de imprimir
        setTimeout(function(){
            ventana.print();
        }, 300);
    }
    // esta funcion rellena la tabla de la papelera
    function rellenarTablaPapelera(){
        $.ajax({
            url: BASE + 'salas/api2',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let conteSalas = " ";
                response.salas.forEach(sala => {
                    let descripcion = '';
                    if(sala.descripcion){
                        descripcion = escaparHtml(sala.descripcion);
                    } else {
                        descripcion = 'Sin descripcion';
                    }
                    // el boton de recuperar solo aparece si tiene permiso de borrar
                    let boton = '';
                    if(permiso_SAL.includes('b')){
                        boton = `<button type="submit" class='btn btn-info'
                                        data-id="${sala.id}" id="btnRecuperarSala">
                                        Recuperar
                                        <i class="bi bi-recycle"></i>
                                    </button>`;
                    }
                    conteSalas += `<tr>
                        <td class='text-center'>${sala.id}</td>
                        <td class='text-center'>${escaparHtml(sala.denominacion)}</td>
                        <td class='text-center'>${descripcion}</td>
                        <td class='text-center'>${sala.fecha_baja}</td>
                        <td class='text-center'>${boton}</td>
                    </tr>`;
                });
                $('#bodyBrTablaSalas').html(conteSalas);

                // ACÁ va el bloque de layout
                $('#tablaSalasBr').DataTable({
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
                    $('#tablaSalasBr').DataTable().search(this.value).draw();
                });
            },
            error: function() {
                mensajes('Error en el servidor', 'error');
            }
        });
    };
    // al cargar la pagina dejo todo cerrado
    $('#arbolSalas ul').each(function(){
        $(this).hide();
        abrirCerrarSala($(this).prev(), false);
    });
    // despues abro solo la sala de arriba de todo (el museo) para que se vean sus hijas
    $('#arbolSalas > li > div').each(function(){
        const divRaiz = $(this);
        // si tiene hijas, el que viene despues del div es un ul
        if(divRaiz.next().is('ul')){
            abrirCerrarSala(divRaiz, true);
        }
    });
    // dejo el museo seleccionado para que ya aparezca el boton de nueva sala
    const divMuseo = $('#arbolSalas > li > div').first();
    if(divMuseo.length > 0){
        traerHijas(divMuseo.attr('id').replace('sala_', ''), divMuseo.find('span').text());
    }
    // click en el triangulito, si esta a la derecha abre y si esta abajo cierra
    $('#arbolSalas').on('click', 'i', function(){
        const caret = $(this);
        const divSala = caret.parent();
        if(caret.hasClass('bi-caret-right-fill')){
            abrirCerrarSala(divSala, true);
        } else if(caret.hasClass('bi-caret-down-fill')){
            abrirCerrarSala(divSala, false);
        }
    });
    // click en la fila de una sala, trae lo que hay adentro
    $('#arbolSalas').on('click', 'div', function(e){
        // si toco el triangulito no hago nada aca, eso lo maneja el click de arriba
        if($(e.target).hasClass('bi-caret-right-fill') || $(e.target).hasClass('bi-caret-down-fill')){
            return;
        }
        const idSala = $(this).attr('id').replace('sala_', '');
        const denominacion = $(this).find('span').text();
        traerHijas(idSala, denominacion);
    });
    // boton nueva sala / nueva ubicacion: abre el modal, el titulo dice en cual sala va a quedar
    $('#detalleSala').on('click', '#btnAgregarUbicacion', function(){
        if(salaActual == ID_MUSEO){
            $('#tituloModalSala').text('Nueva sala en ' + nombreSalaActual);
        } else {
            $('#tituloModalSala').text('Nueva ubicacion en ' + nombreSalaActual);
        }
        $('#modalSala').modal('show');
    });
    // boton agregar objeto: trae los objetos sin ubicacion y abre el modal para elegir uno
    $('#detalleSala').on('click', '#btnAgregarObjeto', function(){
        $('#tituloModalAgregarObjeto').text('Agregar objeto a ' + nombreSalaActual);
        objetoElegido = null;
        $('#buscadorObjetos').val('');
        $.ajax({
            url: BASE + 'salas/objetosLibres',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                objetosLibres = response.objetos;
                dibujarObjetosLibres('');
                $('#modalAgregarObjeto').modal('show');
            },
            error: function(error) {
                let texto = 'No se pudieron traer los objetos';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                mensajes(texto, 'error');
            }
        });
    });
    // el buscador del modal filtra la lista mientras se escribe
    $('#buscadorObjetos').on('keyup', function(){
        dibujarObjetosLibres($(this).val());
    });
    // click en un objeto de la lista del modal: queda elegido (el id viene en el data-id)
    $('#listaObjetosLibres').on('click', '#itemObjetoLibre', function(){
        objetoElegido = $(this).data('id');
        // saco la marca a todos y se la pongo solo al que toque
        $('#listaObjetosLibres button').removeClass('active');
        $(this).addClass('active');
    });
    // guardar: le asigno a ese objeto la sala en la que estoy parado
    $('#btnGuardarAsignacion').on('click', function(){
        if(objetoElegido == null){
            mensajes('Elegi un objeto de la lista', 'warning');
            return;
        }
        const dato = {
            id_objeto: objetoElegido,
            id_ubicacion: salaActual
        };
        $.ajax({
            url: BASE + 'salas/asignarObjeto',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes(response.mensaje, 'success');
                    // limpio el modal y lo cierro
                    objetoElegido = null;
                    $('#buscadorObjetos').val('');
                    $('#listaObjetosLibres').empty();
                    $('#modalAgregarObjeto').modal('hide');
                    // vuelvo a traer la sala para que aparezca el objeto
                    if(salaActual != null){
                        traerHijas(salaActual, nombreSalaActual);
                    }
                } else {
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(error) {
                let texto = 'No se pudo agregar el objeto';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                mensajes(texto, 'error');
            }
        });
    });
    // cancelar del modal de agregar objeto: limpia el formulario y cierra el modal
    $('#btnCancelarAsignacion').on('click', function(){
        objetoElegido = null;
        $('#buscadorObjetos').val('');
        $('#listaObjetosLibres').empty();
        $('#modalAgregarObjeto').modal('hide');
    });
    // boton de sacar un objeto de la sala (el id del objeto viene en el data-id del boton)
    $('#detalleSala').on('click', '#btnQuitarObjeto', function(){
        const idObjeto = $(this).data('id');
        $.ajax({
            url: BASE + 'salas/quitarObjeto/' + idObjeto,
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.exito){
                    mensajes(response.mensaje, 'success');
                    // vuelvo a traer la sala para que desaparezca de la lista
                    if(salaActual != null){
                        traerHijas(salaActual, nombreSalaActual);
                    }
                } else {
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(error) {
                let texto = 'No se pudo sacar el objeto';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                mensajes(texto, 'error');
            }
        });
    });
    // lapiz de la lista (el id de la sala viene en el data-id del boton)
    $('#detalleSala').on('click', '#btnEditarSala', function(){
        const idSala = $(this).data('id');
        // busco la sala en lo que ya traje, asi no hace falta otro ajax
        for(let i = 0; i < hijasActuales.length; i++){
            if(hijasActuales[i].id == idSala){
                idEditando = idSala;
                $('#denominacionEditar').val(hijasActuales[i].denominacion);
                $('#descripcionEditar').val(hijasActuales[i].descripcion);
                $('#modalEditarSala').modal('show');
            }
        }
    });
    // tachito de la lista (el id de la sala viene en el data-id del boton)
    $('#detalleSala').on('click', '#btnBorrarSala', function(){
        const idSala = $(this).data('id');
        for(let i = 0; i < hijasActuales.length; i++){
            if(hijasActuales[i].id == idSala){
                idABorrar = idSala;
                $('#textoBorrarSala').text('Seguro que queres borrar "' + hijasActuales[i].denominacion + '"?');
                $('#modalBorrarSala').modal('show');
            }
        }
    });
    // hoja de ruta
    $('#detalleSala').on('click', '#btnImprimirRuta', function(){
        imprimirHojaDeRuta();
    });
    // boton papelera, lleva a la vista de la papelera
    $('#btnPapelera').on('click', function(){
        window.location.href = BASE + 'salas/papelera';
    });
    //obtenemos el selector del boton recuperar de vista papelera
    $(document).on('click', '#btnRecuperarSala', function(){
        idARecuperar = $(this).data('id');
        $.ajax({
            url: BASE + 'salas/recuperacion/' + idARecuperar,
            method: 'POST',
            dataType: 'json',
            success: function(response){
                if (response.exito){
                    mensajes(response.mensaje, 'success');
                    $('#tablaSalasBr').DataTable().destroy();
                    rellenarTablaPapelera();
                }else{
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                    $('#tablaSalasBr').DataTable().destroy();
                    rellenarTablaPapelera();
                }
            },
            error: function(){
                mensajes('Error en el servidor', 'error');
            }
        });
    });
    // guardar sala nueva
    $('#btnGuardarSala').on('click', function(){
        // el id_pertenece es la sala en la que estoy parado
        const dato = {
            denominacion: $('#denominacion').val(),
            descripcion: $('#descripcion').val(),
            id_pertenece: salaActual
        };
        $.ajax({
            url: BASE + 'salas/insertar',
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                mensajes(response.mensaje, 'success');
                // pongo la sala nueva en el arbol, en el padre que devolvio el controlador
                agregarSalaAlArbol(response.id, dato.denominacion, response.id_pertenece);
                // limpio el modal y lo cierro
                $('#denominacion').val('');
                $('#descripcion').val('');
                $('#modalSala').modal('hide');
                // si hay una sala seleccionada vuelvo a traer su lista para que aparezca la nueva
                if(salaActual != null){
                    traerHijas(salaActual, nombreSalaActual);
                }
            },
            error: function(error) {
                let texto = 'No se pudo guardar la sala';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                // si ya existe en la papelera lo aviso como warning, sino es un error
                if(error.responseJSON && error.responseJSON.papelera){
                    mensajes('Aviso: ' + texto, 'warning');
                } else {
                    mensajes(texto, 'error');
                }
            }
        });
    });
    // guardar los cambios de la sala que se edito con el lapiz
    $('#btnActualizarSala').on('click', function(){
        idActualizar = $(this).data('id');
        const dato = {
            denominacion: $('#denominacionEditar').val(),
            descripcion: $('#descripcionEditar').val()
        };
        $.ajax({
            url: BASE + 'salas/actualizar/'+idEditando,
            method: 'POST',
            data: dato,
            dataType: 'json',
            success: function(response) {
                // el controlador contesta con exito true o false
                if (response.exito){
                    mensajes(response.mensaje, 'success');
                    // le cambio el nombre en el arbol
                    cambiarNombreEnArbol(dato.id, dato.denominacion);
                    $('#modalEditarSala').modal('hide');
                    // vuelvo a traer la lista para que aparezca el cambio
                    if(salaActual != null){
                        traerHijas(salaActual, nombreSalaActual);
                    }
                }else if (response.papelera){
                    mensajes('Aviso: ' + response.mensaje, 'warning');
                }else{
                    mensajes('Error: ' + response.mensaje, 'error');
                }
            },
            error: function(error) {
                let texto = 'No se pudo actualizar la sala';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                mensajes(texto, 'error');
            }
        });
    });
    // confirmar el borrado
    $('#btnConfirmarBorrar').on('click', function(){
        const idSala = idABorrar;
        $.ajax({
            url: BASE + 'salas/borrar/'+idABorrar,
            method: 'POST',
            data: { id: idSala },
            dataType: 'json',
            success: function(response) {
                mensajes(response.mensaje, 'success');
                // la saco del arbol y cierro el modal
                sacarSalaDelArbol(idSala);
                $('#modalBorrarSala').modal('hide');
                // vuelvo a traer la lista de la sala en la que estoy
                if(salaActual != null){
                    traerHijas(salaActual, nombreSalaActual);
                }
            },
            error: function(error) {
                let texto = 'No se pudo borrar la sala';
                if(error.responseJSON && error.responseJSON.mensaje){
                    texto = error.responseJSON.mensaje;
                }
                $('#modalBorrarSala').modal('hide');
                mensajes(texto, 'error');
            }
        });
    });
});