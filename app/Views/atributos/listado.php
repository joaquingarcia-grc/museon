<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>atributos/nuevo">
                Nuevo Atributo <i class="bi bi-tag"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table table-striped' id="tablaclientes" data-papelera-url="<?php echo esc(base_url('atributos/papelera'), 'attr'); ?>">
            <thead>
                <tr>
                    <th class='text-center'>ID</th>
                    <th class='text-center'>Denominación</th>
                    <th class='text-center'>Fecha Alta</th>
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
                        <td class='text-center'>
                            <?php echo $atributo["fecha_alta"];?>
                        </td>
                        <td class='text-center'>    
                            <a class='btn btn-primary' href="<?php echo base_url();?>atributos/editar/<?php echo $atributo["id"];?>">
                                <i class='bi bi-pencil-square'></i>
                            </a>   
                            <a class='btn btn-danger' href="<?php echo base_url(); ?>atributos/borrar/<?php echo $atributo["id"];?>">
                                <i class='bi bi-trash2-fill'></i>
                            </a>
                        </td>                            
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<div id="toats"></div> 