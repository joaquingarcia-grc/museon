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
            <a class="btn btn-success" href="<?php echo base_url();?>usuarios/">
                Usuarios <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Telefono</th>
                    <th class='text-center'>fecha de borrado</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario){ ?>
                            <tr>
                                <td>
                                    <?php echo $usuario["id"];?> 
                                </td>
                                <td>
                                    <?php echo $usuario["denominacion"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $usuario["telefono"];?> 
                                </td> 
                                <td class='tex-center'>
                                    <?php echo $usuario["fecha_baja"]?>
                                </td>
                                <td class='text-center'>
                                    <a class='btn btn-warning' 
                                        href="<?php echo base_url(); ?>usuarios/recuperacion/<?php echo $usuario["id"];?>">
                                        Resuperar<i class="bi bi-recycle"></i>
                                    </a>  
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
</div>