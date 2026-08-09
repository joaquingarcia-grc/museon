<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php 
                    echo $titulo;
                ?>
            </h2>
        </div>
        <div class="col text-right  py-3 px-4">
            <a class="btn btn-success" href="<?php echo base_url();?>etiquetas/">
                Etiquetas <i class="bi bi-tags"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>fecha de borrado</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etiquetas as $etiqueta){ ?>
                            <tr>
                                <td>
                                    <?php echo $etiqueta["id"];?> 
                                </td>
                                <td>
                                    <?php echo $etiqueta["denominacion"];?> 
                                </td>
                                <td class='tex-center'>
                                    <?php echo $etiqueta["fecha_baja"]?>
                                </td>
                                <td class='text-center'>
                                    <a class='btn btn-warning' 
                                        href="<?php echo base_url(); ?>etiquetas/recuperacion/<?php echo $etiqueta["id"];?>">
                                        Resuperar<i class="bi bi-recycle"></i>
                                    </a>  
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
</div>