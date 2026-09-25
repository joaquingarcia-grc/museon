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
                    <form id="formularioATB" method="post">
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
                <!-- unidad simela-->   
                                <div class="col-md-6 mt-3" id="grupoUnidad" style="display:none;">
                                    <label for="unidad">Unidad de medida (SIMELA)</label>
                                    <select class="form-control" id="unidad" name="unidad">
                                        <option value="">Sin unidad</option>
                                        <optgroup label="Longitud">
                                            <option value="mm">Milímetros (mm)</option>
                                            <option value="cm">Centímetros (cm)</option>
                                            <option value="m">Metros (m)</option>
                                        </optgroup>
                                        <optgroup label="Masa">
                                            <option value="g">Gramos (g)</option>
                                            <option value="kg">Kilogramos (kg)</option>
                                        </optgroup>
                                        <optgroup label="Volumen">
                                            <option value="ml">Mililitros (ml)</option>
                                            <option value="l">Litros (l)</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" type="submit" id="btnGuardar" >Guardar</button>
                            <a href="<?php echo base_url();?>atributos/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de guardado -->
<div class="modal fade" id="modalGuardarATB" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Guardar Datos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Esta seguro que desea guardar los datos?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary btn-modalGuardado" id="btnGuardarATB">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>
