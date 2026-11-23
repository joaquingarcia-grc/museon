<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col text-left">
            <h2><?php echo $titulo; ?></h2>
        </div>
    </div>
    <form id="filtroActividad" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
            <span class="form-label d-block" id="tituloTipoActividad">Tipo de registro afectado</span>
            <details id="filtroTipo" class="selector-tipo-actividad">
                <summary class="form-select" aria-labelledby="tituloTipoActividad tipoSeleccionado">
                    <span id="tipoSeleccionado">Todos los tipos</span>
                </summary>
                <div class="opciones-tipo-actividad">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoActividad" id="tipoTodos" value="" checked>
                        <label class="form-check-label" for="tipoTodos">Todos los tipos</label>
                    </div>
                    <?php
                    $tiposActividad = [
                        ['Pieza', 'Ejemplo: crear o editar una pieza.', 'objetos'],
                        ['Etiquetas de pieza', 'Ejemplo: asignar una etiqueta a una pieza. Al editarla también se registra aunque no cambie.', 'objeto_etiquetas'],
                        ['Atributos de pieza', 'Ejemplo: asignar un atributo a una pieza. Al editarla también se registra aunque no cambie.', 'objeto_atributos'],
                        ['Etiqueta', 'Ejemplo: crear o editar la etiqueta Pintura.', 'etiquetas'],
                        ['Atributo', 'Ejemplo: crear o editar el atributo Peso.', 'atributos']
                    ];
                    foreach ($tiposActividad as $indice => $tipo):
                    if (!in_array($tipo[2], $tablasPermitidas, true)) continue;
                    ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoActividad" id="tipoActividad<?php echo $indice; ?>" value="<?php echo esc($tipo[0], 'attr'); ?>" aria-label="<?php echo esc($tipo[0] . ': ' . $tipo[1], 'attr'); ?>">
                            <label class="form-check-label ayuda-actividad" for="tipoActividad<?php echo $indice; ?>" data-tipo="<?php echo esc($tipo[0], 'attr'); ?>" data-ayuda="<?php echo esc($tipo[1], 'attr'); ?>"><?php echo esc($tipo[0]); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        </div>
        <div class="col-md-3">
            <label for="criterioActividad" class="form-label">Filtrar además por</label>
            <select id="criterioActividad" class="form-select">
                <option value="todos">Todos los campos</option>
                <option value="fecha">Fecha</option>
                <option value="usuario">Usuario</option>
                <option value="accion">Acción</option>
                <?php if (in_array('objetos', $tablasPermitidas, true)): ?>
                <option value="pieza">ID de pieza (exacto)</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-3 valor-actividad" id="filtroTexto">
            <label for="valorActividad" class="form-label">Buscar</label>
            <input type="text" id="valorActividad" class="form-control" placeholder="Ingresá un texto">
        </div>
        <div class="col-md-3 valor-actividad" id="filtroFecha" hidden>
            <label for="fechaActividad" class="form-label">Fecha</label>
            <input type="date" id="fechaActividad" class="form-control">
        </div>
        <div class="col-md-3 valor-actividad" id="filtroUsuario" hidden>
            <label for="usuarioActividad" class="form-label">Usuario que realizó la acción</label>
            <select id="usuarioActividad" class="form-select">
                <option value="">Todos los usuarios</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo esc($usuario['denominacion'], 'attr'); ?>"><?php echo esc($usuario['denominacion']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <fieldset class="col-md-6 valor-actividad" id="filtroAccion" hidden>
            <legend class="form-label fs-6">Acción realizada</legend>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="accionActividad" id="accionTodas" value="" checked>
                <label class="form-check-label" for="accionTodas">Todas las acciones</label>
            </div>
            <?php
            $accionesActividad = [
                ['Alta', 'Se creó un registro o se agregó una relación.'],
                ['Baja', 'Se envió un registro a la papelera.'],
                ['Modificación', 'Se guardó una edición.'],
                ['Recuperación', 'Se recuperó un registro de la papelera.']
            ];
            foreach ($accionesActividad as $indice => $accion):
            ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accionActividad" id="accionActividad<?php echo $indice; ?>" value="<?php echo esc($accion[0], 'attr'); ?>" aria-label="<?php echo esc($accion[0] . ': ' . $accion[1], 'attr'); ?>">
                    <label class="form-check-label ayuda-actividad" for="accionActividad<?php echo $indice; ?>" data-accion="<?php echo esc($accion[0], 'attr'); ?>" data-ayuda="<?php echo esc($accion[1], 'attr'); ?>"><?php echo esc($accion[0]); ?></label>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <div class="col-md-auto">
            <button type="submit" id="buscarActividad" class="btn btn-primary">Filtrar</button>
            <button type="button" id="limpiarActividad" class="btn btn-outline-secondary">Limpiar</button>
        </div>
    </form>
    <p class="text-muted">Elegí un tipo de registro y, si querés, otro criterio; luego presioná Filtrar. Se muestran los últimos 300 registros; el filtro por ID de pieza consulta todo su historial registrado.</p>
    <div id="errorActividad" class="alert alert-danger" role="alert" hidden></div>
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
            <tbody id="bodyTablaACT"></tbody>
        </table>
    </div>
</div>