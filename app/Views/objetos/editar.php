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
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Objetos <i class="bi bi-box"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo base_url();?>objetos/actualizar/<?php echo $objetos['id'];?>" method="post">
                        
                        <!-- Datos principales del objeto -->
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label for="codigo" class="form-label fw-bold">Código del Objeto</label>
                                <input class="form-control" type="text" id="codigo" name="codigo" 
                                placeholder="Ej: A03, B54..." 
                                value="<?php echo($objetos['codigo']); ?>" 
                                required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="denominacion" class="form-label fw-bold">Denominación</label>
                                <input class="form-control" type="text" id="denominacion" name="denominacion" 
                                placeholder="Nombre del objeto" 
                                value="<?php echo($objetos['denominacion']); ?>">
                            </div>
                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold">Descripción / Tipo de dato</label>
                                <input class="form-control" type="text" id="descripcion" name="descripcion" 
                                placeholder="Descripción breve" 
                                value="<?php echo($objetos['descripcion']); ?>">
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
                                    <ul id="listaSugerenciasEtiquetas" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; margin-top: 2px;"></ul>
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
                                    <ul id="listaSugerenciasAtributos" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></ul>
                                </div>
                                <div id="contenedorAtributos" class="row g-2 mt-3"></div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-outline-success" type="submit">Actualizar</button>
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

<script type="application/json" id="datosObjeto">
<?php echo json_encode([
    'etiqueta' => $etiquetas ?? [],
    'atributo' => $atributos ?? [],
    'etiquetasSeleccionadas' => $etiquetasSeleccionadas ?? [],
    'atributosSeleccionados' => $atributosSeleccionados ?? []
]); ?>
</script>

<script src="<?php echo base_url(); ?>vendor/js/jsproyecto/objetos.js"></script>