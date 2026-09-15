<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>etiquetas/nuevo">
                Nueva Etiqueta <i class="bi bi-tags"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Fecha alta</th>
                    <th class='text-end'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etiquetas as $etiqueta){ ?>
                            <tr>
                                <td>
                                    <?php echo $etiqueta["id"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $etiqueta["denominacion"];?> 
                                </td> 
                                <td class='tex-center'>
                                    <?php echo $etiqueta["fecha_alta"]?>
                                </td>
                                <td class='text-end'>    
                                    <a class='btn btn-primary' 
                                        href="<?php echo base_url()?>etiquetas/editar/<?php echo $etiqueta["id"];?>">
                                        <i class='bi bi-pencil-square'></i>
                                    </a>   
                                    <button type="button" class="btn btn-danger btn-borrar" id="btnBorrarAtributo" data-id="<?php echo $etiqueta["id"];?>">
                                        <i class='bi bi-trash2-fill'></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
    <div class='col text-right  py-3 px-4'>            
        <a class="btn btn-outline-secondary" href="<?php echo base_url();?>etiquetas/papelera">
            <i class='bi bi-trash2-fill'>Papelera</i>
        </a>
    </div>
</div>
<div class="modal fade" id="modalBorrado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
              <button type="button" class="btn btn-danger" id="botonBorradosAtributos">Borrar</button>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>