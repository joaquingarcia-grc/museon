<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col text-left">
            <h2>
                <?php echo $titulo; ?>
            </h2>
        </div>
    </div>
    <div class="row">
        <!-- Columna izquierda: marco del arbol de salas -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Salas del museo</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnExpandirSalas">
                        <i class="bi bi-arrows-expand"></i>
                    </button>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0" id="arbolSalas">
                        <?php
                        // dibuja una sala y busca en la lista suelta cuales son sus hijas, se llama a si misma
                        $dibujarSala = function ($sala) use (&$dibujarSala, $salas) {
                            // junto las salas que tienen como id_pertenece el id de esta
                            $hijas = array();
                            foreach ($salas as $posible) {
                                if ($posible['id_pertenece'] == $sala['id']) {
                                    $hijas[] = $posible;
                                }
                            }
                        ?>
                            <li>
                                <div class="d-flex align-items-center py-1" id="sala_<?php echo $sala['id']; ?>" style="cursor:pointer;">
                                    <?php if (!empty($hijas)) { ?>
                                        <i class="bi bi-caret-down-fill me-2"></i>
                                        <i class="bi bi-door-open me-2"></i>
                                    <?php } else { ?>
                                        <i class="bi bi-door-closed me-2"></i>
                                    <?php } ?>
                                    <span><?php echo esc($sala['denominacion']); ?></span>
                                </div>
                                <?php if (!empty($hijas)) { ?>
                                    <ul class="list-unstyled ms-4">
                                        <?php foreach ($hijas as $hija) { ?>
                                            <?php $dibujarSala($hija); ?>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php
                        };
                        // arranco por las que no pertenecen a nadie (el museo)
                        foreach ($salas as $sala) {
                            if (empty($sala['id_pertenece'])) {
                                $dibujarSala($sala);
                            }
                        }
                        ?>
                    </ul>
                </div>
                <!-- Papelera, abajo a la izquierda del marco del arbol -->
                <div class="card-footer text-start">
                    <div class="dt-buttons btn-group">
                        <button type="button" class="dt-button btn btn-outline-secondary" id="btnPapelera" title="Papelera">
                            <span>Papelera <i class="bi bi-trash2-fill"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Columna derecha: lo arma el js (ubicaciones hijas y objetos de la sala seleccionada) -->
        <div class="col-md-8" id="detalleSala">
        </div>
    </div>
</div>
<!-- Modal para crear sala, queda dentro de la sala seleccionada -->
<div class="modal fade" id="modalSala" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tituloModalSala" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalSala">Nueva sala</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="denominacion" class="form-label">Denominacion</label>
                    <input type="text" class="form-control" id="denominacion" name='denominacion'>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion</label>
                    <textarea class="form-control" id="descripcion" name='descripcion' rows="3"></textarea>
                </div>
            </div>
            <!-- Botones del lado izquierdo -->
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-success" id="btnGuardarSala">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para editar sala, se abre con el lapiz -->
<div class="modal fade" id="modalEditarSala" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tituloModalEditarSala" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Editar sala</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="denominacionEditar" class="form-label">Denominacion</label>
                    <input type="text" class="form-control" id="denominacionEditar" name='denominacionEditar'>
                </div>
                <div class="mb-3">
                    <label for="descripcionEditar" class="form-label">Descripcion</label>
                    <textarea class="form-control" id="descripcionEditar" name='descripcionEditar' rows="3"></textarea>
                </div>
            </div>
            <!-- Botones del lado izquierdo -->
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-success" id="btnActualizarSala">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para agregar un objeto a la sala seleccionada: lista los objetos que todavia no tienen ubicacion -->
<div class="modal fade" id="modalAgregarObjeto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tituloModalAgregarObjeto" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalAgregarObjeto">Agregar objeto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Buscador para filtrar la lista por denominacion o codigo -->
                <div class="mb-3">
                    <input type="text" class="form-control" id="buscadorObjetos" placeholder="Buscar objeto...">
                </div>
                <!-- La lista la arma el js -->
                <div class="list-group" id="listaObjetosLibres">
                </div>
            </div>
            <!-- Botones del lado izquierdo, guardar primero -->
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-success" id="btnGuardarAsignacion">Guardar</button>
                <button type="button" class="btn btn-secondary" id="btnCancelarAsignacion">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para confirmar el borrado de una sala -->
<div class="modal fade" id="modalBorrarSala" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tituloModalBorrarSala" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalBorrarSala">Borrar sala</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="textoBorrarSala"></p>
            </div>
            <!-- Botones del lado derecho (el de borrar es el unico modal asi) -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarBorrar">Borrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Toast de mensajes, lo usa la funcion mensajes del js -->
<div>
  <div id='toats'>
  </div>
</div>