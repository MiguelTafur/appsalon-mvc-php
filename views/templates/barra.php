<div class="barra">
    <p>Olá: <?= $nombre ?? ''; ?></p>   

    <a href="/logout" class="boton">Sair</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?>

    <div class="barra-servicios">
        <a href="/admin" class="boton">Agendamentos</a>
        <a href="/servicios" class="boton">Serviços</a>
        <a href="/servicios/crear" class="boton">Novo Serviço</a>
    </div>

<?php } ?>