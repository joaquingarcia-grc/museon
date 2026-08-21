<div class="container-fluid px-4">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>atributos/nuevo">
                Nuevo atributo <i class="bi bi-blockquote-left"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table id="tablaclientes">
            <thead>
                <tr>
                    <th>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Tipos de datos</th>
                    <th class='text-center'>Fecha alta</th>
                    <th class='text-end'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($atributos as $atributo){ ?>
                            <tr>
                                <td>
                                    <?php echo $atributo["id"];?> 
                                </td>
                                <td class='text-center'>
                                    <?php echo $atributo["denominacion"];?> 
                                </td> 
                                <td class='text-center'>
                                    <?php echo $atributo["tipo_dato"];?> 
                                </td> 
                                <td class='tex-center'>
                                    <?php echo $atributo["fecha_alta"]?>
                                </td>
                                <td class='text-end'>    
                                    <a class='btn btn-primary' 
                                        href="<?php echo base_url()?>atributos/editar/<?php echo $atributo["id"];?>">
                                        <i class='bi bi-pencil-square'></i>
                                    </a>   
                                    <a class='btn btn-danger' 
                                                    href="<?php echo base_url(); ?>atributos/borrar/<?php echo $atributo["id"];?>">
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
</div>

<!-- Script que inyecta el botón de la papelera dentro del bloque de DataTables -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            const dtButtonsContainer = document.querySelector('.dt-buttons');
            if (dtButtonsContainer) {
                // Verificamos que no se duplique si la página se recarga o actualiza
                if (!document.querySelector('.dt-buttons .btn-papelera-custom')) {
                    const btnPapelera = document.createElement('a');
                    btnPapelera.href = "<?php echo base_url();?>atributos/papelera";
                    btnPapelera.className = "dt-button btn-papelera-custom";
                    btnPapelera.innerHTML = '<i class="bi bi-trash2-fill"></i><span>Papelera</span>';
                    dtButtonsContainer.appendChild(btnPapelera);
                }
            }
        }, 300);
    });
</script>