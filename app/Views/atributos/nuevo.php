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
            <a class="btn btn-success" href="<?php echo base_url();?>atributos/">
                Atributos <i class="bi bi-blockquote-right"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo base_url();?>atributos/insertar" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="denominacion">Denominacion</label>
                                <input class="form-control" type="text" placeholder="" id="denominacion" name="denominacion" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_dato">Tipo de dato</label>
                                <select class="form-control" id="tipo_dato" name="tipo_dato">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="numerico">Numérico</option>
                                    <option value="texto">Texto</option>
                                    <option value="archivo">Archivo</option>
                                    <option value="fecha">Fecha</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" type="submit">Guardar</button>
                            <a href="<?php echo base_url();?>atributos/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>