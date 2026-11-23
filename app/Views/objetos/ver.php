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
                <td class="object-sheet-image-cell" rowspan="<?php echo in_array('f', session()->get('permisos')['objeto'] ?? [], true) ? 7 : 6; ?>">
                    <div class="object-sheet-image-dropzone">
                        <?php if (!empty($galeria)): ?>
                            <img
                                class="object-sheet-image-preview"
                                data-visor-abrir="0"
                                src="<?php echo base_url('objetos_multimedia/images/' . rawurlencode($galeria[0]['nombrearchivo'])); ?>"
                                alt="Imagen de <?php echo esc($objeto['denominacion']); ?>"
                            >
                        <?php else: ?>
                            <span class="object-sheet-image-placeholder">
                                <span>Sin imagen</span>
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bg-light fw-bold" style="width: 35%;">Número de inventario (ID)</td>
                <td><?php echo $objeto['id']; ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Nombre del objeto</td>
                <td><?php echo esc(mb_strtoupper(mb_substr($objeto['denominacion'], 0, 1)) . mb_substr($objeto['denominacion'], 1)); ?></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Código del objeto</td>
                <td><?php echo $objeto['codigo']; ?></td>
            </tr>
            <?php if (in_array('f', session()->get('permisos')['objeto'] ?? [], true)): ?>
            <tr>
                <td class="bg-light fw-bold">Fecha de Alta en Sistema</td>
                <td><?php echo date('d/m/Y', strtotime($objeto['fecha_alta'])); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="bg-light fw-bold">Descripción</td>
                <td style="text-align: justify;">
                    <?php echo $objeto['descripcion'] ? $objeto['descripcion'] : '-'; ?>
                </td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Ubicación física</td>
                <td>
                    <?php echo $ubicacion ? esc($ubicacion['denominacion']) : 'Sin ubicación'; ?>
                    <?php if ($ubicacion && !empty($ubicacion['fecha_baja'])): ?> (en la papelera)<?php endif; ?>
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
            <tr>
                <td colspan="3" class="bg-secondary text-white text-center fw-bold">
                    Archivos adjuntos
                </td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">Documentos</td>
                <td colspan="2">
                    <?php if (!empty($archivos)): ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($archivos as $archivo): ?>
                                <?php
                                    $urlArchivo = base_url('objetos_multimedia/file/' . rawurlencode($archivo['nombrearchivo']));
                                    $extension = strtolower(pathinfo($archivo['nombrearchivo'], PATHINFO_EXTENSION));
                                ?>
                                <li>
                                    <?php if ($extension === 'pdf'): ?>
                                        <a href="<?php echo $urlArchivo; ?>" target="_blank" rel="noopener"><?php echo esc($archivo['nombreoriginal']); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo $urlArchivo; ?>" download="<?php echo esc($archivo['nombreoriginal'], 'attr'); ?>"><?php echo esc($archivo['nombreoriginal']); ?></a>
                                    <?php endif; ?>
                                    <span class="text-muted">(<?php echo strtoupper($extension); ?>)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <?php if (!empty($galeria)): ?>
        <section class="ficha-galeria d-print-none" id="fichaGaleria" aria-label="Imágenes de la pieza">
            <h5 class="fw-bold text-center mb-3">
                Imágenes de la pieza <span class="text-muted">(<?php echo count($galeria); ?>)</span>
            </h5>
            <div class="ficha-galeria-grid">
                <?php foreach ($galeria as $indice => $imagen): ?>
                    <?php $url = base_url('objetos_multimedia/images/' . rawurlencode($imagen['nombrearchivo'])); ?>
                    <button type="button" class="ficha-galeria-item" data-src="<?php echo $url; ?>"
                            aria-label="Ver imagen <?php echo $indice + 1; ?> de <?php echo count($galeria); ?>">
                        <img src="<?php echo $url; ?>" loading="lazy"
                             alt="Imagen <?php echo $indice + 1; ?> de <?php echo esc($objeto['denominacion']); ?>">
                        <?php if ($imagen['portada'] == 1): ?>
                            <span class="ficha-galeria-badge">Portada</span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="visor-imagenes d-print-none" id="visorImagenes" hidden role="dialog" aria-modal="true" aria-label="Visor de imágenes">
            <button type="button" class="visor-cerrar" data-visor="cerrar" aria-label="Cerrar">&times;</button>
            <button type="button" class="visor-flecha visor-anterior" data-visor="anterior" aria-label="Imagen anterior">&#10094;</button>
            <figure class="visor-figura">
                <img id="visorImagen" alt="">
                <figcaption id="visorContador"></figcaption>
            </figure>
            <button type="button" class="visor-flecha visor-siguiente" data-visor="siguiente" aria-label="Imagen siguiente">&#10095;</button>
        </div>
    <?php endif; ?>

    <!-- Bloque que solo se imprime (sin loading="lazy" a propósito) -->
    <?php if (!empty($galeria)): ?>
        <section class="ficha-impresion" aria-hidden="true">
            <h5 class="ficha-impresion-titulo">
                Imágenes de la pieza: <?php echo esc($objeto['denominacion']); ?>
            </h5>
            <?php foreach ($galeria as $indice => $imagen): ?>
                <figure class="ficha-impresion-item">
                    <img src="<?php echo base_url('objetos_multimedia/images/' . rawurlencode($imagen['nombrearchivo'])); ?>" alt="">
                    <figcaption>
                        Imagen <?php echo $indice + 1; ?> de <?php echo count($galeria); ?><?php echo $imagen['portada'] == 1 ? ' (portada)' : ''; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <!-- Botón imprimir -->
    <div class="text-center d-print-none">
        <?php if (!empty($galeria)): ?>
            <button id="btn-imagenes-impresion" type="button" class="btn btn-outline-secondary btn-sm" aria-pressed="false">
                Imágenes al imprimir: No
            </button>
        <?php endif; ?>
        <button id="btn-imprimir" class="btn btn-primary btn-sm">
            Imprimir Ficha
        </button>
    </div>

</div>