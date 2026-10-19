<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <?php $permisos = session()->get('permisos') ?? []; ?>
            <?php if (in_array('a', $permisos['atributo'] ?? [])): ?>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>atributos/nuevo">
                Nuevo Atributo <i class="bi bi-tag"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table-primary table-striped' id="tablaATB">
            <thead>
                <tr>
                    <th class='text-center'>ID</th>
                    <th class='text-center'>Denominación</th>
                    <th class='text-center'>Tipo de dato</th>
                    <th class='text-center'>Fecha Alta</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody id='bodyTablaATB'>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalBorradoATB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmacion de borrado</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Segura desa borrar este dato?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-danger" id="botonBorradoATB">Borrar</button>
            </div>
        </div>
    </div>
</div>
<div id="toats"></div> 