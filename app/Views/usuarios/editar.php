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
                    <form data-id="<?php echo $usuarios['id'];?>" id="actualizarUSR" method="post">
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
                                    <option value="<?php echo esc($rol["id"], 'attr');?>"
                                    <?php if ($rol['id']==$usuarios['id_rol']){
                                        echo  'selected';
                                    } ?>
                                    > <?php echo esc($rol["denominacion"]);?></option>
                                <?php };?>
                                </select>
                            </div>
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