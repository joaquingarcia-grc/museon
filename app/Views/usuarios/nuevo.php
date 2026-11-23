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
            <a class="btn btn-success" href="<?php echo base_url();?>usuarios/">
                Usuarios <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <!-- aviso de campos obligatorios -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        Los campos con <strong>*</strong> son obligatorios.
                    </div>

                    <form id='formularioUSR' method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="denominacion">Denominación *</label>
                                <input class="form-control" type="text" placeholder="" id="denominacion" name="denominacion" aria-label="default input example" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono">Teléfono</label>
                                <input class="form-control" type="text" placeholder="" id="telefono" name="telefono" aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id='password' placeholder="••••••••" required>
                                    <button type="button" class="btn btn-outline-secondary" data-mostrar-contrasena aria-controls="password" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 8 caracteres y un carácter especial.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rol" class="form-label">Rol *</label>
                                <select class="form-select" name="rol" id="rol" required>
                                    <option value="">Seleccioná un rol</option>
                                <?php foreach ($roles as $rol){?>
                                    <option value="<?php echo esc($rol["id"], 'attr');?>">
                                        <?php echo esc($rol["denominacion"]);?>
                                    </option>
                                <?php };?>
                                    <option value="otros">Otros...</option>
                                </select>
                            </div>
                            <!-- link que abre el modal con los permisos, al lado del rol -->
                            <div class="col-md-6 d-flex align-items-end">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalPermisosROL">
                                    <i class="bi bi-shield-lock-fill"></i> Ver qué puede hacer cada rol
                                </a>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" type="submit" id="btnGuardar" >Guardar</button>
                            <a href="<?php echo base_url();?>usuarios/" class="btn btn-outline-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de permisos de los roles -->
<div class="modal fade" id="modalPermisosROL" tabindex="-1" aria-labelledby="tituloPermisosROL" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tituloPermisosROL">Permisos de cada rol</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="listaPermisosRoles">
        <?php
        $modulosRol = ['objeto' => 'Piezas', 'etiqueta' => 'Etiquetas', 'atributo' => 'Atributos', 'usuario' => 'Usuarios', 'sala' => 'Salas'];
        $accionesRol = ['l' => 'ver', 'a' => 'crear', 'm' => 'editar', 'b' => 'eliminar', 'f' => 'ver fecha de alta'];
        foreach ($roles as $rol):
        ?>
            <div class="mb-3">
                <strong><?php echo esc($rol['denominacion']); ?></strong>
                <ul>
                    <?php foreach ($modulosRol as $modulo => $nombre): ?>
                        <?php
                        $acciones = empty($rol[$modulo]) ? [] : explode(',', $rol[$modulo]);
                        $nombres = [];
                        foreach ($accionesRol as $codigo => $accion) {
                            if (in_array($codigo, $acciones, true)) $nombres[] = $accion;
                        }
                        ?>
                        <li><?php echo esc($nombre . ': ' . ($nombres ? implode(', ', $nombres) : 'sin permisos')); ?></li>
                    <?php endforeach; ?>
                    <li>Actividad: <?php echo in_array('l', explode(',', $rol['actividad']), true) ? 'ver secciones permitidas' : 'sin permiso'; ?></li>
                </ul>
            </div>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer justify-content-start">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-labelledby="tituloNuevoRol" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formNuevoRol">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tituloNuevoRol">Crear rol</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <label for="nombreNuevoRol" class="form-label">Denominación *</label>
          <input type="text" class="form-control mb-3" id="nombreNuevoRol" name="denominacion" maxlength="50" required>
          <p>Elegí qué puede hacer en cada sección. Para crear, editar, eliminar o ver la fecha de alta también necesita permiso para ver.</p>
          <?php $permisosSesion = session()->get('permisos') ?? []; ?>
          <?php foreach ($modulosRol as $modulo => $nombre): ?>
            <fieldset class="mb-3">
              <legend class="fs-6"><?php echo esc($nombre); ?></legend>
              <?php foreach ($accionesRol as $codigo => $accion): ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input permiso-rol" type="checkbox" name="permisos[<?php echo $modulo; ?>][]" id="permiso_<?php echo $modulo . '_' . $codigo; ?>" value="<?php echo $codigo; ?>" <?php echo session()->get('rol') != 1 && !in_array($codigo, $permisosSesion[$modulo] ?? [], true) ? 'disabled' : ''; ?>>
                  <label class="form-check-label" for="permiso_<?php echo $modulo . '_' . $codigo; ?>"><?php echo ucfirst($accion); ?></label>
                </div>
              <?php endforeach; ?>
            </fieldset>
          <?php endforeach; ?>
          <fieldset class="mb-3">
            <legend class="fs-6">Actividad</legend>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="actividad" id="permiso_actividad" value="l" <?php echo session()->get('rol') != 1 && !in_array('l', $permisosSesion['actividad'] ?? [], true) ? 'disabled' : ''; ?>>
              <label class="form-check-label" for="permiso_actividad">Ver actividad de las secciones permitidas</label>
            </div>
          </fieldset>
          <div class="alert alert-danger" id="errorNuevoRol" role="alert" hidden></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="guardarNuevoRol">Crear rol</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de guardado -->
<div class="modal fade" id="modalGuardadoUSR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Guardar Datos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro que desea guardar los datos?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary btn-modalGuardado" id="btnGuardarUSR">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>