document.addEventListener('DOMContentLoaded', () => {
    const inputArchivos = document.getElementById('archivos');
    const lista = document.getElementById('vistaPreviaArchivos');
    const aviso = document.getElementById('avisoArchivos');

    if (!inputArchivos || !lista) {
        return;
    }

    const formulario = inputArchivos.form;
    const EXTENSIONES_PERMITIDAS = ['pdf', 'doc', 'docx'];
    const MAX_BYTES = 10 * 1024 * 1024;

    // archivos nuevos elegidos que todavía no se guardaron
    let archivos = [];

    function clave(archivo) {
        return archivo.name + '|' + archivo.size + '|' + archivo.lastModified;
    }

    function extension(nombre) {
        const punto = nombre.lastIndexOf('.');
        return punto === -1 ? '' : nombre.slice(punto + 1).toLowerCase();
    }

    function tamano(bytes) {
        return bytes >= 1024 * 1024
            ? (bytes / (1024 * 1024)).toFixed(1) + ' MB'
            : Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    // el input solo recuerda la última selección: lo reconstruimos con todos los acumulados
    function sincronizarInput() {
        const transferencia = new DataTransfer();
        archivos.forEach(archivo => transferencia.items.add(archivo));
        inputArchivos.files = transferencia.files;
    }

    function mostrarAviso(texto) {
        if (aviso) {
            aviso.textContent = texto;
        }
    }

    function renderizar() {
        lista.innerHTML = '';

        archivos.forEach(archivo => {
            const k = clave(archivo);

            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';

            const nombre = document.createElement('span');
            nombre.textContent = archivo.name + ' (' + tamano(archivo.size) + ')';

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'btn btn-sm btn-outline-danger';
            quitar.title = 'Quitar archivo';
            quitar.setAttribute('aria-label', 'Quitar ' + archivo.name);
            quitar.innerHTML = '&times;';
            quitar.addEventListener('click', () => {
                archivos = archivos.filter(a => clave(a) !== k);
                sincronizarInput();
                renderizar();
            });

            item.appendChild(nombre);
            item.appendChild(quitar);
            lista.appendChild(item);
        });
    }

    inputArchivos.addEventListener('change', () => {
        const rechazados = [];

        Array.from(inputArchivos.files).forEach(archivo => {
            if (!EXTENSIONES_PERMITIDAS.includes(extension(archivo.name))) {
                rechazados.push(archivo.name + ' (solo PDF, DOC o DOCX)');
                return;
            }
            if (archivo.size > MAX_BYTES) {
                rechazados.push(archivo.name + ' (supera los 10 MB)');
                return;
            }
            if (archivos.some(a => clave(a) === clave(archivo))) {
                return; // ya estaba cargado
            }
            archivos.push(archivo);
        });

        mostrarAviso(rechazados.length ? 'No se agregaron: ' + rechazados.join(', ') : '');
        sincronizarInput();
        renderizar();
    });

    // editar: la cruz de un archivo ya guardado lo saca de la pantalla y avisa al servidor al guardar
    formulario.addEventListener('click', (evento) => {
        const boton = evento.target.closest('.archivo-pieza-quitar[data-id]');
        if (!boton) {
            return;
        }

        const campo = document.createElement('input');
        campo.type = 'hidden';
        campo.name = 'archivos_quitar[]';
        campo.value = boton.dataset.id;
        formulario.appendChild(campo);

        boton.closest('.archivo-actual').remove();
    });

    // después de guardar, nuevo.php resetea el formulario: limpiamos todo
    formulario.addEventListener('reset', () => {
        archivos = [];
        lista.innerHTML = '';
        mostrarAviso('');
    });
});