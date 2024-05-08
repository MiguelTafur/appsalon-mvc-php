let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
};

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); // MUESTRA Y OCULTA LAS SECCIONES
    tabs(); //CAMBIA LA SECCION CUANDO SE PRECIONEN LOS TABS
    botonesPaginador(); //AGREGA O QUITA LOS BOTONES DEL PAGINADOR
    paginaAnterior();
    paginaSiguiente();

    consultarAPI(); // CONSULTA LA API DEL BACKEND DE PHP

    idCliente();
    nombreCliente(); // AGREGA NOMBRE DEL CLIENTE AL OBJETO DE CITA
    seleccionarFecha();// AGREGA LA FECHA DE LA CITA EN EL OBJETO
    seleccionarHora();// AGREGA LA HORA DE LA CITA EN EL OBJETO
    mostrarResumen() //MUESTRA EL RESUMEN DE LA CITA
}

function mostrarSeccion() {

    //OCULTAR LA SECCION QUE TENGA LA CLASE DE MOSTRAR
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //SELECCIONAR LA SECCION CON EL PASO...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //QUITA LA CLASE SE ACTUAL AL TAB ANTERIOR
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //RESALTA EL TAB ACTUAL
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach( boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');    
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        if(paso <= pasoInicial) return;
        paso--;
        mostrarSeccion()
        botonesPaginador();
    });
}   

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if(paso >= pasoFinal) return;
        paso ++;
        mostrarSeccion()
        botonesPaginador();
    });
}

async function consultarAPI() {
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }

    try {
        const url = `/api/horas`;
        const resultado = await fetch(url);
        const horas = await resultado.json();
        mostrarHoras(horas);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = precio;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {
    const {id} = servicio
    const {servicios} = cita;

    // IDENTIFICAR EL ELEMENTO AL QUE SE LE DA CLICK
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    
    //COMPORBAS SI UN SERVICIO YA FUE AGREGADO
    if(servicios.some(agregado => agregado.id === id)) {
        //SI YA ESTÁ AGREGADO, ELIMINA EL SERVICIO
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else {
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha= document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();

        if([0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Horários de Segunda à Sexta', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
            desabilitarHoras();
        }
    })
}

async function desabilitarHoras() {
    try {
        const url = `/api/horasDisponibles?fecha=${cita.fecha}`;
        const resultado = await fetch(url);
        const horasNoDisponibles = await resultado.json();

        //DESABILITAR HORAS NO DISPONIBLES
        const hora = document.querySelectorAll('#hora option');
        //console.log(horasNoDisponibles);
        hora.forEach(hora => {
            const horaDisponible = horasNoDisponibles.find(horaNoDisponible => horaNoDisponible.hora === hora.value);
            if(horaDisponible) {
                hora.disabled = true;
                hora.classList.remove('disponible');
                hora.classList.add('disabled');
                //hora.textContent = `${hora.value} - Hora no Disponible`;
            } else {
                hora.disabled = false;
                hora.classList.remove('disabled');
                //hora.textContent = hora.value;
                hora.classList.add('disponible');
            }
        });

    } catch (error) {
        console.Console.log(error)
    }
}

function mostrarHoras(horas) {
    const horarios = document.querySelector('#hora');
    horas.forEach(hora => {
        const { id, hora: horaCita } = hora;
        
        const opcion = document.createElement('OPTION');
        opcion.value = horaCita;
        opcion.textContent = horaCita;
        opcion.dataset.hora_id = id;
        opcion.classList.add('disponible');

        horarios.appendChild(opcion);
    });
}

function seleccionarHora() {
    //SELECCIONAR LAHORA POR SU ID
    const horarios = document.querySelector('#hora');
    horarios.addEventListener('change', function(e) {
        const hora = e.target.value;
        const id = e.target.selectedOptions[0].dataset.hora_id;
        cita.hora = hora;
        cita.hora_id = id;
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // PREVIENE QUE SE GENERE MAS DE UNA ALERTA
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    // SCRIPTING PARA GENERAR UNA ALERTA
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    // ELIMINA LA ALERTA
    if(desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltam dados do serviço, data ou hora', 'error', '.contenido-resumen', false);
        return;
    }

    //FORMATEAR EL DIV DE RESUMEN
    const {nombre, fecha , hora, servicios} = cita;

    //HEADEING PARA SERVICIOS EN RESUMEN
    const headindServicios = document.createElement('H3');
    headindServicios.textContent = 'Resumo dos serviços';
    resumen.appendChild(headindServicios);

    //ITERANDO Y MOSTRANDO LOS SERVICIOS
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Preço:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //SUMAR EL TOTAL DE SERVICIOS
    const total = servicios.reduce((total, servicio) => total + parseFloat(servicio.precio), 0);
    const totalParrafo = document.createElement('P');
    totalParrafo.innerHTML = `<span>Total:</span> $${total}`;

    //HEADEING PARA SERVICIOS EN RESUMEN
    const headindCita = document.createElement('H3');
    headindCita.textContent = 'Resumo do Agendamento';
    resumen.appendChild(headindCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nome:</span> ${nombre}`;

    //FORMATEAR LA FECHA EN ESPANHOL
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();
    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('pt-BR', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Data:</span> ${fechaFormateada}`;
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    // MOSTRAR LA CANTIDAD DE SERVICIOS SELECCIONADOS
    const cantidadServicios = document.createElement('P');
    cantidadServicios.innerHTML = `<span>Quantidade de serviços:</span> ${servicios.length}`;

    //BOTON PARA CREAR UNA CITA
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Agendar'
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(cantidadServicios);
    resumen.appendChild(totalParrafo);
    resumen.appendChild(botonReservar);
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

async function reservarCita() {

    const {id, fecha, hora, hora_id, servicios} = cita;
    //console.log(cita);
    const idServicios = servicios.map(servicio => servicio.id);
    const datos = new FormData();

    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('horaId', hora_id);
    datos.append('servicios', idServicios);

    try {
        // Peticion hacia la API
        const url = `/api/citas`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        if(resultado.alertas) {
            Toast.fire({
                icon: 'error',
                title: resultado.alertas
            })
            return;
        } else {
            Toast.fire({
                icon: 'success',
                title: 'O Agendamento foi criado com sucesso.'
            }).then(() => {
                window.location.reload();
            })
        }

    } catch (error) {
        Toast.fire({
            icon: 'error',
            title: 'Oops! Ocorreu um erro ao salvar o Agendamento.'
        })
    }
}