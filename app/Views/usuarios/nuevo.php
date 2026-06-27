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
            <a class="btn btn-success" href="<?php echo base_url();?>clientes/">
                Clientes <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo base_url();?>clientes/insertar" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre">Nombre</label>
                                <input class="form-control" type="text" placeholder="" id="nombre" name="nombre" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="apellido">Apellido</label>
                                <input class="form-control" type="text" placeholder="" id="apellido" name="apellido" aria-label="default input example">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono">Telefono</label>
                                <input class="form-control" type="text" placeholder="" id="telefono" name="telefono" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="domicilio">Domicilio</label>
                            <input class="form-control" type="text" id="domicilio" name="domicilio" placeholder="" aria-label="default input example">
                        </div>
                        <div class="mb-3">
                            <label for="condicion">Condición Frente al IVA</label>
                            <select name="condicion_iva" id="condicion" class="form-select">
                              <option value="">--Seleccione una condicion--</option>
                              <option value="monotributista">Monotributista</option>
                              <option value="consumidor_final">Consumidor Final</option>
                              <option value="responsable_inscripto">Responsable Inscripto</option>
                              <option value="exento_iva">Exento IVA</option>
                            </select>   
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuenta1" id="cuenta1">
                                <label class="form-check-label" for="cuenta1">
                                    Posee cuenta
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuenta2" id="cuenta2" checked>
                                <label class="form-check-label" for="cuenta2">
                                    No posee cuenta
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" type="submit">Guardar</button>
                            <a href="<?php echo base_url();?>clientes/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>