<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <?php $permisos = session()->get('permisos') ?? []; ?>
        <?php if (in_array('a', $permisos['objeto'] ?? [])): ?> 
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>objetos/nuevo">
                Nueva Pieza <i class="bi bi-box"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row justify-content-center">
        <table class='table-primary table-striped' id="tablaOBJ">
            <thead>
                <tr>
                    <th class='text-center'>id</th>
                    <th class='text-center'>Código</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Descripcion</th>
                    <th class='text-center'>Cargado por</th>
                    <th class='text-center'>Fecha alta</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody id='bodyTablaOBJ'>

            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalBorradoOBJ" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
              <button type="button" class="btn btn-danger" id="botonBorradoOBJ">Borrar</button>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>

