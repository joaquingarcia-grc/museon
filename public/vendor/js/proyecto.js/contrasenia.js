document.querySelectorAll('[data-mostrar-contrasena]').forEach(function(boton){
    const campo = document.getElementById(boton.getAttribute('aria-controls'));
    const icono = boton.querySelector('i');

    function mostrarContrasena(visible){
        campo.type = visible ? 'text' : 'password';
        const texto = visible ? 'Ocultar contraseña' : 'Mostrar contraseña';
        boton.setAttribute('aria-label', texto);
        boton.setAttribute('title', texto);
        boton.setAttribute('aria-pressed', String(visible));
        icono.classList.toggle('bi-eye', !visible);
        icono.classList.toggle('bi-eye-slash', visible);
    }

    boton.addEventListener('click', function(){
        mostrarContrasena(campo.type === 'password');
    });

    campo.form.addEventListener('reset', function(){
        mostrarContrasena(false);
    });
});
