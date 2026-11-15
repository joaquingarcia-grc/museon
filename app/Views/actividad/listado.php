<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2><?php echo $titulo; ?></h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class='table-primary table-striped' id="tablaACT">
            <thead>
                <tr>
                    <th class='text-center'>Fecha</th>
                    <th class='text-center'>Usuario</th>
                    <th class='text-center'>Acción</th>
                    <th class='text-center'>Sobre</th>
                    <th class='text-center'>ID dato</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $acciones = ['a' => 'Alta', 'b' => 'Baja', 'm' => 'Modificación', 'r' => 'Recuperación'];
                $tablas = [
                    'objetos'          => 'Pieza',
                    'objeto_etiquetas' => 'Etiquetas de pieza',
                    'objeto_atributos' => 'Atributos de pieza',
                    'etiquetas'        => 'Etiqueta',
                    'atributos'        => 'Atributo',
                ];
                foreach ($actividad as $f): ?>
                <tr>
                    <td class='text-center'><?php echo date('d/m/Y H:i', strtotime($f['fecha'])); ?></td>
                    <td class='text-center'><?php echo esc($f['usuario']); ?></td>
                    <td class='text-center'><?php echo $acciones[$f['accion']]; ?></td>
                    <td class='text-center'><?php echo $tablas[$f['tabla']]; ?></td>
                    <td class='text-center'><?php echo $f['id_dato']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new DataTable('#tablaACT', { order: [] });
});
</script>