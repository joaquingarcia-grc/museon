<?php
$permisosVista = session()->get('permisos') ?? [];
$puedeEditarImagen = in_array('m', $permisosVista['objeto'] ?? [], true);
?>

<div class="container-fluid mt-4 mb-5 object-sheet-page">
    <!-- Botón para volver  -->
    <div class="mb-3 d-print-none object-sheet-actions">
        <div class="col text-right">
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Volver al Listado <i class="bi bi-box"></i>
            </a>
        </div>
    </div>

    <h4 class="text-center fw-bold mb-4 object-sheet-title">
        FICHA IDENTITARIA OBJETO
    </h4>

    <!-- Tabla -->
    <table class="table table-bordered border-dark object-sheet-table">
        <tbody>
            <!-- Datos fijos del Objeto -->
            <tr>
                <td colspan="3" class="bg-secondary text-white text-center fw-bold">
                    Datos Esenciales
                </td>
            </tr>
            <tr>
                <td class="bg-light fw-bold" style="width: 35%;">Responsable</td>
                <td><?php echo $objeto['cargado_por']; ?></td>
                <td class="object-sheet-image-cell" rowspan="6">
                    <?php $errorImagen = session()->getFlashdata('errorImagen'); ?>
                    <?php if ($errorImagen): ?>
                        <div class="alert alert-danger d-print-none" role="alert">
                            <?php echo esc($errorImagen); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($puedeEditarImagen): ?>
                        <form action="<?php echo base_url('objetos/imagen/' . $objeto['id']); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                    <?php endif; ?>
                        <label class="object-sheet-image-dropzone" for="imagenObjeto">
                            <?php if ($puedeEditarImagen): ?>
                                <input type="file" id="imagenObjeto" name="imagen" accept="image/jpeg,image/png,image/gif,image/webp" class="object-sheet-image-input">
                            <?php endif; ?>
                            <img
                                id="vistaPreviaImagen"
                                class="object-sheet-image-preview"
                                src="<?php echo !empty($objeto['imagen']) ? base_url('imagenes/objetos/' . rawurlencode($objeto['imagen'])) : ''; ?>"
                                alt="Imagen de <?php echo esc($objeto['denominacion']); ?>"
                                <?php echo empty($objeto['imagen']) ? 'hidden' : ''; ?>
                            >
                            <?php if ($puedeEditarImagen): ?>
                                <span id="indicacionImagen" class="object-sheet-image-placeholder" <?php echo !empty($objeto['imagen']) ? 'hidden' : ''; ?>>
                                    <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                                    <span>Haz clic o arrastra una imagen</span>
                                    <small>JPG, PNG, GIF o WEBP (máx. 5 MB)</small>
                                </span>
                            <?php elseif (empty($objeto['imagen'])): ?>
                                <span class="object-sheet-image-placeholder">
                                    <span>Sin imagen</span>
                                </span>
                            <?php endif; ?>
                        </label>
                        <?php if ($puedeEditarImagen): ?>
                            <button type="submit" class="btn btn-secondary btn-sm mt-2 d-print-none">Guardar imagen</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="bg-light fw-bold" style="width: 35%;">Número de inventario (ID)</td>
                <td><?php echo $objeto['id']; ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Nombre del objeto</td>
                <td><?php echo $objeto['denominacion']; ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">código del objeto</td>
                <td><?php echo $objeto['codigo']; ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Fecha de Alta en Sistema</td>
                <td><?php echo date('d/m/Y', strtotime($objeto['fecha_alta'])); ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Descripción</td>
                <td style="text-align: justify;">
                    <?php echo $objeto['descripcion'] ? $objeto['descripcion'] : '-'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="bg-secondary text-white text-center fw-bold">
                    Detalle de Atributos
                </td>
            </tr>
            <!-- Atributos Dinámicos -->
            <?php if (!empty($atributos)): ?>
                <?php foreach ($atributos as $atr): ?>
                    <tr>
                        <td class="bg-light fw-bold"><?php echo $atr['denominacion']; ?></td>
                        <td colspan="2">
                            <?php echo $atr['valor']; ?>
                            <?php if (!empty($atr['unidad'])): ?>
                                <?php echo ' ' . $atr['unidad']; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="bg-light fw-bold">Atributos</td>
                    <td class="text-muted fst-italic">Sin atributos registrados</td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="3" class="bg-secondary text-white text-center fw-bold">
                    Detalle de Etiquetas
                </td>
            </tr>
            <!-- Etiquetas -->
            <tr>
                <td class="bg-light fw-bold">Etiquetas</td>
                <td colspan="2">
                    <?php
                    if (!empty($etiquetas)) {
                        $nombres_etiquetas = array_column($etiquetas, 'denominacion');
                        echo implode(', ', $nombres_etiquetas);
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <script src="<?php echo base_url(); ?>vendor/js/jsproyecto/object-sheet-image.js"></script>

    <!-- Botón imprimir -->
    <div class="text-center d-print-none">
        <button id="btn-imprimir" class="btn btn-primary btn-sm">
            Imprimir Ficha
        </button>
    </div>

</div>
</div>