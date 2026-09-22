<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>usuarios/nuevo">
                Nuevo Usuario <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table table-striped' id="tablaclientes">
            <thead>
                <tr>
                    <th class='text-center'>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Email</th>
                    <th class='text-center'>Telefono</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario){ ?>
                            <tr>
                                <td class='text-center'>
                                    <?php echo $usuario["id"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $usuario["denominacion"];?> 
                                </td> 
                                <td class='text-center'>
                                    <?php echo $usuario["email"]?>
                                </td>
                                <td class='text-center'>
                                    <?php echo $usuario["telefono"]?>
                                </td>
                                <td class='text-center'>    
                                    <a class='btn btn-primary' 
                                        href="<?php echo base_url()?>usuarios/editar/<?php echo $usuario["id"];?>">
                                        <i class='bi bi-pencil-square'></i>
                                    </a> 
                                    <button type="submit" class="btn btn-danger btn-borrarUSR" id="btnBorrarUSR" data-id="<?php echo $usuario["id"];?>">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
    <div class='col text-right  py-3 px-4'>            
        <a class="btn btn-outline-secondary" href="<?php echo base_url();?>usuarios/papelera">
            <i class='bi bi-trash2-fill'>Papelera</i>
        </a>
    </div>
</div>
<div class="modal fade" id="modalBorradoUSR" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
              <button type="button" class="btn btn-danger" id="btnBorradoUSR">Borrar</button>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>