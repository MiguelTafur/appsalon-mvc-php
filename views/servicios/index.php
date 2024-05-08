<h1 class="nombre-pagina">Serviços</h1>    
<p class="descripcion-pagina">Administração de Serviços</p>

<?php include __DIR__ . '/../templates/barra.php'; ?>

<ul class="servicios">
    <?php foreach ($servicios as $servicio) { ?>
        <li>
            <p>Nome: <span><?= $servicio->nombre; ?></span></p>
            <p>Preço: <span>R$<?= $servicio->precio; ?></span></p>

            <div class="acciones">
                <a href="/servicios/actualizar?id=<?= $servicio->id; ?>" class="boton">Atualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?= $servicio->id; ?>">

                    <input type="submit" class="boton-eliminar" value="Excluir">
                </form>
            </div>
        </li>
    <?php } ?>
</ul>