<h1 class="nombre-pagina">Recuperar senha</h1>
<p class="descripcion-pagina">Digite sua nova senha abaixo</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
    
    if($error) return;
?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Sua nova Senha">
    </div>
    <input type="submit" class="boton" value="Salvar nova senha">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Não tem conta? Crie uma</a>
    <a href="/olvide">Esqueceu a senha?</a>
</div>