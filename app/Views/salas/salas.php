<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col text-left">
            <h2>
                <?php echo $titulo; ?>
            </h2>
        </div>
        <div class="col text-right">
            <button type="button" class="btn btn-outline-success" id="btnNuevaSala" data-bs-toggle="modal" data-bs-target="#modalSala">
                Nueva Sala <i class="bi bi-door-open"></i>
            </button>
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
                        <?php foreach ($salas as $sala){?>
                            <li>
                                <div class="d-flex align-items-center py-1" id="<?php echo $sala["id"];?>" style="cursor:pointer;">
                                    <?php if (!empty($sala['hijas'])): ?>
                                        <i class="bi bi-caret-down-fill me-2"></i>
                                        <i class="bi bi-door-open me-2"></i>
                                    <?php else: ?>
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <i class="bi bi-door-closed me-2"></i>
                                    <?php endif; ?>
                                    <span><?php echo esc($sala['denominacion']); ?></span>
                                </div>
                                <?php if (!empty($sala['hijas'])): ?>
                                    <ul class="list-unstyled ms-4">
                                        <?php foreach ($sala['hijas'] as $hija): ?>
                                            <li>
                                                <div class="d-flex align-items-center py-1" id="sala_<?php echo $hija['id']; ?>" style="cursor:pointer;">
                                                    <i class="bi bi-caret-right-fill me-2"></i>
                                                    <i class="bi bi-door-closed me-2"></i>
                                                    <span><?php echo esc($hija['denominacion']); ?></span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php };?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Columna derecha: lo arma el js -->
        <div class="col-md-8" id="detalleSala">
        </div>
    </div>
</div>
<!-- Modal para crear sala y elegir donde queda -->
<div class="modal fade" id="modalSala" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tituloModalSala" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalSala">Nueva sala</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="denominacionSala" class="form-label">Denominacion</label>
                    <input type="text" class="form-control" id="denominacionSala">
                </div>
                <div class="mb-3">
                    <label for="descripcionSala" class="form-label">Descripcion</label>
                    <textarea class="form-control" id="descripcionSala" rows="3"></textarea>
                </div>

                <hr>
                <h6 class="mb-3"><i class="bi bi-geo-alt"></i> Ubicacion</h6>
                <div class="mb-3">
                    <label for="salaPadre" class="form-label">Dentro de la sala</label>
                    <select class="form-select" id="salaPadre">
                        <option value="">Ninguna (queda dentro del museo)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarSala">Guardar</button>
            </div>
        </div>
    </div>
</div>