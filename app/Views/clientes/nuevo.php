    <div class="container-fluid ">
        <div class="row text-left">
            <p>
                <h2> 
                    <?php 
                        echo $titulo;
                    ?>
                </h2>
            </p>
        </div>
        <form action="<?php echo base_url();?>clientes/insertar" method="post">
            <div class="mb-3">
                <label for="nombre">Nombre</label>
                <input class="form-control" type="text" placeholder="" id="nombre" name="nombre" aria-label="default input example">
            </div>
            <div class="mb-3">
                <label for="apellido">Apellido</label>
                <input class="form-control" type="text" placeholder="" id="apellido" name="apellido"  aria-label="default input example">
            </div>
            <div class="mb-3">
                <label for="telefono">Telefono</label>
                <input class="form-control" type="text" placeholder="" id="telefono" name="telefono" aria-label="default input example">
            </div>
            <div class="mb-3">
                <label for="domicilio">Domicilio</label>
                <input class="form-control" type="text" id="domicilio" name="domicilio" placeholder="" aria-label="default input example">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="cuenta1" id="cuenta1">
                <label class="form-check-label" for="radioDefault1">
                    Posee cuenta
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="cuenta2" id="cuenta2" checked>
                <label class="form-check-label" for="radioDefault2">
                    No posee cuenta
                </label>
            </div>
            <button class="btn btn-outline-success" type="submit">Guardar</button>
            <a href="<?php echo base_url();?>clientes/" class="btn btn-outline-danger">Cancelar</a>
        </form>
    </div>