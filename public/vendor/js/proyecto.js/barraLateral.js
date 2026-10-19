$(document).ready(function(){

    var fijada = false;

    function abrirBarra() {
        $("body").removeClass("sidebar-toggled");
        $(".sidebar").removeClass("toggled");
    }

    function cerrarBarra() {
        $("body").addClass("sidebar-toggled");
        $(".sidebar").addClass("toggled");
        $('.sidebar .collapse').collapse('hide');
    }

    // si estaba fijada la dejo abierta, si no arranca cerrada
    if (localStorage.getItem('sb|sidebar-fijada') === 'true') {
        fijada = true;
        abrirBarra();
    } else {
        cerrarBarra();
    }

    // click en el boton fija o suelta la barra
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

    // boton de arriba (celu), abre y cierra normal
    $('#sidebarToggleTop').on('click', function (e) {
        e.preventDefault();
        if ($(".sidebar").hasClass("toggled")) {
            abrirBarra();
        } else {
            cerrarBarra();
        }
    });

    // hover en la barra (el boton ya esta adentro)
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

    // cierro los acordeones si la ventana baja de 768px
    $(window).resize(function() {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }

        // por debajo de 480px la cierro
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            cerrarBarra();
        }
    });

    // evito que scrollee el contenido cuando paso el mouse por la barra fija
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

    // boton de volver arriba
    $(document).on('scroll', function() {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // scroll suave
    $(document).on('click', 'a.scroll-to-top', function(e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

});