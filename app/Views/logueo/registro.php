<div class="login-page">
    <div class="login-container">
        <div class="login-card">

            <div class="login-header">
                <i class="bi bi-bank"></i>
                <h2>Iniciar sesión</h2>
            </div>

            <form id="formularioIngreso" class="login-form" method="post">
                <div class="form-group">
                    <label for="denominacion" class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="denominacion" id="denominacion" placeholder="Usuario" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    Ingresar <i class="bi bi-box-arrow-in-right"></i>
                </button>
            </form>

            <!-- Footer opcional (lo tenías comentado)
            <div class="login-footer text-center">
                <p>¿No estás registrado?</p>
                <a class="link-register" href="<?php echo base_url();?>registro/nuevo">Crear una cuenta</a>
            </div>
            -->

        </div>
    </div>
</div>

<div>
    <div id="toats"></div>
</div>