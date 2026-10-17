$(document).ready(function(){
    $("#buscador").on('keyup', function(){
        $('#tablaclientes').DataTable().search(this.value).draw();
    });
});