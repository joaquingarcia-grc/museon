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
        <table class='table-primary table-striped' id="tablaATB">
            <thead>
                <tr>
                    <th class='text-center'>ID</th>
                    <th class='text-center'>Denominación</th>
                    <th class='text-center'>Tipo de dato</th>
                    <th class='text-center'>Fecha Alta</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody id='bodyTablaATB'>
            </tbody>
        </table>
    </div>
</div>

<div id="toats"></div> 