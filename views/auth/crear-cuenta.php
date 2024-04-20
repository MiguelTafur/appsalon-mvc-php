<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el formulario para crear una cuenta</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
?>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre" value="<?= s($usuario->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Tu Apellido" value="<?= s($usuario->apellido); ?>">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" placeholder="Tu Teléfono" value="<?= s($usuario->telefono); ?>">
    </div>

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="text" id="email" name="email" placeholder="Tu E-mail" value="<?= s($usuario->email); ?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu Password" value="<?= s($usuario->password); ?>">
    </div>

    <input type="submit" class="boton" value="Crear-cuenta">
</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">Olvidaste tu password?</a>
</div>