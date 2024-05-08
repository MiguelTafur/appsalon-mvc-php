<h1 class="nombre-pagina">Atualizar Serviço</h1>    
<p class="descripcion-pagina">Modifique os valores do formulário</p>

<?php 
    include __DIR__ . '/../templates/barra.php'; 
    include __DIR__ . '/../templates/alertas.php'; 
?>

<form method="POST" class="formulario">
    <?php include_once __DIR__ .  '/formulario.php' ?>
    <input type="submit" class="boton" value="Atualizar">
</form>