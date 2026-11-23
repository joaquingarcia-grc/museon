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
                    <form data-id="<?php echo esc($usuarios['id'], 'attr');?>" id="actualizarUSR" method="post"
                          data-rol-inicial="<?php echo esc($usuarios['id_rol'], 'attr'); ?>"
                          data-permisos-iniciales="<?php echo esc(json_encode($permisosPersonalizados), 'attr'); ?>"
                          data-permisos-actor="<?php echo esc(json_encode(session()->get('permisos') ?? []), 'attr'); ?>"
                          data-administrador="<?php echo session()->get('rol') == 1 ? '1' : '0'; ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="denominacion">Denominación</label>
                                <input class="form-control" type="text" placeholder="" 
                                id="denominacion" name="denominacion" 
                                value="<?php echo($usuarios['denominacion']); ?>"
                                aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                value="<?php echo($usuarios['email']); ?>"
                                name="email" placeholder="name@example.com">
                            </div>
                        </div>         
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono">Teléfono</label>
                                <input class="form-control" type="text" placeholder="" 
                                id="telefono" name="telefono" 
                                value="<?php echo($usuarios['telefono']); ?>"
                                aria-label="default input example">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id='password' name="password" placeholder="••••••••">
                                <div class="form-text">Dejar vacío para mantener la actual.</div>
                                <div class="form-text">Si la cambiás: mínimo 8 caracteres y un carácter especial (# ! * @ $ % & ? ¿).</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select" name="rol" id="rol" required>
                                <?php foreach ($roles as $rol){?>
                                    <option value="<?php echo esc($rol["id"], 'attr');?>" data-permisos="<?php echo esc(json_encode($rol), 'attr'); ?>"
                                    <?php if ($rol['id']==$usuarios['id_rol']){
                                        echo  'selected';
                                    } ?>
                                    > <?php echo esc($rol["denominacion"]);?></option>
                                <?php };?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="personalizarPermisos">
                                <label class="form-check-label" for="personalizarPermisos">Personalizar permisos de este usuario</label>
                            </div>
                            <div class="form-text">Los cambios solo afectan a este usuario, no a las demás personas con el mismo rol. Al cambiar de rol se restablecen sus permisos.</div>
                        </div>
                        <div id="permisosUsuario" class="border rounded p-3 mb-3" hidden>
                            <p>Elegí qué puede hacer este usuario. Para crear, editar, eliminar o ver la fecha de alta también necesita permiso para ver.</p>
                            <?php
                            $modulosRol = ['objeto' => 'Piezas', 'etiqueta' => 'Etiquetas', 'atributo' => 'Atributos', 'usuario' => 'Usuarios', 'sala' => 'Salas'];
                            $accionesRol = ['l' => 'Ver', 'a' => 'Crear', 'm' => 'Editar', 'b' => 'Eliminar', 'f' => 'Ver fecha de alta'];
                            foreach ($modulosRol as $modulo => $nombre):
                            ?>
                            <fieldset class="mb-3">
                                <legend class="fs-6"><?php echo esc($nombre); ?></legend>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input seleccionar-todos-permisos" type="checkbox" id="usuario_todos_permiso_<?php echo $modulo; ?>">
                                    <label class="form-check-label" for="usuario_todos_permiso_<?php echo $modulo; ?>">Todos</label>
                                </div>
                                <?php foreach ($accionesRol as $codigo => $accion): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input permiso-rol permiso-usuario" type="checkbox" name="permisos[<?php echo $modulo; ?>][]" id="usuario_permiso_<?php echo $modulo . '_' . $codigo; ?>" data-modulo="<?php echo $modulo; ?>" value="<?php echo $codigo; ?>">
                                    <label class="form-check-label" for="usuario_permiso_<?php echo $modulo . '_' . $codigo; ?>"><?php echo esc($accion); ?></label>
                                </div>
                                <?php endforeach; ?>
                            </fieldset>
                            <?php endforeach; ?>
                            <fieldset>
                                <legend class="fs-6">Actividad</legend>
                                <div class="form-check">
                                    <input class="form-check-input permiso-usuario" type="checkbox" name="actividad" id="usuario_permiso_actividad" data-modulo="actividad" value="l">
                                    <label class="form-check-label" for="usuario_permiso_actividad">Ver actividad de las secciones permitidas</label>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success" type="submit"  >Actualizar</button>
                                <a href="<?php echo base_url();?>usuarios/" class="btn btn-outline-danger">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
  <div id='toats'>
  </div>
</div>