<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-bank2"></i>
            <h2>Iniciar Sesión</h2>
        </div>
        
        <form action="<?php echo base_url();?>login/validacion" method="post" class="login-form">
            <div class="form-group mb-3">
                <label for="denominacion" class="form-label">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" id="denominacion" name="denominacion" placeholder="Ingrese su usuario" required>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar
            </button>
        </form>

        <div class="login-footer mt-4 text-center">
            <p class="mb-1">¿No tienes una cuenta?</p>
            <a href="<?php echo base_url();?>usuarios/nuevo" class="link-register">
                <i class="bi bi-person-plus-fill"></i> Crear usuario
            </a>
        </div>
    </div>
</div>