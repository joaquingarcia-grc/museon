document.addEventListener('DOMContentLoaded', () => {
    const inputImagenes = document.getElementById('imagenes');
    const vistaPrevia = document.getElementById('vistaPreviaImagenes');
    const aviso = document.getElementById('avisoImagenes');

    if (!inputImagenes || !vistaPrevia) {
        return;
    }

    const formulario = inputImagenes.form;
    const TIPOS_PERMITIDOS = ['image/jpeg', 'image/png'];
    const MAX_BYTES = 5 * 1024 * 1024;

    // imágenes nuevas elegidas que todavía no se guardaron
    let archivos = [];

    // identifica un archivo para no cargar dos veces el mismo
    function clave(archivo) {
        return archivo.name + '|' + archivo.size + '|' + archivo.lastModified;
    }

    // el input solo recuerda la última selección: lo reconstruimos con todas las acumuladas
    function sincronizarInput() {
        const transferencia = new DataTransfer();
        archivos.forEach(archivo => transferencia.items.add(archivo));
        inputImagenes.files = transferencia.files;
    }

    function mostrarAviso(texto) {
        if (aviso) {
            aviso.textContent = texto;
        }
    }

    // clave de la portada si es una imagen nueva (null si es una ya guardada)
    function portadaNuevaElegida() {
        const marcada = formulario.querySelector('input[name="portada"]:checked');
        return marcada && marcada.dataset.clave ? marcada.dataset.clave : null;
    }

    // si no quedó ninguna portada marcada, marca la primera que haya
    function asegurarPortada() {
        if (formulario.querySelector('input[name="portada"]:checked')) {
            return;
        }
        const primera = formulario.querySelector('input[name="portada"]');
        if (primera) {
            primera.checked = true;
        }
    }

    function renderizar() {
        const elegida = portadaNuevaElegida();
        vistaPrevia.innerHTML = '';

        archivos.forEach((archivo, indice) => {
            const k = clave(archivo);

            const columna = document.createElement('div');
            columna.className = 'col-6 col-md-3 text-center';

            const tarjeta = document.createElement('div');
            tarjeta.className = 'imagen-pieza';

            const imagen = document.createElement('img');
            imagen.className = 'img-thumbnail';
            imagen.alt = 'Vista previa de ' + archivo.name;
            imagen.src = URL.createObjectURL(archivo);
            imagen.onload = () => URL.revokeObjectURL(imagen.src);

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'imagen-pieza-quitar';
            quitar.title = 'Quitar imagen';
            quitar.setAttribute('aria-label', 'Quitar ' + archivo.name);
            quitar.innerHTML = '&times;';
            quitar.addEventListener('click', () => {
                archivos = archivos.filter(a => clave(a) !== k);
                sincronizarInput();
                renderizar();
            });

            const etiqueta = document.createElement('label');
            etiqueta.className = 'd-block mt-1';

            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'portada';
            radio.value = 'nueva-' + indice; // coincide con la posición dentro de imagenes[]
            radio.dataset.clave = k;
            radio.checked = (k === elegida);

            etiqueta.appendChild(radio);
            etiqueta.append(' Portada');
            tarjeta.appendChild(imagen);
            tarjeta.appendChild(quitar);
            columna.appendChild(tarjeta);
            columna.appendChild(etiqueta);
            vistaPrevia.appendChild(columna);
        });

        asegurarPortada();
    }

    inputImagenes.addEventListener('change', () => {
        const rechazadas = [];

        Array.from(inputImagenes.files).forEach(archivo => {
            if (!TIPOS_PERMITIDOS.includes(archivo.type)) {
                rechazadas.push(archivo.name + ' (solo JPG o PNG)');
                return;
            }
            if (archivo.size > MAX_BYTES) {
                rechazadas.push(archivo.name + ' (supera los 5 MB)');
                return;
            }
            if (archivos.some(a => clave(a) === clave(archivo))) {
                return; // ya estaba cargada
            }
            archivos.push(archivo);
        });

        mostrarAviso(rechazadas.length ? 'No se agregaron: ' + rechazadas.join(', ') : '');
        sincronizarInput();
        renderizar();
    });

    // editar: la cruz de una imagen ya guardada la saca de la pantalla y avisa al servidor al guardar
    formulario.addEventListener('click', (evento) => {
        const boton = evento.target.closest('.imagen-pieza-quitar[data-id]');
        if (!boton) {
            return;
        }

        const campo = document.createElement('input');
        campo.type = 'hidden';
        campo.name = 'imagenes_quitar[]';
        campo.value = boton.dataset.id;
        formulario.appendChild(campo);

        boton.closest('.imagen-actual').remove();
        asegurarPortada();
    });

    // después de guardar, nuevo.php resetea el formulario: limpiamos todo
    formulario.addEventListener('reset', () => {
        archivos = [];
        vistaPrevia.innerHTML = '';
        mostrarAviso('');
    });

    asegurarPortada();
});