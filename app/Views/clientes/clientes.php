
    <div>
        <div class="row">
            <div class="col">
                <p><h2> 
                    <?php 
                        echo $titulo;
                    ?>
                </h2></p>
            </div>
            <div class="col text-right  py-3 px-4">
                <a class="btn btn-outline-success" href="<?php base_url();?>clientes/nuevo">
                    <i class="bi bi-plus">Nuevo</i>
                </a>
            </div>
        </div>
        
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th class='text-center'>Apellido</th>
                    <th class='text-end'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente){ ?>
                            <tr>
                                <td>
                                    <?php echo $cliente["nombre"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $cliente["apellido"];?> 
                                </td> 
                                <td class='text-end'>    
                                    <button type='button' class='btn btn-primary'>
                                        <i class='bi bi-pencil-square'></i>
                                    </button>   
                                    <a class='btn btn-danger' 
                                                    href="<?php echo base_url(); ?>clientes/borrar/<?php echo $cliente["id"];?>">
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