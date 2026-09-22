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
        <table class='table table-striped' id="tablaclientes">
            <thead>
                <tr>
                    <th class='text-center'>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Telefono</th>
                    <th class='text-center'>fecha de borrado</th>
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
                                    <?php echo $usuario["telefono"];?> 
                                </td> 
                                <td class='text-center'>
                                    <?php echo $usuario["fecha_baja"]?>
                                </td>
                                <td class='text-center'>
                                    <button class='btn btn-warning btn-recuperarUSR' 
                                        data-id="<?php echo $usuario["id"];?>" id="btnRecuperar">
                                        Resuperar
                                        <i class="bi bi-recycle"></i>
                                    </button>

                                    <!--a class='btn btn-warning' 
                                        href="<?php echo base_url(); ?>usuarios/recuperacion/<?php echo $usuario["id"];?>">
                                        Resuperar<i class="bi bi-recycle"></i>
                                    </a-->  
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>