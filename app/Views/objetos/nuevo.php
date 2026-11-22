
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="text-center">
            <h2>
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class="text-right">
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Piezas <i class="bi bi-box"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id='formularioOBJ' method="post">
                        <!-- Datos principales del objeto -->
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label for="codigo" class="form-label fw-bold">Código del Objeto</label>
                                <input class="form-control" type="text" id="codigo" name="codigo" placeholder="Ej: A03, B54..." required autofocus>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="denominacion" class="form-label fw-bold">Denominación</label>
                                <input class="form-control" type="text" id="denominacion" name="denominacion" placeholder="Nombre del objeto">
                            </div>
                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                <input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción breve">
                            </div>
                            <?php $permisosSala = session()->get('permisos')['sala'] ?? []; ?>
                            <?php if (in_array('l', $permisosSala) && in_array('a', $permisosSala)): ?>
                                <div class="col-12 mt-3">
                                    <label for="id_ubicacion" class="form-label fw-bold">Ubicación física</label>
                                    <select class="form-select" id="id_ubicacion" name="id_ubicacion">
                                        <option value="">Sin ubicación</option>
                                        <?php foreach ($salas as $sala): ?>
                                            <option value="<?php echo esc($sala['id'], 'attr'); ?>"><?php echo esc($sala['denominacion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-12 mt-3">
                                <label for="imagenes" class="form-label fw-bold">Imágenes</label>
                                <input class="form-control" type="file" id="imagenes" name="imagenes[]" accept="image/jpeg,image/png" multiple>
                                <small class="text-muted">JPG o PNG (máx. 5 MB cada una). Podés elegir varias a la vez o ir sumando de a una. Marcá cuál es la portada.</small>
                                <div id="avisoImagenes" class="text-danger small mt-1"></div>
                                <div id="vistaPreviaImagenes" class="row g-3 mt-1"></div>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="archivos" class="form-label fw-bold">Archivos adjuntos</label>
                                <input class="form-control" type="file" id="archivos" name="archivos[]" accept=".pdf,.doc,.docx" multiple>
                                <small class="text-muted">PDF, DOC o DOCX (máx. 10 MB cada uno). Podés elegir varios a la vez o ir sumando de a uno.</small>
                                <div id="avisoArchivos" class="text-danger small mt-1"></div>
                                <ul id="vistaPreviaArchivos" class="list-group mt-2"></ul>
                            </div>                        
                        </div>
 
                        <hr>
 
                        <div class="row mb-4">
                            <!-- ETIQUETAS (Autocompletado múltiple dinámico) -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Buscar y Agregar Etiquetas</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <input type="text" id="inputBuscarEtiqueta" class="form-control" placeholder="Escriba para buscar etiqueta..." autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalEtiquetas" title="Ver todas las etiquetas">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                    <ul id="listaSugerenciasEtiquetas" class="list-group position-absolute w-100 shadow object-autocomplete-suggestions"></ul>
                                </div>
                                <div id="contenedorEtiquetas" class="row g-2 mt-3"></div>
                            </div>
 
                            <!-- ATRIBUTOS (Autocompletado con valor dinámico) -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Buscar y Agregar Atributos</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                    <input type="text" id="inputBuscarAtributo" class="form-control" placeholder="Escriba para buscar atributo..." autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalAtributos" title="Ver todos los atributos">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                <ul id="listaSugerenciasAtributos" class="list-group position-absolute w-100 shadow object-autocomplete-suggestions"></ul>
                                </div>
                                <div id="contenedorAtributos" class="row g-2 mt-3"></div>
                            </div>
                        </div>
 
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-outline-success"  type="submit" autofocus>Guardar</button>
                            <a href="<?php echo base_url();?>objetos/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                        <!-- MODAL DE ATRIBUTOS -->
                        <div class="modal fade" id="modalAtributos" tabindex="-1" aria-labelledby="modalAtributosLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalAtributosLabel">Todos los Atributos Disponibles</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="list-group" id="listaTodosAtributosModal">
                                            <!-- JS insertará las opciones aquí -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        <!-- MODAL DE ETIQUETAS -->
                        <div class="modal fade" id="modalEtiquetas" tabindex="-1" aria-labelledby="modalEtiquetasLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEtiquetasLabel">Todas las Etiquetas Disponibles</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="list-group" id="listaTodasEtiquetasModal">
                                            <!-- JS insertará las opciones aquí -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
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