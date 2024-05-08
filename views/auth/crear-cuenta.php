<h1 class="nombre-pagina">Criar Conta</h1>
<p class="descripcion-pagina">Preencha o formulário para criar uma conta</p>

<?php 
    include __DIR__ . '/../templates/alertas.php';
?>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nome</label>
        <input type="text" id="nombre" name="nombre" placeholder="Seu Nome" value="<?= s($usuario->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Sobrenome</label>
        <input type="text" id="apellido" name="apellido" placeholder="Seu Sobrenome" value="<?= s($usuario->apellido); ?>">
    </div>

    <div class="campo">
        <label for="telefono">Telefone</label>
        <input type="text" id="telefono" name="telefono" placeholder="Seu Telefone" value="<?= s($usuario->telefono); ?>">
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Seu Email" value="<?= s($usuario->email); ?>">
    </div>

    <div class="campo">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" placeholder="Sua Senha" value="<?= s($usuario->password); ?>">
    </div>

    <div class="campo">
    <label for="salonId">Código</label>
        <input type="password" id="salonId" name="salonId" placeholder="Seu Código" value="<?= s($usuario->salonId); ?>">
    </div>

    <input type="submit" class="boton" value="Criar Conta">
</form>

<div class="acciones">
    <a href="/">Já tem uma conta? Inicia Sessão</a>
    <a href="/olvide">Esqueceu a senha?</a>
</div>