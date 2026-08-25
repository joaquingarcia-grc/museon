<div class="container mt-4 mb-5">
    
    <!-- Botón para volver (fuera del área de impresión) -->
    <div class="mb-3 d-print-none" style="max-width: 850px; margin: 0 auto;">
       
       <div class="col text-right">
            <a class="btn btn-success" href="<?php echo base_url();?>objetos/">
                Volver al Listado <i class="bi bi-box">

                </i>
            </a>
        </div>
    </div>

        <h4 class="text-center fw-bold mb-4" style="font-family: 'Times New Roman', serif;">
            FICHA IDENTITARIA OBJETO
        </h4>

        <!-- TablaS -->
        <table class="table table-bordered border-dark" style="font-size: 14px;">
            <tbody>
                <!-- Datos fijos del Objeto -->
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
                <br>
                <tr>
                    <td class="bg-light fw-bold">Descripción</td>
                    <td style="text-align: justify;">
                        <?php echo $objeto['descripcion'] ? $objeto['descripcion'] : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="bg-danger text-white text-center fw-bold">
                        Detalle de Atributos
                    </td>
                </tr>
                <!-- Atributos Dinámicos (Se listan como filas de la tabla) -->
                <?php if (!empty($atributos)): ?>
                    <?php foreach ($atributos as $atr): ?>
                        <tr>
                            <td class="bg-light fw-bold"><?php echo $atr['denominacion']; ?></td>
                            <td><?php echo $atr['valor']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="bg-light fw-bold">Atributos</td>
                        <td class="text-muted fst-italic">Sin atributos registrados</td>
                    </tr>
                <?php endif; ?>

                <!-- Etiquetas -->
                <tr>
                    <td class="bg-light fw-bold">Etiquetas</td>
                    <td>
                        <?php 
                        if (!empty($etiquetas)) {
                            // Extraemos solo los nombres y los separamos por coma
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

    </div>
</div>
<div class="text-center">
     <button onclick="window.print()" class=" btn btn-primary btn-sm">
            Imprimir Ficha
        </button>
</div>