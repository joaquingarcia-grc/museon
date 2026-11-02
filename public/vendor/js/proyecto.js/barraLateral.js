$(document).ready(function(){

    // guardo si la barra esta fijada (abierta) o no
    var fijada = false;

    // abre la barra sacandole las clases de cerrada
    function abrirBarra() {
        $("body").removeClass("sidebar-toggled");
        $(".sidebar").removeClass("toggled");
    }

    // cierra la barra y de paso cierra todos los desplegables de Componentes
    function cerrarBarra() {
        $("body").addClass("sidebar-toggled");
        $(".sidebar").addClass("toggled");
        $('.sidebar .collapse').collapse('hide');
    }

    // al cargar la pagina: si estaba fijada la dejo abierta, si no arranca cerrada
    if (localStorage.getItem('sb|sidebar-fijada') === 'true') {
        fijada = true;
        abrirBarra();
    } else {
        cerrarBarra();
    }

    // click en el boton redondo: fija o suelta la barra y guarda el estado
    $('#barraLateral').on('click', function (e) {
        e.preventDefault();
        if (fijada) {
            fijada = false;
            cerrarBarra();
        } else {
            fijada = true;
            abrirBarra();
        }
        localStorage.setItem('sb|sidebar-fijada', fijada);
    });

    // boton de arriba (celu): abre y cierra la barra normal
    $('#sidebarToggleTop').on('click', function (e) {
        e.preventDefault();
        if ($(".sidebar").hasClass("toggled")) {
            abrirBarra();
        } else {
            cerrarBarra();
        }
    });

    // hover: si no esta fijada se abre al pasar el mouse y se cierra al salir
    $('.sidebar').hover(
        function () {
            if (fijada === false) {
                abrirBarra();
            }
        },
        function () {
            if (fijada === false) {
                cerrarBarra();
            }
        }
    );

    // si la ventana baja de 768px cierro los desplegables
    $(window).resize(function() {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }

        // por debajo de 480px cierro la barra entera
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            cerrarBarra();
        }
    });

    // si el body tiene fixed-nav, el scroll con el mouse sobre la barra mueve la barra y no el contenido
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent;
            var delta = e0.wheelDelta || -e0.detail;
            if (delta < 0) {
                this.scrollTop += 30;
            } else {
                this.scrollTop -= 30;
            }
            e.preventDefault();
        }
    });

    // muestra u oculta el boton de volver arriba segun cuanto scrolleaste
    $(document).on('scroll', function() {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // click en el boton de volver arriba: sube con scroll suave
    $(document).on('click', 'a.scroll-to-top', function(e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

});