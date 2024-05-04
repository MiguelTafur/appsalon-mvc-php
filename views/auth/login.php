<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sessão com seus dados</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Seu Email" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){echo s($usuario->email);} ?>">
    </div>

    <div class="campo">
    <label for="password">Senha</label>
        <input type="password" id="password" name="password" placeholder="Sua Senha" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->password);} ?>">
    </div>

    <div class="campo">
    <label for="salonId">Código</label>
        <input type="password" id="salonId" name="salonId" placeholder="Seu Código" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->salonId);} ?>">
    </div>

    <input type="submit" class="boton" value="Iniciar Sessão">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Não tem conta? Crie uma</a>
    <a href="/olvide">Esqueceu a senha?</a>
</div>