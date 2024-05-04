<h1 class="nombre-pagina">Recuperar Senha</h1>
<p class="descripcion-pagina">Redefina sua senha digitando seu e-mail e Código abaixo</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Seu Email" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->email);} ?>">
    </div>

    <div class="campo">
    <label for="salonId">Código</label>
        <input type="password" id="salonId" name="salonId" placeholder="Seu Código" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST'){ echo s($usuario->salonId);} ?>">
    </div>

    <input type="submit" value="Enviar instruções" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Não tem conta? Crie uma</a>
    <a href="/olvide">Esqueceu a senha?</a>
</div>