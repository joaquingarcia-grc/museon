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
            <a class="btn btn-success" href="<?php echo base_url();?>atributos/">
                Atributos <i class="bi bi-card-checklist"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th class='text-center'>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>fecha de borrado</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($atributos as $atributo){ ?>
                            <tr>
                                <td class='text-center'>
                                    <?php echo $atributo["id"];?> 
                                </td>
                                
                                <td class='text-center'>
                                    <?php echo $atributo["denominacion"];?> 
                                </td>
                                
                                <td class='tex-center'>
                                    <?php echo $atributo["fecha_baja"]?>
                                </td>

                                <td class='text-center'>
                                    <a class='btn btn-warning' 
                                        href="<?php echo base_url(); ?>atributos/recuperacion/<?php echo $atributo["id"];?>">
                                        Resuperar<i class="bi bi-recycle"></i>
                                    </a>  
                                </td>                            
                            </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>
</div>