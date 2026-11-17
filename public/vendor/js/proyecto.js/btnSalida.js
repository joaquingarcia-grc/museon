$(document).ready(function(){
    $('#logoutModal').on('shown.bs.modal', function(){
        $('#btnSalir').trigger('focus');
    });
});