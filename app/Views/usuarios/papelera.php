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
            <a class="btn btn-success" href="<?php echo base_url();?>clientes/">
                Clientes <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Nombre</th>
                    <th class='text-center'>Apellido</th>
                    <th class='text-center'>fecha de borrado</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente){ ?>
                            <tr>
                                <td>
                                    <?php echo $cliente["id"];?> 
                                </td>
                                <td>
                                    <?php echo $cliente["nombre"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $cliente["apellido"];?> 
                                </td> 
                                <td class='tex-center'>
                                    <?php echo $cliente["fecha_borrado"]?>
                                </td>
                                <td class='text-center'>
                                    <a class='btn btn-warning' 
                                        href="<?php echo base_url(); ?>clientes/recuperacion/<?php echo $cliente["id"];?>">
                                        Resuperar<i class="bi bi-recycle"></i>
                                    </a>  
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
</div>