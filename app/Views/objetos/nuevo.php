<div class="container-fluid">
    <div class="row align-items-center">
        <div class="text-center">
            <h2>
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class="text-right">
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Objetos <i class="bi bi-box"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo base_url();?>objetos/insertar" method="post">
                        <!-- Datos principales del objeto -->
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label for="codigo" class="form-label fw-bold">Código del Objeto</label>
                                <input class="form-control" type="text" id="codigo" name="codigo" placeholder="Ej: A03, B54..." required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="denominacion" class="form-label fw-bold">Denominación</label>
                                <input class="form-control" type="text" id="denominacion" name="denominacion" placeholder="Nombre del objeto">
                            </div>
                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                <input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción breve">
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-4">
                            <!-- ETIQUETAS (Autocompletado múltiple dinámico) -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Buscar y Agregar Etiquetas</label>
                                <div class="position-relative"> <!-- Cambié position por position-relative -->
                                    <!-- Input Group de Bootstrap para unir el buscador y el botón de la lupa -->
                                    <div class="input-group">
                                        <input type="text" id="inputBuscarEtiqueta" class="form-control" placeholder="Escriba para buscar etiqueta..." autocomplete="off">
                                        <!-- Botón de lupa que abre el Modal -->
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalEtiquetas" title="Ver todas las etiquetas">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                    <!-- Lista de sugerencias-->
                                    <ul id="listaSugerenciasEtiquetas" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; margin-top: 2px;"></ul>
                                </div>
                                <div id="contenedorEtiquetas" class="row g-2 mt-3"></div> <!-- Agregado row y g-2 -->
                            </div>
                            
                            <!-- ATRIBUTOS (Autocompletado con valor dinámico) -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Buscar y Agregar Atributos</label>
                                <div class="position-relative">
                                    <input type="text" id="inputBuscarAtributo" class="form-control" placeholder="Escriba para buscar atributo..." autocomplete="off">
                                    <ul id="listaSugerenciasAtributos" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></ul>
                                </div>
                                <div id="contenedorAtributos" class="row g-2 mt-3"></div> <!-- Agregado row y g-2 -->
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-outline-success" type="submit">Guardar</button>
                            <a href="<?php echo base_url();?>objetos/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                        <!-- MODAL DE ETIQUETAs -->
                        <div class="modal fade" id="modalEtiquetas" tabindex="-1" aria-labelledby="modalEtiquetasLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEtiquetasLabel">Todas las Etiquetas Disponibles</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body ">
                                        <!-- Aquí se inyectarán todas las etiquetas disponibles desde JavaScript -->
                                        <div class="list-group" id="listaTodasEtiquetasModal">
                                            <!-- JS insertará las opciones aquí -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// SCRIPT DE AUTOCOMPLETADO
document.addEventListener('DOMContentLoaded', () => {

    const datos = {
        // Convierte el array de PHP $etiquetas a un array nativo de JS (o array vacío si no existe)
        etiqueta: <?php echo json_encode($etiquetas ?? []); ?>,
        // Convierte el array de PHP $atributos a un array nativo de JS (o array vacío si no existe)
        atributo: <?php echo json_encode($atributos ?? []); ?>
    };

    // --- NUEVO: Objeto global para almacenar los seleccionados ---
    // Se saca de la función principal para que el MODAL también sepa qué etiquetas ya se eligieron
    const seleccionadosGlobal = {
        Etiqueta: new Set(),
        Atributo: new Set()
    };
    // -------------------------------------------------------------

    // 2. Función principal que configura la búsqueda y el autocompletado reutilizable
    function setupAutocompletar(tipo, requiereValor) {
        // Captura el input de texto según el tipo ('Etiqueta' o 'Atributo')
        const input = document.getElementById(`inputBuscar${tipo}`);
        // Captura la lista desplegable de sugerencias según el tipo
        const lista = document.getElementById(`listaSugerencias${tipo}s`);
        // Captura el contenedor donde se van a insertar los elementos elegidos
        const contenedor = document.getElementById(`contenedor${tipo}s`);
        // MODIFICADO: Usa el Set global correspondiente al tipo ('Etiqueta' o 'Atributo') en lugar de crear uno nuevo acá
        const seleccionados = seleccionadosGlobal[tipo];

        // Escucha cada tecla o cambio introducido en el input de búsqueda
        input.addEventListener('input', () => {
            // Convierte lo ingresado a minúsculas y elimina espacios iniciales/finales
            const query = input.value.toLowerCase().trim();
            // Limpia el contenido actual de la lista de sugerencias
            lista.innerHTML = '';
            // Si el input está vacío, oculta la lista flotante y detiene la ejecución
            if (!query) return lista.style.display = 'none';

            // Filtra los datos y luego los ordena alfabéticamente por la denominación
            const coincidencias = datos[tipo.toLowerCase()].filter(item =>
                item.denominacion.toLowerCase().includes(query) && !seleccionados.has(item.id)
            ).sort((a, b) => {
                const nombreA = a.denominacion.toLowerCase();
                const nombreB = b.denominacion.toLowerCase();
                const aEmpieza = nombreA.startsWith(query);
                const bEmpieza = nombreB.startsWith(query);

                // Si 'a' empieza con la letra/texto y 'b' no, 'a' va primero
                if (aEmpieza && !bEmpieza) return -1;
                // Si 'b' empieza con la letra/texto y 'a' no, 'b' va primero
                if (!aEmpieza && bEmpieza) return 1;
                // Si ambos empiezan igual o ambos solo la contienen, se ordenan alfabéticamente
                return a.denominacion.localeCompare(b.denominacion);
            });

            // Muestra u oculta la lista desplegable según si hay resultados o no
            lista.style.display = coincidencias.length ? 'block' : 'none';

            // Recorre cada coincidencia encontrada para generar su opción en el menú
            coincidencias.forEach(item => {
                // Crea el elemento de lista <li>
                const li = document.createElement('li');
                // Aplica clases de estilo de Bootstrap para que sea interactivo
                li.className = 'list-group-item list-group-item-action d-flex justify-content-between pointer';
                // Asigna el texto con el nombre y un icono de check verde
                li.innerHTML = `${item.denominacion} <i class="bi bi-check-circle-fill text-success"></i>`;
                // Evento de clic al seleccionar un ítem de la lista
                li.onclick = () => {
                    // Llama a la función que renderiza el badge en el formulario
                    agregarBadge(item, tipo, requiereValor, contenedor, seleccionados);
                    // Vacía el texto del campo de búsqueda
                    input.value = '';
                    // Oculta la lista desplegable
                    lista.style.display = 'none';
                };
                // Inserta el <li> dentro del <ul> de la lista flotante
                lista.appendChild(li);
            });
        });

        // Escucha clics en cualquier parte de la pantalla
        document.addEventListener('click', (e) => {
            // Si el clic ocurrió fuera del input de búsqueda, oculta la lista
            if (e.target !== input) lista.style.display = 'none';
        });
    }

    // 3. Función encarga de renderizar la etiqueta/atributo seleccionado en el DOM
    function agregarBadge(item, tipo, requiereValor, contenedor, seleccionados) {
        // Agrega el ID del elemento al Set para marcarlo como ya elegido
        seleccionados.add(item.id);
        // Convierte el nombre del tipo a minúsculas ('etiqueta' o 'atributo') para formar los names de PHP
        const prefix = tipo.toLowerCase();
        
        // Crea un contenedor <div> que envuelve la tarjeta del badge
        const div = document.createElement('div');
        div.className = tipo === 'Etiqueta' ? 'col-6' : 'col-12';

        // Si requiere valor (Atributo), genera el HTML del campo de texto; si no, queda vacío
        const campoValor = requiereValor 
            ? `<input type="text" class="form-control form-control-sm" name="${prefix}_valores[${item.id}]" placeholder="Ingresar dato..." required>` 
            : '';

        // Inyecta el contenido HTML dinamizado (Badge + Input Oculto de ID + Campo Valor opcional + Botón Borrar)
        if (tipo === 'Etiqueta') {
            div.innerHTML = `
                <span class="badge bg-primary w-100 p-2 fs-6 d-flex justify-content-between align-items-center text-start text-wrap h-100">
                    ${item.denominacion}
                    <input type="hidden" name="${prefix}_ids[]" value="${item.id}">
                    <button type="button" class="btn-close btn-close-white ms-2 btn-borrar" aria-label="Eliminar"></button>
                </span>
            `;
        } else {
            div.innerHTML = `
                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light w-100">
                    <span class="badge bg-secondary p-2 text-start flex-grow-1">
                        ${item.denominacion}
                    </span>
                    <input type="hidden" name="${prefix}_ids[]" value="${item.id}">
                    ${campoValor}
                    <button type="button" class="btn btn-sm btn-outline-danger btn-borrar"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            `;
        }

        // Asigna el evento al botón de eliminar (cruz)
        div.querySelector('.btn-borrar').onclick = () => {
            // Remueve el elemento HTML visual de la pantalla
            div.remove();
            // Quita el ID del Set para que vuelva a estar disponible en las búsquedas
            seleccionados.delete(item.id);
        };

        // Agrega el nuevo badge/bloque dentro del contenedor correspondiente en el formulario
        contenedor.appendChild(div);

        // Pone el foco automáticamente en el input
        if (requiereValor) {
            // Busca el input de texto recién creado dentro de este div
            const inputGenerado = div.querySelector('input[type="text"]');
            if (inputGenerado) {
                // setTimeout asegura que el DOM haya terminado de dibujar el input antes de enfocarlo
                setTimeout(() => {
                    inputGenerado.focus();
                }, 10);
            }
        }
    }
    
    // 4. Inicializa el comportamiento para Etiquetas (sin solicitar valor adicional)
    setupAutocompletar('Etiqueta', false);
    // Inicia el comportamiento para Atributos (solicitando el campo de texto de valor)
    setupAutocompletar('Atributo', true);

  
    //  NUEVO: LÓGICA DEL MODAL DE ETIQUETAS ---
 
    // Capturamos el modal, la lista donde irán las etiquetas y el contenedor final
    const modalEtiquetas = document.getElementById('modalEtiquetas');
    const listaModal = document.getElementById('listaTodasEtiquetasModal');
    const contenedorEtiquetas = document.getElementById('contenedorEtiquetas');

    if (modalEtiquetas) {
        // Evento nativo de Bootstrap que se dispara justo al abrir el modal
        modalEtiquetas.addEventListener('show.bs.modal', () => {
            // Limpiamos el contenido previo cada vez que se abre el modal
            listaModal.innerHTML = '';
            let hayDisponibles = false;

            // Recorremos el array original de etiquetas que llegó desde PHP
            datos.etiqueta.forEach(item => {
                // Verificamos en el Set global si la etiqueta NO ha sido seleccionada aún
                if (!seleccionadosGlobal['Etiqueta'].has(item.id)) {
                    hayDisponibles = true;
                    // Creamos un botón <button> con diseño de lista de Bootstrap
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    // Le ponemos el nombre de la etiqueta y un ícono de "más"
                    btn.innerHTML = `${item.denominacion} <i class="bi bi-plus-circle text-success"></i>`;
                    // Qué pasa cuando el usuario hace clic en una etiqueta del Modal:
                    btn.onclick = () => {
                        // 1. Usamos la función existente para agregarla al formulario
                        agregarBadge(item, 'Etiqueta', false, contenedorEtiquetas, seleccionadosGlobal['Etiqueta']);
                        // 2. Quitamos esta opción visualmente de la lista del modal
                        btn.remove();
                        // 3. Si era la última etiqueta disponible, mostramos un mensaje
                        if (listaModal.children.length === 0) {
                            listaModal.innerHTML = '<div class="text-muted text-center p-3">No hay más etiquetas disponibles.</div>';
                        }
                    };
                    // Inyectamos el botón recién creado en el HTML del modal
                    listaModal.appendChild(btn);
                }
            });

            // Si al recorrer todo vimos que ya están todas agregadas, mostramos mensaje
            if (!hayDisponibles) {
                listaModal.innerHTML = '<div class="text-muted text-center p-3">Todas las etiquetas ya han sido agregadas.</div>';
            }
        });
    }
});
</script>