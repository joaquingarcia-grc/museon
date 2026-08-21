<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>objetos/nuevo">
                Nuevo Objeto <i class="bi bi-box"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Descripcion</th>
                    <th class='text-center'>Fecha alta</th>
                    <th class='text-end'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objetos as $objeto){ ?>
                            <tr>
                                <td>
                                    <?php echo $objeto["id"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $objeto["denominacion"];?> 
                                </td> 
                                <td class='text-center'>
                                    <?php echo $objeto["descripcion"];?> 
                                </td> 
                                <td class='tex-center'>
                                    <?php echo $objeto["fecha_alta"]?>
                                </td>
                                <td class='text-end'>    
                                    <a class='btn btn-primary' 
                                        href="<?php echo base_url()?>objetos/editar/<?php echo $objeto["id"];?>">
                                        <i class='bi bi-pencil-square'></i>
                                    </a>   
                                    <a class='btn btn-danger' 
                                                    href="<?php echo base_url(); ?>objetos/borrar/<?php echo $objeto["id"];?>">
                                        <i class='bi bi-trash2-fill'></i>
                                    </a>
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
        <a class="btn btn-outline-secondary" href="<?php echo base_url();?>objetos/papelera">
            <i class='bi bi-trash2-fill'>Papelera</i>
        </a>
    </div>
</div>