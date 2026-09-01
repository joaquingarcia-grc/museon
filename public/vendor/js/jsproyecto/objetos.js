document.addEventListener('DOMContentLoaded', () => {

    // 1. Buscamos el <script id="datosObjeto"> de la vista
    const datosScript = document.getElementById('datosObjeto');
    const datos = datosScript ? JSON.parse(datosScript.textContent) : { etiqueta: [], atributo: [] };

    const seleccionadosGlobal = {
        Etiqueta: new Set(), // acá van a ir guardados los IDs (como String) de etiquetas ya agregadas al formulario
        Atributo: new Set()  // lo mismo pero para atributos
    };

    // 2. Función principal que configura la búsqueda y el autocompletado reutilizable
    function setupAutocompletar(tipo, requiereValor) {
        // Buscamos el input de texto donde el usuario escribe para buscar (ej: inputBuscarEtiqueta)
        const input = document.getElementById(`inputBuscar${tipo}`);
        // Buscamos el <ul> donde vamos a mostrar la lista flotante de sugerencias
        const lista = document.getElementById(`listaSugerencias${tipo}s`);
        // Buscamos el contenedor final donde quedan "pegadas" las etiquetas/atributos ya elegidos
        const contenedor = document.getElementById(`contenedor${tipo}s`);
        // Tomamos el Set correspondiente (Etiqueta o Atributo) del objeto global de arriba
        const seleccionados = seleccionadosGlobal[tipo];

        // Si en una vista no existe el buscador de este tipo, detenemos la ejecución
        if (!input || !lista || !contenedor) return;

        // Cada vez que el usuario escribe (evento "input") en el buscador, se dispara esta función
        input.addEventListener('input', () => {
            const query = input.value.toLowerCase().trim();
            // Vaciamos la lista de sugerencias anterior antes de calcular las nuevas
            lista.innerHTML = '';
            // Si el campo quedó vacío, ocultamos la lista y no seguimos calculando nada
            if (!query) return (lista.style.display = 'none');

            // Prevenimos error si la propiedad no viene definida en el JSON usando un fallback de array vacío
            const coleccion = datos[tipo.toLowerCase()] || [];

            const coincidencias = coleccion.filter(item =>
                item.denominacion.toLowerCase().includes(query) && !seleccionados.has(String(item.id))
            ).sort((a, b) => {
                // Ordenamos los resultados: primero los que EMPIEZAN con lo buscado
                const nombreA = a.denominacion.toLowerCase();
                const nombreB = b.denominacion.toLowerCase();
                const aEmpieza = nombreA.startsWith(query);
                const bEmpieza = nombreB.startsWith(query);

                // Si "a" empieza con el texto buscado y "b" no, "a" va primero en la lista
                if (aEmpieza && !bEmpieza) return -1;
                // Si es al revés, "b" va primero
                if (!aEmpieza && bEmpieza) return 1;
                // Si los dos empiezan igual (o ninguno empieza, solo contiene), los ordenamos alfabéticamente
                return a.denominacion.localeCompare(b.denominacion);
            });

            // Si encontramos coincidencias mostramos la lista ('block'), si no la ocultamos ('none')
            lista.style.display = coincidencias.length ? 'block' : 'none';

            // Recorremos cada coincidencia encontrada para crear su fila en la lista de sugerencias
            coincidencias.forEach(item => {
                // Creamos un <li> nuevo por cada sugerencia
                const li = document.createElement('li');
                // Le ponemos clases de Bootstrap para que se vea como un ítem de lista clickeable
                li.className = 'list-group-item list-group-item-action d-flex justify-content-between pointer';
                
                // Usamos textContent para la denominación para evitar vulnerabilidades XSS
                const textoSpan = document.createElement('span');
                textoSpan.textContent = item.denominacion;
                li.appendChild(textoSpan);

                // Agregamos el ícono de check verde a la derecha
                const icono = document.createElement('i');
                icono.className = 'bi bi-check-circle-fill text-success';
                li.appendChild(icono);

                // Si el usuario hace clic en esta sugerencia...
                li.onclick = () => {
                    // ...la agregamos como badge/bloque al formulario
                    agregarBadge(item, tipo, requiereValor, contenedor, seleccionados);
                    // Limpiamos el input de búsqueda para que quede listo para la próxima búsqueda
                    input.value = '';
                    // Ocultamos la lista de sugerencias porque ya se eligió una opción
                    lista.style.display = 'none';
                };
                // Insertamos el <li> recién armado dentro del <ul> de sugerencias
                lista.appendChild(li);
            });
        });

        // Ocultamos la lista de sugerencias si el usuario hace clic fuera de la lista y del input
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !lista.contains(e.target)) {
                lista.style.display = 'none';
            }
        });
    }

    // 3. Función encargada de renderizar la etiqueta/atributo seleccionado en el DOM
    //    valorInicial: opcional, se usa para precargar el valor de un atributo ya guardado (edición)
    function agregarBadge(item, tipo, requiereValor, contenedor, seleccionados, valorInicial = '') {
        // Normalizamos el id a String para asegurar coincidencia estricta en el Set
        const itemIdStr = String(item.id);
        // Guardamos el id del item en el Set para que no se pueda volver a elegir mientras esté agregado
        seleccionados.add(itemIdStr);
        
        // Armamos el prefijo en minúsculas ("etiqueta" o "atributo") para usarlo en los "name" de los inputs
        const prefix = tipo.toLowerCase();

        // Creamos el <div> que va a envolver visualmente el badge/bloque
        const div = document.createElement('div');
        // Si es Etiqueta ocupa media fila (col-6), si es Atributo ocupa la fila completa (col-12)
        div.className = tipo === 'Etiqueta' ? 'col-6' : 'col-12';

        // Escapamos comillas para evitar fallos HTML si el valor las incluye
        const valorEscapado = (valorInicial ?? '').toString().replace(/"/g, '&quot;');
        const campoValor = requiereValor
            ? `<input type="text" class="form-control form-control-sm" name="${prefix}_valores[${item.id}]" placeholder="Ingresar dato..." value="${valorEscapado}" required>`
            : '';

        // Armamos la estructura HTML dejando una etiqueta vacía con clase .texto-item para inyectar seguro el nombre
        if (tipo === 'Etiqueta') {
            div.innerHTML = `
                <span class="badge bg-primary w-100 p-2 fs-6 d-flex justify-content-between align-items-center text-start text-wrap h-100">
                    <span class="texto-item"></span>
                    <input type="hidden" name="${prefix}_ids[]" value="${item.id}">
                    <button type="button" class="btn-close btn-close-white ms-2 btn-borrar" aria-label="Eliminar"></button>
                </span>
            `;
        } else {
            div.innerHTML = `
                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light w-100">
                    <span class="badge bg-secondary p-2 text-start flex-grow-1 texto-item"></span>
                    <input type="hidden" name="${prefix}_ids[]" value="${item.id}">
                    ${campoValor}
                    <button type="button" class="btn btn-sm btn-outline-danger btn-borrar"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            `;
        }

        // Asignamos el texto seguro
        div.querySelector('.texto-item').textContent = item.denominacion;

        // Asignamos el comportamiento al botón de borrar
        div.querySelector('.btn-borrar').onclick = () => {
            // Sacamos el bloque completo de la pantalla
            div.remove();
            // Liberamos el id del Set para que vuelva a aparecer disponible
            seleccionados.delete(itemIdStr);
        };

        // Insertamos el bloque dentro del contenedor correspondiente
        contenedor.appendChild(div);

        // Si este tipo pedía un valor adicional (atributos), le damos foco automático al input
        if (requiereValor) {
            const inputGenerado = div.querySelector('input[type="text"]');
            if (inputGenerado) {
                setTimeout(() => {
                    inputGenerado.focus();
                }, 10);
            }
        }
    }

    // --- LÓGICA DEL MODAL DE ATRIBUTOS ---
    const modalAtributos = document.getElementById('modalAtributos');
    const listaModalAtributos = document.getElementById('listaTodosAtributosModal');
    const contenedorAtributos = document.getElementById('contenedorAtributos');

    if (modalAtributos) {
        modalAtributos.addEventListener('show.bs.modal', () => {
            listaModalAtributos.innerHTML = '';
            let hayDisponibles = false;
            const coleccionAtributos = datos.atributo || [];

            [...coleccionAtributos].sort((a, b) => a.denominacion.localeCompare(b.denominacion)).forEach(item => {
                if (!seleccionadosGlobal['Atributo'].has(String(item.id))) {
                    hayDisponibles = true;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    
                    const textoSpan = document.createElement('span');
                    textoSpan.textContent = item.denominacion;
                    btn.appendChild(textoSpan);

                    const icono = document.createElement('i');
                    icono.className = 'bi bi-plus-circle text-success';
                    btn.appendChild(icono);

                    btn.onclick = () => {
                        // requiereValor = true, porque los atributos siempre piden un valor extra
                        agregarBadge(item, 'Atributo', true, contenedorAtributos, seleccionadosGlobal['Atributo']);
                        btn.remove();
                        if (listaModalAtributos.children.length === 0) {
                            listaModalAtributos.innerHTML = '<div class="text-muted text-center p-3">No hay más atributos disponibles.</div>';
                        }
                    };
                    listaModalAtributos.appendChild(btn);
                }
            });

            if (!hayDisponibles) {
                listaModalAtributos.innerHTML = '<div class="text-muted text-center p-3">Todos los atributos ya han sido agregados.</div>';
            }
        });
    }

    // Inicializamos las búsquedas autocompletables
    setupAutocompletar('Etiqueta', false);
    setupAutocompletar('Atributo', true);

    // --- LÓGICA DEL MODAL DE ETIQUETAS ---
    const modalEtiquetas = document.getElementById('modalEtiquetas');
    const listaModal = document.getElementById('listaTodasEtiquetasModal');
    const contenedorEtiquetas = document.getElementById('contenedorEtiquetas');

    if (modalEtiquetas) {
        // Bootstrap dispara este evento automáticamente justo antes de mostrarse
        modalEtiquetas.addEventListener('show.bs.modal', () => {
            // Limpiamos la lista del modal cada vez que se abre
            listaModal.innerHTML = '';
            let hayDisponibles = false;
            const coleccionEtiquetas = datos.etiqueta || [];

            // Corregido: Se eliminó el salto de línea que rompía la sintaxis de la desestructuración (...)
            [...coleccionEtiquetas].sort((a, b) => a.denominacion.localeCompare(b.denominacion)).forEach(item => {
                // Si esta etiqueta todavía NO fue agregada al formulario, la mostramos como opción
                if (!seleccionadosGlobal['Etiqueta'].has(String(item.id))) {
                    hayDisponibles = true;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    
                    const textoSpan = document.createElement('span');
                    textoSpan.textContent = item.denominacion;
                    btn.appendChild(textoSpan);

                    const icono = document.createElement('i');
                    icono.className = 'bi bi-plus-circle text-success';
                    btn.appendChild(icono);

                    btn.onclick = () => {
                        // 1. La agregamos al formulario
                        agregarBadge(item, 'Etiqueta', false, contenedorEtiquetas, seleccionadosGlobal['Etiqueta']);
                        // 2. Sacamos este botón de la lista del modal
                        btn.remove();
                        // 3. Si ya no quedan más opciones, mostramos el aviso
                        if (listaModal.children.length === 0) {
                            listaModal.innerHTML = '<div class="text-muted text-center p-3">No hay más etiquetas disponibles.</div>';
                        }
                    };
                    listaModal.appendChild(btn);
                }
            });

            if (!hayDisponibles) {
                listaModal.innerHTML = '<div class="text-muted text-center p-3">Todas las etiquetas ya han sido agregadas.</div>';
            }
        });
    }

    // Si la vista mandó etiquetas/atributos ya vinculados al objeto (editar.php),
    // los pintamos como badges ya agregados apenas carga la página.
    if (Array.isArray(datos.etiquetasSeleccionadas)) {
        datos.etiquetasSeleccionadas.forEach(item => {
            agregarBadge(item, 'Etiqueta', false, contenedorEtiquetas, seleccionadosGlobal['Etiqueta']);
        });
    }

    if (Array.isArray(datos.atributosSeleccionados)) {
        datos.atributosSeleccionados.forEach(item => {
            agregarBadge(item, 'Atributo', true, contenedorAtributos, seleccionadosGlobal['Atributo'], item.valor);
        });
    }
});