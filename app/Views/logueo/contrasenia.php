<div class="login-page">
	<h1><?php echo $titulo?></h1>
    <form method="post">
    	<input type="email" name="email" placeholder="Correo electrónico" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Enviar enlace</button>
    </form>
    <form class="login-form">
        <p class="message">¿Ya te acordaste? <a href='<?php echo base_url();?>registro/'>Volver al inicio de sesión</a></p>
        <a href='<?php echo base_url();?>registro/nuevo'>¿No tenés cuenta? Registrate</a>
    </form>
</div>