<div class="container-fluid">
    <div class="row align-items-center">
        <div class="text-center">
            <h2> 
                <?php 
                    echo $titulo;
                ?>
            </h2>
        </div>
        <div class="text-right">
            <a class="btn btn-success" href="<?php echo base_url();?>usuarios/">
                Usuarios <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo base_url();?>usuarios/insertar" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="denominacion">Denominacion</label>
                                <input class="form-control" type="text" placeholder="" id="denominacion" name="denominacion" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono">Telefono</label>
                                <input class="form-control" type="text" placeholder="" id="telefono" name="telefono" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" type="submit">Guardar</button>
                            <a href="<?php echo base_url();?>usuarios/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>