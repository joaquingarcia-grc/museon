<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col text-left">
            <h2>
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class="col text-end">
            <!-- vuelve a la vista de salas -->
            <a href="<?php echo base_url(); ?>salas" class="btn btn-outline-secondary" id="btnVolverSalas">
                Volver a las salas
            </a>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover w-100" id="tablaSalasBr">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Denominación</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Fecha de baja</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyBrTablaSalas">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>