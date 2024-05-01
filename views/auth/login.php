<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesión con tus datos</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Tu Email" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){echo s($usuario->email);} ?>">
    </div>

    <div class="campo">
    <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu Password" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->password);} ?>">
    </div>

    <div class="campo">
    <label for="salonId">Código</label>
        <input type="password" id="salonId" name="salonId" placeholder="Tu Código" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->salonId);} ?>">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">Olvidaste tu password?</a>
</div>