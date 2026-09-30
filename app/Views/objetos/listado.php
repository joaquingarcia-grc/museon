<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>objetos/nuevo">
                Nuevo Objeto <i class="bi bi-box-seam-fill"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table table-striped' id="tablaclientes" data-papelera-url="<?php echo esc(base_url('objetos/papelera'), 'attr'); ?>">
            <thead>
                <tr>
                    <th class='text-center'>ID</th>
                    <th class='text-center'>Código</th>
                    <th class='text-center'>Denominación</th>
                    <th class='text-center'>Descripción</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($objetos)): ?>
                    <?php foreach ($objetos as $objeto){ ?>
                        <tr>
                            <td class='text-center'>
                                <?php echo $objeto["id"];?> 
                            </td>
                            <td class='text-center'>
                                <?php echo $objeto["codigo"];?> 
                            </td>
                            <td class='text-center'>
                                <?php echo $objeto["denominacion"];?>
                            </td>
                            <td class='text-center'>
                                <?php echo $objeto["descripcion"];?>
                            </td>
                            <td class='text-center'> 
                                <a class='btn btn-info' href="<?php echo base_url();?>objetos/ver/<?php echo $objeto["id"];?>" title="Ver Ficha">
                                    <i class='bi bi-eye-fill'></i>
                                </a>   
                                <a class='btn btn-primary' href="<?php echo base_url();?>objetos/editar/<?php echo $objeto["id"];?>" title="Editar">
                                    <i class='bi bi-pencil-square'></i>
                                </a>   
                                <a class='btn btn-danger' href="<?php echo base_url(); ?>objetos/borrar/<?php echo $objeto["id"];?>" title="Borrar">
                                    <i class='bi bi-trash2-fill'></i>
                                </a>
                            </td>                            
                        </tr>
                    <?php }?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>