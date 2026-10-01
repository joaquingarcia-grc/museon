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
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Piezas <i class="bi bi-box"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table-secondary table-striped' id="tablaOBJBr">
            <thead>
                <tr>
                    <th class='text-center'>id</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>fecha de borrado</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody id= 'bodyBrTablaOBJ'>

            </tbody>
        </table>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>