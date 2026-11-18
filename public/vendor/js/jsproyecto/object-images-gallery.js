document.addEventListener('DOMContentLoaded', () => {
    const galeria = document.getElementById('fichaGaleria');
    const visor = document.getElementById('visorImagenes');

    if (!galeria || !visor) {
        return;
    }

    // botón: incluir o no las imágenes al imprimir la ficha
    const botonImpresion = document.getElementById('btn-imagenes-impresion');
    if (botonImpresion) {
        botonImpresion.addEventListener('click', () => {
            const activo = document.body.classList.toggle('imprimir-imagenes');
            botonImpresion.setAttribute('aria-pressed', activo ? 'true' : 'false');
            botonImpresion.classList.toggle('btn-secondary', activo);
            botonImpresion.classList.toggle('btn-outline-secondary', !activo);
            botonImpresion.textContent = activo ? 'Imágenes al imprimir: Sí' : 'Imágenes al imprimir: No';
        });
    }

    const imagenVisor = document.getElementById('visorImagen');
    const contador = document.getElementById('visorContador');
    const items = Array.from(galeria.querySelectorAll('.ficha-galeria-item'));
    const fuentes = items.map(item => ({
        src: item.dataset.src,
        alt: item.querySelector('img').alt
    }));

    let actual = 0;
    let ultimoFoco = null;

    if (fuentes.length < 2) {
        visor.classList.add('visor-una'); // con una sola imagen no hacen falta las flechas
    }

    function mostrar(indice) {
        actual = (indice + fuentes.length) % fuentes.length;
        imagenVisor.src = fuentes[actual].src;
        imagenVisor.alt = fuentes[actual].alt;
        contador.textContent = (actual + 1) + ' / ' + fuentes.length;
    }

    function abrir(indice) {
        ultimoFoco = document.activeElement;
        mostrar(indice);
        visor.hidden = false;
        document.body.style.overflow = 'hidden';
        visor.querySelector('[data-visor="cerrar"]').focus();
    }

    function cerrar() {
        visor.hidden = true;
        imagenVisor.removeAttribute('src');
        document.body.style.overflow = '';
        if (ultimoFoco) {
            ultimoFoco.focus();
        }
    }

    items.forEach((item, indice) => {
        item.addEventListener('click', () => abrir(indice));
    });

    // la portada de la tabla también abre el visor
    document.querySelectorAll('[data-visor-abrir]').forEach(elemento => {
        elemento.addEventListener('click', () => abrir(Number(elemento.dataset.visorAbrir)));
    });

    visor.addEventListener('click', (evento) => {
        const boton = evento.target.closest('[data-visor]');
        if (boton) {
            const accion = boton.dataset.visor;
            if (accion === 'cerrar') cerrar();
            if (accion === 'anterior') mostrar(actual - 1);
            if (accion === 'siguiente') mostrar(actual + 1);
            return;
        }
        if (evento.target === visor) {
            cerrar(); // clic en el fondo oscuro
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (visor.hidden) {
            return;
        }
        if (evento.key === 'Escape') cerrar();
        if (evento.key === 'ArrowLeft') mostrar(actual - 1);
        if (evento.key === 'ArrowRight') mostrar(actual + 1);
    });
});