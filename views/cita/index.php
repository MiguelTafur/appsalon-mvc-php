<h1 class="nombre-pagina">Criar novo Agendamento</h1>
<p class="descripcion-pagina">Escolha seus serviços e insira seus dados</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<div class="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Serviços</button>
        <button type="button" data-paso="2">Informação Agendamento</button>
        <button type="button" data-paso="3">Resumo</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>serviços</h2>
        <p class="text-center">Escolha abaixo seus serviços</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Seus dados e Agendamento</h2>
        <p class="text-center">Insira seus dados e data do agendamento</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nome</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" value="<?= $nombre; ?>" disabled>
            </div>

            <div class="campo">
                <label for="fecha">Data</label>
                <input type="date" id="fecha" min="<?= date('Y-m-d'); ?>">
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <select id="hora">
                    <option value="">-- Escolha --</option>
                </select>
            </div>
            <input type="hidden" id="id" value="<?= $id; ?>">
        </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumo</h2>
        <p class="text-center">Verifique se as informações estão corretas</p>
    </div>

    <div class="paginacion">
        <button class="boton" id="anterior">&laquo; Retornar</button>
        <button class="boton" id="siguiente">Próximo &raquo;</button>
    </div>
</div>

<?php

    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    " 
?>