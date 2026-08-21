<div class="login-page">
	<h1>Iniciar sesión</h1>
    <form action="<?php echo base_url();?>login/validacion" method="post">
    	<input type="text" name="denominacion" placeholder="Usuario" required="required" />
        <input type="password" name="password" placeholder="Contraseña" required="required" />
        <button  class="btn btn-primary btn-block btn-large"> Ingresar</button>
    </form>
    <!--<form class="login-form">
        <p class="message">¿No estás registrado? <a href='<?php echo base_url();?>registro/nuevo'>Crear una cuenta</a></p>
        <a href='<?php echo base_url();?>registro/recuperacion'>¿Olvidaste tu contraseña?</a>
    </form>-->
</div>