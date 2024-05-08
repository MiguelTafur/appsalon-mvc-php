<h1 class="nombre-pagina">Novo Serviço</h1>    
<p class="descripcion-pagina">Preencha todos os campos para adicionar um novo serviço</p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<form action="/servicios/crear" method="POST" class="formulario">

<?php include_once __DIR__ .  '/formulario.php' ?>
    
    <input type="submit" class="boton" value="Salvar serviço">

    <a href="/servicios" class="boton">retornar</a>
</form>