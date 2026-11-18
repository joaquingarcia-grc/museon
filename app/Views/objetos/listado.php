<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2> 
                <?php echo $titulo; ?>
            </h2>
        </div>
        <?php $permisos = session()->get('permisos') ?? []; ?>
        <?php if (in_array('a', $permisos['objeto'] ?? [])): ?> 
        <div class='col text-right'>
            <a class="btn btn-outline-success" href="<?php echo base_url();?>objetos/nuevo">
                Nuevo piezas <i class="bi bi-box-seam-fill"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row align-items-center my-3">
        <div class="col">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFiltroEtiquetas">
                Filtrar piezas <i class="bi bi-funnel"></i>
            </button>
            <span class="ms-2 text-muted" id="resumenFiltroEtiquetas">Sin filtros aplicados</span>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table-primary table-striped' id="tablaOBJ">
            <thead>
                <tr>
                    <th class='text-center'>ID</th>
                    <th class='text-center'>Código</th>
                    <th class='text-center'>Denominacion</th>
                    <th class='text-center'>Descripcion</th>
                    <th class='text-center'>Cargado por</th>
                    <th class='text-center'>Fecha alta</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody id='bodyTablaOBJ'>

            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalFiltroEtiquetas" tabindex="-1" aria-labelledby="modalFiltroEtiquetasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form class="modal-content" id="formularioFiltroEtiquetas">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFiltroEtiquetasLabel">Filtros de piezas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Los filtros se combinan. Al elegir varias etiquetas, se mostrarán piezas que tengan al menos una de ellas.</p>
                <div class="mb-3">
                    <label for="descripcionFiltro" class="form-label">Descripción</label>
                    <input type="search" class="form-control" id="descripcionFiltro" name="descripcion_filtro" placeholder="Buscar texto en la descripción...">
                </div>
                <div class="mb-3">
                    <label for="creadoPorFiltro" class="form-label">Dado de alta por</label>
                    <select class="form-select" id="creadoPorFiltro" name="creado_por">
                        <option value="">Cualquier usuario</option>
                        <?php foreach ($usuariosFiltro as $usuario): ?>
                            <option value="<?= esc($usuario['id']) ?>"><?= esc($usuario['denominacion']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <fieldset class="mb-3">
                    <legend class="form-label fs-6">Fecha</legend>
                    <label for="tipoFechaFiltro" class="form-label">Filtrar por fecha de</label>
                    <select class="form-select mb-2" id="tipoFechaFiltro" name="tipo_fecha">
                        <option value="alta">Alta</option>
                        <option value="edicion">Modificación</option>
                    </select>
                    <div class="row g-2">
                        <div class="col">
                            <label for="fechaDesdeFiltro" class="form-label">Desde</label>
                            <input type="date" class="form-control" id="fechaDesdeFiltro" name="fecha_desde">
                        </div>
                        <div class="col">
                            <label for="fechaHastaFiltro" class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="fechaHastaFiltro" name="fecha_hasta">
                        </div>
                    </div>
                </fieldset>
                <input type="search" class="form-control mb-3" id="buscarEtiquetaFiltro" placeholder="Buscar etiqueta..." autocomplete="off">
                <div class="list-group" id="listaEtiquetasFiltro">
                    <?php if (empty($etiquetas)): ?>
                        <p class="text-muted mb-0">No hay etiquetas disponibles.</p>
                    <?php else: ?>
                        <?php foreach ($etiquetas as $etiqueta): ?>
                            <label class="list-group-item d-flex align-items-center gap-2">
                                <input class="filtro-etiqueta" style="flex: 0 0 1rem; width: 1rem; height: 1rem; margin: 0;" type="checkbox" name="etiqueta_ids[]" value="<?= esc($etiqueta['id']) ?>">
                                <span><?= esc($etiqueta['denominacion']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="limpiarFiltroEtiquetas">Quitar filtros</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="aplicarFiltroEtiquetas">Aceptar</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalBorradoOBJ" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmacion de borrado</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Segura desa borrar este dato?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-danger" id="botonBorradoOBJ">Borrar</button>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>

