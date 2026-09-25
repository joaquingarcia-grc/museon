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
                Atributos <i class="bi bi-card-checklist"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form data-id="<?php echo $atributos['id'];?>" id="ATBactualizar" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="denominacion">Denominacion</label>
                                <input class="form-control" type="text" placeholder="" 
                                id="denominacion" name="denominacion" 
                                value="<?php echo($atributos['denominacion']); ?>"
                                aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_dato">Tipo de dato</label>
                                <select class="form-control" id="tipo_dato" name="tipo_dato">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="numerico" <?php echo ($atributos['tipo_dato'] == 'numerico') ? 'selected' : ''; ?>>Numérico</option>
                                    <option value="texto" <?php echo ($atributos['tipo_dato'] == 'texto') ? 'selected' : ''; ?>>Texto</option>
                                    <option value="archivo" <?php echo ($atributos['tipo_dato'] == 'archivo') ? 'selected' : ''; ?>>Archivo</option>
                                    <option value="fecha" <?php echo ($atributos['tipo_dato'] == 'fecha') ? 'selected' : ''; ?>>Fecha</option>
                                </select>
                            </div>
                            <!-- bloque de unidad simela -->
                            <div class="col-md-6 mt-3" id="grupoUnidad" style="display:none;">
                                <label for="unidad">Unidad de medida (SIMELA)</label>
                                <select class="form-control" id="unidad" name="unidad">
                                    <option value="" <?php echo empty($atributos['unidad']) ? 'selected' : ''; ?>>Sin unidad</option>
                                    <optgroup label="Longitud">
                                        <option value="mm" <?php echo ($atributos['unidad'] == 'mm') ? 'selected' : ''; ?>>Milímetros (mm)</option>
                                        <option value="cm" <?php echo ($atributos['unidad'] == 'cm') ? 'selected' : ''; ?>>Centímetros (cm)</option>
                                        <option value="m" <?php echo ($atributos['unidad'] == 'm') ? 'selected' : ''; ?>>Metros (m)</option>
                                    </optgroup>
                                    <optgroup label="Masa">
                                        <option value="g" <?php echo ($atributos['unidad'] == 'g') ? 'selected' : ''; ?>>Gramos (g)</option>
                                        <option value="kg" <?php echo ($atributos['unidad'] == 'kg') ? 'selected' : ''; ?>>Kilogramos (kg)</option>
                                    </optgroup>
                                    <optgroup label="Volumen">
                                        <option value="ml" <?php echo ($atributos['unidad'] == 'ml') ? 'selected' : ''; ?>>Mililitros (ml)</option>
                                        <option value="l" <?php echo ($atributos['unidad'] == 'l') ? 'selected' : ''; ?>>Litros (l)</option>
                                    </optgroup>
                                </select>
                            </div>
                            <!-- FIN  -->
                        </div>
                        <div class="row mb-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success" type="submit"  >Actualizar</button>
                                <a href="<?php echo base_url();?>atributos/" class="btn btn-outline-danger">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>
